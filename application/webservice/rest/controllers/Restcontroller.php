<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Rest Controller
 *
 * @category webservice
 *            
 * @package rest
 * 
 * @subpackage controllers
 *  
 * @module Rest
 * 
 * @class Restcontroller.php
 * 
 * @path application\webservice\rest\controllers\Restcontroller.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class RestController extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create_token method is used to create token for webservices security.
     */
    public function create_token()
    {
        $this->load->model('rest_model');
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $remote_addr = $this->wschecker->getHTTPRealIPAddr();
        $user_agent = $this->wschecker->getHTTPUserAgent();
        $prepare_str = $remote_addr . "@@" . $user_agent . "@@" . time();
        $ws_token = hash("SHA256", $this->wschecker->encrypt($prepare_str));

        $inser_arr['vWSToken'] = $ws_token;
        $inser_arr['vIPAddress'] = $remote_addr;
        $inser_arr['vUserAgent'] = $user_agent;
        $inser_arr['dLastAccess'] = date("Y-m-d H:i:s");
        $res = $this->rest_model->insertToken($inser_arr);
        if ($res) {
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Token generated successfully..!";
            $data_arr['ws_token'] = $ws_token;
        } else {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = "Token generation failed..!";
            $data_arr = array();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data_arr;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * create_token method is used to create token for webservices security.
     */
    public function inactive_token()
    {
        $this->load->model('rest_model');
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $remote_addr = $this->wschecker->getHTTPRealIPAddr();
        $user_agent = $this->wschecker->getHTTPUserAgent();
        $ws_token = trim($this->input->get_post("ws_token"));

        if (empty($ws_token)) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = "Please send token to inactivate.!";
        } else {
            $update_arr['eStatus'] = "Inactive";
            $extra_cond = "vWSToken = '" . $ws_token . "'";
            $res = $this->rest_model->updateToken($update_arr, $extra_cond);
            if ($res) {
                $settings_arr['success'] = 1;
                $settings_arr['message'] = "Token inactivated successfully..!";
            } else {
                $settings_arr['success'] = 0;
                $settings_arr['message'] = "Token inactivation failed..!";
            }
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = array();
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * execute_notify_schedule method is used to get push notifications full data.
     */
    public function execute_notify_schedule()
    {
        $this->load->model('rest_model');
        $this->load->library('wsresponse');
        $limit = $this->config->item('WS_PUSH_LIMIT');
        try {
            $data = array();

            $extra_cond = $this->db->protect("mpn.eStatus") . " = " . $this->db->escape("Pending");
            $data_arr = $this->rest_model->getPushNotify($extra_cond, "", "", "", $limit);

            if (!is_array($data_arr) || count($data_arr) == 0) {
                throw new Exception("There are no notification found to execute.");
            }

            foreach ($data_arr as $key => $val) {
                $update_arr = array();
                $push_time = $val['dtPushTime'];
                if (!empty($push_time) && $push_time != "0000-00-00 00:00:00") {
                    if ($push_time > date("Y-m-d H:i:s")) {
                        continue;
                    }
                }
                $expire_time = $val['dtExpireTime'];
                if (!empty($expire_time) && $expire_time != "0000-00-00 00:00:00") {
                    if ($expire_time < date("Y-m-d H:i:s")) {
                        $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
                        $update_arr['eStatus'] = 'Expired';
                        $res = $this->rest_model->updatePushNotify($update_arr, $val['iPushNotifyId']);
                        continue;
                    }
                }

                $notify_arr = array();
                $vars_arr = json_decode($val['tVarsJSON'], true);
                if (is_array($vars_arr) && count($vars_arr) > 0) {
                    foreach ($vars_arr as $vk => $vv) {
                        if ($vv['key'] != "" && $vv['send'] == "Yes") {
                            $notify_arr['others'][$vv['key']] = $vv['value'];
                        }
                    }
                }
                $notify_arr['mode'] = $this->config->item('PUSH_NOTIFY_SENDING_MODE');
                $notify_arr['message'] = $val['tMessage'];
                $notify_arr['title'] = $val['vTitle'];
                $notify_arr['badge'] = intval($val['vBadge']);
                $notify_arr['sound'] = $val['vSound'];
                $notify_arr['code'] = $val['eNotifyCode'];
                $notify_arr['id'] = $val['vUniqueId'];

                $success = $this->general->pushTestNotification($val['vDeviceId'], $notify_arr);

                $update_arr['tSendJSON'] = $this->general->getPushNotifyOutput("body");
                $update_arr['dtExeDateTime'] = date("Y-m-d H:i:s");
                if ($success) {
                    $update_arr['eStatus'] = 'Executed';
                } else {
                    $update_arr['eStatus'] = 'Failed';
                }

                $res = $this->rest_model->updatePushNotify($update_arr, $val['iPushNotifyId']);

                $send_arr = $notify_arr;
                $send_arr['device_id'] = $val['vDeviceId'];
                $data[] = $send_arr;
            }

            $settings_arr['success'] = 1;
            $settings_arr['count'] = count($data_arr);
            $settings_arr['message'] = "Push notification(s) send successfully";
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * get_push_notification method is used to get push notifications full data.
     */
    public function get_push_notification()
    {
        $this->load->model('rest_model');
        $this->load->library('wsresponse');
        $this->load->library('wschecker');
        $get_arr = is_array($this->input->get(null)) ? $this->input->get(null) : array();
        $post_arr = is_array($this->input->post(null)) ? $this->input->post(null) : array();
        $post_params = array_merge($get_arr, $post_arr);

        try {
            if ($this->config->item('WS_RESPONSE_ENCRYPTION') == "Y") {
                $post_params = $this->wschecker->decrypt_params($post_params);
            }
            $verify_res = $this->wschecker->verify_webservice($post_params);
            if ($verify_res['success'] != "1") {
                $this->wschecker->show_error_code($verify_res);
            }

            $unique_id = $post_params["unique_id"];
            $data = $temp = array();
            if (empty($unique_id)) {
                throw new Exception("Please send unique id for this notification");
            }
            $extra_cond = $this->db->protect("mpn.vUniqueId") . " = " . $this->db->escape($unique_id);
            $data_arr = $this->rest_model->getPushNotify($extra_cond);

            if (!is_array($data_arr) || count($data_arr) == 0) {
                throw new Exception("Data not found for this unique id");
            }
            $variables = json_decode($data_arr[0]['tVarsJSON'], true);
            if (is_array($variables) && count($variables) > 0) {
                foreach ($variables as $vk => $vv) {
                    if ($vv['key'] != "") {
                        $temp[$vv['key']] = $vv['value'];
                    }
                }
            }
            $temp['code'] = $data_arr[0]['eNotifyCode'];
            $temp['title'] = $data_arr[0]['vTitle'];
            $temp['body'] = $data_arr[0]['tMessage'];

            $data[0] = $temp;
            $settings_arr['success'] = 1;
            $settings_arr['message'] = "Push notification data found";
        } catch (Exception $e) {
            $settings_arr['success'] = 0;
            $settings_arr['message'] = $e->getMessage();
        }
        $responce_arr['settings'] = $settings_arr;
        $responce_arr['data'] = $data;
        $this->wsresponse->sendWSResponse($responce_arr);
    }

    /**
     * image_resize method is used to resize image for different sizes.
     */
    public function image_resize()
    {
        $url = $this->input->get('pic');
        $width = $this->input->get('width');
        $height = $this->input->get('height');
        $bgcolor = $this->input->get('color');
        $type = 'fit'; // fit,fill
//        $bgcolor = (trim($bgcolor) == "") ? "FFFFFF" : $bgcolor;
//        $props = array(
//            'picture' => $url,
//            'resize_width' => $width,
//            'resize_height' => $height,
//            'bg_color' => $bgcolor
//        );
//
//        $this->load->library('Image_resize', $props);
//        $this->skip_template_view();

        $dest_folder = APPPATH . 'cache' . DS . 'temp' . DS;
        $this->general->createFolder($dest_folder);

        $pic = trim($url);
        $pic = base64_decode($pic);
        $pic = str_replace(" ", "%20", $pic);
        $url = $pic;
        $url = str_replace(" ", "%20", $url);
        $props = array(
            'picture' => $url,
            'resize_width' => $width,
            'resize_height' => $height,
            'bg_color' => $bgcolor
        );
        $md5_url = md5($url . serialize($props));
        $tmp_path = $tmp_file = $dest_folder . $md5_url;

        if (!is_file($tmp_path)) {

            $image_data = file_get_contents($url);
            if ($image_data == FALSE) {
                $headers = parse_response_header($http_response_header);
                if ($headers['reponse_code'] != 200) {
                    $this->output->set_status_header($headers['reponse_code']);
                    exit;
                }
            }
            $handle = fopen($tmp_path, 'w+');
            fwrite($handle, $image_data);
            fclose($handle);

            $img_info = getimagesize($tmp_path);
            $img_ext = end(explode("/", $img_info['mime']));
            if ($img_ext == 'jpeg' || $img_ext == "pjpeg") {
                $img_ext = 'jpg';
            }

            $this->load->library('image_lib');

            $image_process_tool = $this->config->item('imageprocesstool');
            $config['image_library'] = $image_process_tool;
            if ($image_process_tool == "imagemagick") {
                $config['library_path'] = $this->config->item('imagemagickinstalldir');
            }
            if ($img_ext == "jpg") {
                $png_convert = $this->image_lib->convet_jpg_png($tmp_path, $tmp_path . ".png", $config['library_path']);
                if ($png_convert) {
                    unlink($tmp_path);
                    rename($tmp_path . ".png", $tmp_path);
                }
            }

            if ($type == 'fill') {
                $img_info = getimagesize($tmp_path);
                $org_width = $img_info[0];
                $org_height = $img_info[1];

                $width_ratio = $width / $org_width;
                $height_ratio = $height / $org_height;
                if ($width_ratio > $height_ratio) {
                    $resize_width = $org_width * $width_ratio;
                    $resize_height = $org_height * $width_ratio;
                } else {
                    $resize_width = $org_width * $height_ratio;
                    $resize_height = $org_height * $height_ratio;
                }

                $crop_width = $width;
                $crop_height = $height;

                $width = $resize_width;
                $height = $resize_height;
            }

            $config['source_image'] = $tmp_path;
            $config['width'] = $width;
            $config['height'] = $height;
            $config['gravity'] = 'center';
            $config['bgcolor'] = (trim($bgcolor) != "") ? trim($bgcolor) : $this->config->item('imageresizebgcolor');
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            if ($type == 'fill') {
                $config['source_image'] = $tmp_path;
                $config['width'] = $crop_width;
                $config['height'] = $crop_height;
                $config['gravity'] = 'center';
                $config['maintain_ratio'] = FALSE;

                $this->image_lib->initialize($config);
                $this->image_lib->crop();
            }
        }
        $this->image_display($tmp_file);
    }

    protected function image_display($image_path = '')
    {
        //ob_end_clean();
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        ob_start();
        $image_path = str_replace(" ", "%20", $image_path);
        $img_info = getimagesize($image_path);
        if ($img_info[2] == 1) {
            header('Content-Type: image/gif');
        } elseif ($img_info[2] == 2) {
            header('Content-Type: image/jpg');
        } elseif ($img_info[2] == 3) {
            header('Content-Type: image/png');
        } else {
            header('Content-Type: application/octet-stream');
        }
        $timestamp = filemtime($image_path);
        $gmt_mtime = gmdate('r', $timestamp);
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $image_path)) {
                header('HTTP/1.1 304 Not Modified');
                exit;
            }
        }
        header_remove('Pragma');
        header("Access-Control-Allow-Origin: *");
        header('ETag: "' . md5($timestamp . $image_path) . '"');
        header('Last-Modified: ' . $gmt_mtime);
        header('Cache-Control: max-age=2592000, public');
        header("Content-Length: " . filesize($image_path));
        echo readfile($image_path);
        exit;
    }
}
