<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Login Controller
 *
 * @category admin
 *            
 * @package user
 * 
 * @subpackage controllers
 * 
 * @module Login
 * 
 * @class Login.php
 * 
 * @path application\admin\user\controllers\Login.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Login extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('filter');
        $this->load->library('listing');
        $this->load->model('admin_model');
        $this->mod_url_cod = array(
            "user_login_index",
            "user_login_entry",
            "user_login_entry_a",
            "user_login_logout",
            "user_sess_expire",
            "user_forgot_password_action",
            "user_changepassword",
            "user_changepassword_action",
            "user_resetpassword",
            "user_resetpassword_action",
            "user_auto_logoff",
            "user_notify_events",
            "dashboard_index"
        );
        $this->mod_enc_url = $this->general->getCustomEncryptURL($this->mod_url_cod, true);
    }

    /**
     * index method is used to intialize index page.
     */
    public function index()
    {
        
    }

    /**
     * entry method is used to display admin login page.
     */
    public function entry()
    {
        if ($this->session->userdata("iAdminId") > 0) {
            redirect($this->config->item("admin_url") . "#" . $this->mod_enc_url['dashboard_index']);
        } else {
            $is_patternlock = "no";
            $LOGIN_PASSWORD_TYPE = $this->config->item("LOGIN_PASSWORD_TYPE");
            $setting_pattern_lock = (strtolower($LOGIN_PASSWORD_TYPE) == "y") ? true : false;
            if ($setting_pattern_lock) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $admin_pattern_lock = strtolower($pwd_settings['pattern']);
                $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
            }

            $render_arr = array();
            /* cookie data for saving login details */
            $remember_me_data = $this->cookie->read($this->general->getMD5EncryptString("RememberMe"));
            $remember_me = $passwd = $login_name = "";
            $remember_me_arr = json_decode($remember_me_data[0], true);
            if (is_array($remember_me_arr) && count($remember_me_arr) > 0) {
                if ($remember_me_arr['remember'] == "Yes") {
                    $remember_me = $remember_me_arr["remember"];
                    $login_data = $remember_me_arr["data"];
                    $login_name = $login_data["_user"];
                    $passwd = $login_data["_pass"];
                    $login_name = $this->general->decryptDataMethod($login_name);
                    $passwd = $this->general->decryptDataMethod($passwd);
                }
            }
            $enc_url['forgot_pwd_url'] = $this->config->item("admin_url") . $this->mod_enc_url['user_forgot_password_action'];
            $enc_url['entry_action_url'] = $this->config->item("admin_url") . $this->mod_enc_url['user_login_entry_a'];

            $render_arr["is_patternlock"] = $is_patternlock;
            $render_arr["login_name"] = $login_name;
            $render_arr["passwd"] = $passwd;
            $render_arr["remember_me"] = $remember_me;
            $render_arr["enc_url"] = $enc_url;

            $this->smarty->assign($render_arr);
            $file_name = "admin_login_template";
            $this->set_template($file_name);
        }
    }

    /**
     * entry_a method is used to check admin login user.
     */
    public function entry_a()
    {

        $mode = $this->input->get_post('mode', TRUE);
        try {
            $this->load->model('user/loghistory_model');
            $passwd = $this->input->get_post('passwd', TRUE);
            $login_name = $this->input->get_post('login_name', TRUE);
            $handle_url = $this->input->get_post('handle_url', TRUE);
            $handle_url = ltrim($handle_url, '#');
            $pwd_settings = $this->admin_model->getPasswordSettings();
            $is_patternlock = strtolower($pwd_settings['pattern']);
            $is_encryptdata = strtolower($pwd_settings['encrypt']);
            $encrypt_method = strtolower($pwd_settings['enctype']);
            $LOGIN_PASSWORD_TYPE = $this->config->item("LOGIN_PASSWORD_TYPE");
            $setting_pattern_lock = (strtolower($LOGIN_PASSWORD_TYPE) == "y") ? true : false;

            if ($is_encryptdata == "yes") {
                $login_pass = $this->general->encryptDataMethod($passwd, $encrypt_method);
            } else {
                $login_pass = $passwd;
            }
            $result = $this->admin_model->getAdminUser($login_name, $login_pass);

            if (!is_array($result) || count($result) == 0) {
                if ($setting_pattern_lock && $is_patternlock == "yes") {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PATTERN_PLEASE_TRY_AGAIN_C46_C46_C33'));
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_HAVE_ENTERED_WRONG_LOGIN_NAME_OR_PASSWORD_PLEASE_TRY_AGAIN_C46_C46_C33'));
                }
            }

            if ($result[0]["eStatus"] != 'Active') {
                throw new Exception($this->general->processMessageLabel('ACTION_YOUR_ADMIN_LOGIN_TEMPORARILY_INACTIVATED_PLEASE_CONTACT_ADMINISTRATOR_C46_C46_C33'));
            }
            if ($result[0]["vGroupStaus"] != 'Active') {
                throw new Exception($this->general->processMessageLabel('ACTION_YOUR_GROUP_LOGIN_TEMPORARILY_INACTIVATED_PLEASE_CONTACT_ADMINISTRATOR_C46_C46_C33'));
            }
            $this->general->checkUserAccountStatus();

            $remember_me = $this->input->get_post('remember_me', TRUE);
            $cookie_str = $this->general->getMD5EncryptString("RememberMe");
            if (isset($remember_me) && $remember_me == "Yes") {
                $rem_login_arr = array();
                $rem_login_arr['_user'] = $this->general->encryptDataMethod($login_name);
                $rem_login_arr['_pass'] = $this->general->encryptDataMethod($passwd);

                $rem_frm_data_arr["remember"] = "Yes";
                $rem_frm_data_arr["data"] = $rem_login_arr;
                $remfrm_data_json = json_encode($rem_frm_data_arr);
                $this->cookie->write($cookie_str, array($remfrm_data_json));
            } else {
                $this->cookie->delete($cookie_str);
            }

            $user_array = array();
            if (is_array($result[0]) && count($result[0]) > 0) {
                foreach ($result[0] as $key => $val) {
                    $val = stripslashes(str_replace(array("\r", "\n"), '', $val));
                    $user_array[$key] = $val;
                }
            }
            $logoff_time = (intval($this->config->item('AUTO_LOGOFF_TIME')) > 0) ? $this->config->item('AUTO_LOGOFF_TIME') : 15;
            $user_array['isLoggedIn'] = true;
            $user_array['loggedAt'] = time();
            $user_array['timeOut'] = $logoff_time;
            $this->session->set_userdata($user_array);
            // New code changed start
            $extra_cond = $this->db->protect("iUserId") . " = " . $this->db->escape($result[0]["iAdminId"]);
            $log_result = $this->loghistory_model->getData($extra_cond, "", "mlh_log_id DESC");
            $this->general->logInOutEntry($result[0]["iAdminId"], 'Admin');

            $login_callback = $this->config->item('login_callback');
            if ($login_callback != "" && method_exists($this->general, $login_callback)) {
                $this->general->$login_callback($user_array);
            }

            if (trim($handle_url) != '') {
                $extra_param = '#' . $handle_url;
            } else {
                $extra_param = $this->filter->getLandingpageURL($result[0], $log_result[0]['mlh_current_url']);
            }
            // New code changed end
            $var_msg = "Welcome " . $this->session->userdata("vName") . " , you have successfully logged in.";
            $this->session->set_flashdata('success', $var_msg);
            redirect($this->config->item('admin_url') . $extra_param);
            $this->skip_template_view();
        } catch (Exception $e) {
            $err_msg = $e->getMessage();
            $this->session->set_flashdata('failure', $err_msg);
            redirect($this->config->item("admin_url") . $this->mod_enc_url['user_login_entry'] . "?_=" . time());
            $this->skip_template_view();
        }
    }

    /**
     * logout method is used to log out the current login user.
     */
    public function logout()
    {
        $hash_val = $this->input->get_post('hashVal', TRUE);
        $extra_arr = array();
        $extra_arr['hashVal'] = trim($hash_val != "") ? $hash_val : "";
        $this->general->logInOutEntry($this->session->userdata("iAdminId"), 'Admin', $extra_arr);
        $session_arr = $this->session->all_userdata();
        $session_key = is_array($session_arr) ? array_keys($session_arr) : array();
        $this->session->unset_userdata($session_key);
        $this->session->set_flashdata('success', "You have successfully logged out.");
        $this->session->set_flashdata('failure', "");

        $return_arr['success'] = 1;
        $return_arr['message'] = $err_msg;

        echo json_encode($return_arr);
        $this->skip_template_view();
    }

    /**
     * sess_expire method is used to show session expire page.
     */
    public function sess_expire()
    {
        $render_arr = array(
            "login_entry_url" => $this->config->item("admin_url") . $this->mod_enc_url['user_login_entry']
        );
        $this->smarty->assign($render_arr);
        $file_name = "admin_sess_expire_template";
        $this->set_template($file_name);
        $this->loadView("sess_expire");
    }

    /**
     * forgot_password_action method is used to send forgot password action.
     */
    public function forgot_password_action()
    {
        $username = $this->input->get_post('username', TRUE);

        try {
            $forgot_passwrd_type = $this->config->item('ADMIN_FORGOT_PASSWORD_TYPE');
            if ($username == '') {
                $error_msg = $this->general->processMessageLabel('ACTION_PLEASE_ENTER_LOGIN_NAME_C46_C46_C33');
                throw new Exception($error_msg);
            }
            $username_cond = $this->db->protect("vUserName") . " = " . $this->db->escape($username);
            $email_cond = $this->db->protect("vEmail") . " = " . $this->db->escape($username);
            $extra_cond = "(" . $username_cond . " OR " . $email_cond . ")";
            $db_query = $this->admin_model->getData($extra_cond, "iAdminId, vName, vEmail, vUserName, vPassword");
            if (!is_array($db_query) || count($db_query) == 0) {
                $error_msg = $this->general->processMessageLabel('ACTION_UNABLE_TO_FIND_A_USER_WITH_THIS_LOGIN_NAME_C46_C46_C33');
                throw new Exception($error_msg);
            }
            $numeric = range(1, 9);
            $PoolLength = count($numeric) - 1;
            $results = array();
            for ($i = 0; $i < 6;) {
                $num = $numeric[mt_rand(0, $PoolLength)];
                if (!in_array($num, $results)) {
                    $results[] = $num;
                    $i++;
                }
            }
            $password = implode("", $results);

            if ($forgot_passwrd_type == 'MAIL') {
                $db_query[0]['vPassword'] = $password;
                $update_arr = array();
                $update_arr['vPassword'] = $password;
                $update_query = $this->admin_model->update($update_arr, $db_query[0]['iAdminId']);

                $mail_success = $this->general->sendMail($db_query[0], 'FORGOT_PASSWORD');
                $msg = $this->general->processMessageLabel('ACTION_PLEASE_CHECK_YOUR_MAIL_FOR_LOGIN_NAME_AND_PASSWORD_C46_C46_C33');
            } else {
                $reset_code = $password;
                $time = base64_encode(time());
                $encode_id = base64_encode($db_query[0]['iAdminId']);
                $_rspwd = base64_encode("RESET_PASSWORD");
                $code = base64_encode($password);
                $reset_url = $this->config->item("admin_url") . $this->mod_enc_url['user_resetpassword'] . "?_rspwd=" . $_rspwd . "&_rsid=" . $encode_id . "&_rst=" . $time . "&_rsc=" . $code;
                $db_query[0]['RESET_CODE'] = $reset_code;
                $db_query[0]['RESET_URL'] = $reset_url;
                $mail_success = $this->general->sendMail($db_query[0], 'RESET_PASSWORD');
                $msg = $this->general->processMessageLabel('ACTION_PLEASE_VISIT_THE_LINK_WHICH_HAS_BEEN_SENT_TO_YOUR_MAIL_C46_C46_C33');
            }
            $msg = ($mail_success) ? $msg : $this->general->processMessageLabel('ACTION_FAILURE_IN_SENDING_MAIL_C46_C46_C33');
            if (!$mail_success) {
                throw new Exception($msg);
            }
            $success = "1";
        } catch (Exception $e) {
            $success = "0";
            $msg = $e->getMessage();
        }

        $returnArr['message'] = $msg;
        $returnArr['success'] = $success;

        echo json_encode($returnArr);
        $this->skip_template_view();
    }

    /**
     * changepassword method is used to display form for changing password.
     */
    public function changepassword()
    {
        $is_patternlock = "no";
        $LOGIN_PASSWORD_TYPE = $this->config->item("LOGIN_PASSWORD_TYPE");
        $setting_pattern_lock = (strtolower($LOGIN_PASSWORD_TYPE) == "y") ? true : false;

        if ($setting_pattern_lock) {
            $pwd_settings = $this->admin_model->getPasswordSettings();
            $admin_pattern_lock = strtolower($pwd_settings['pattern']);
            $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
        }
        $changepassword_url = $this->config->item("admin_url") . $this->mod_enc_url['user_changepassword_action'];

        $render_arr = array();
        $render_arr["is_patternlock"] = $is_patternlock;
        $render_arr["changepassword_url"] = $changepassword_url;
        $render_arr["id"] = $this->session->userdata('iAdminId');
        $enc_id = $this->general->getAdminEncodeURL($render_arr['id']);
        $render_arr["enc_id"] = $enc_id;

        $this->smarty->assign($render_arr);
        $this->loadView("change_password");
    }

    /**
     * changepassword_action method is used to save changed password.
     */
    public function changepassword_action()
    {
        $iAdminId = $this->input->get_post('id', TRUE);
        $vOldPassword = $this->input->get_post('vOldPassword', TRUE);
        $vPassword = $this->input->get_post('vPassword', TRUE);
        $patternLock = strtolower($this->input->get_post('patternLock', TRUE));
        $vConfirmPassword = $this->input->get_post('vConfirmPassword', TRUE);
        $db_pwd_field = $this->admin_model->getData($iAdminId, "vPassword");

        try {
            if ($iAdminId != $this->session->userdata('iAdminId')) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $pwd_settings = $this->admin_model->getPasswordSettings();
            $is_encryptdata = strtolower($pwd_settings['encrypt']);
            $encrypt_method = strtolower($pwd_settings['enctype']);
            if ($is_encryptdata == 'yes') {
                $password_res = $this->general->verifyEncryptData($vOldPassword, $db_pwd_field[0]['vPassword'], $encrypt_method);
                if ($vOldPassword == "" || !$password_res) {
                    throw new Exception($this->general->processMessageLabel('ACTION_OLD_PASSWORD_IS_INCORRECT_C46_C46_C33'));
                }
            } else {
                $db_password = $db_pwd_field[0]['vPassword'];
                if ($vOldPassword == "" || $db_password != $vOldPassword) {
                    throw new Exception($this->general->processMessageLabel('ACTION_OLD_PASSWORD_IS_INCORRECT_C46_C46_C33'));
                }
            }

            if ($vPassword == "") {
                throw new Exception($this->general->processMessageLabel('ACTION_PLEASE_ENTER_NEW_PASSWORD_C46_C46_C33'));
            }
            if ($patternLock != "yes") {
                if ($vConfirmPassword == "" || $vConfirmPassword != $vPassword) {
                    throw new Exception($this->general->processMessageLabel('ACTION_NEW_PASSWORD_AND_CONFIRM_PASSWORD_DOES_NOT_MATCH_C46_C46_C33'));
                }
            }
            if ($is_encryptdata == 'yes') {
                $new_password = $this->general->encryptDataMethod($vPassword, $encrypt_method);
            } else {
                $new_password = $vPassword;
            }

            $updateArr = array();
            $updateArr["vPassword"] = $new_password;
            $res = $this->admin_model->update($updateArr, $iAdminId);

            $msg = $this->general->processMessageLabel('ACTION_PASSWORD_CHANGED_SUCCESSFULLY_C46_C46_C33');
            if (!$res) {
                throw new Exception($this->general->processMessageLabel('ACTION_FALIURE_IN_CHANGING_PASSWORD_C46_C46_C33'));
            }

            $success = "1";
        } catch (Exception $e) {
            $success = "0";
            $msg = $e->getMessage();
        }

        $returnArr['message'] = $msg;
        $returnArr['success'] = $success;

        echo json_encode($returnArr);
        $this->skip_template_view();
    }

    /**
     * resetpassword method is used to display form for reset password.
     */
    public function resetpassword()
    {
        $_rspwd = $this->input->get_post("_rspwd", TRUE);

        if ($this->session->userdata("iAdminId") > 0) {
            redirect($this->config->item("admin_url") . "#" . $this->mod_enc_url['dashboard_index']);
        } else {
            $is_patternlock = "no";
            $LOGIN_PASSWORD_TYPE = $this->config->item("LOGIN_PASSWORD_TYPE");
            $setting_pattern_lock = (strtolower($LOGIN_PASSWORD_TYPE) == "y") ? true : false;
            if ($setting_pattern_lock) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $admin_pattern_lock = strtolower($pwd_settings['pattern']);
                $is_patternlock = (strtolower($admin_pattern_lock) == "yes") ? "yes" : "no";
            }
            $resetpassword_url = $this->config->item("admin_url") . $this->mod_enc_url['user_resetpassword_action'];

            $render_arr["id"] = $_REQUEST['_rsid'];
            $render_arr["time"] = $_REQUEST['_rst'];
            $render_arr["code"] = $_REQUEST['_rsc'];
            $render_arr["is_patternlock"] = $is_patternlock;
            $render_arr["resetpassword_url"] = $resetpassword_url;
            $this->smarty->assign($render_arr);

            $file_name = "admin_login_template";
            $this->set_template($file_name);
        }
    }

    /**
     * resetpassword_action method is used to reset password for admin user.
     */
    public function resetpassword_action()
    {
        $iAdminId = $this->input->get_post("userid", TRUE);
        $code = $this->input->get_post("code", TRUE);
        $time = $this->input->get_post("time", TRUE);
        $password = $this->input->get_post("password", TRUE);
        $securitycode = $this->input->get_post("securitycode", TRUE);
        $iAdminId = base64_decode($iAdminId);
        $time = base64_decode($time);
        $code = base64_decode($code);
        try {
            if ($code != $securitycode) {
                throw new Exception($this->general->processMessageLabel('ACTION_SECURITY_CODE_FAILED_C46_C46_C33'));
            }
            $currenttime = time();
            $resettime = $this->config->item("ADMIN_RESET_PASSWORD_TIME") * 60 * 60 * 1000; //check 1sec
            $delay = $currenttime - $time;
            if ($iAdminId > 0 && $delay < $resettime) {
                $pwd_settings = $this->admin_model->getPasswordSettings();
                $is_encryptdata = strtolower($pwd_settings['encrypt']);
                $encrypt_method = strtolower($pwd_settings['enctype']);
                if ($is_encryptdata == 'yes') {
                    $new_password = $this->general->encryptDataMethod($password, $encrypt_method);
                } else {
                    $new_password = $password;
                }
                $updateArr = array();
                $updateArr["vPassword"] = $new_password;
                $res = $this->admin_model->update($updateArr, $iAdminId);

                $msg = $this->general->processMessageLabel('ACTION_PLEASE_LOGIN_WITH_YOUR_NEW_PASSWORD_C46_C46_C33');
                if (!$res) {
                    throw new Exception($this->general->processMessageLabel('ACTION_RESET_PASSWORD_FAILED_C46_C46_C33'));
                }
            } else {
                throw new Exception($this->general->processMessageLabel('ACTION_TIME_EXCEEDED_TO_RESET_THE_PASSWORD_C46_C46_C33'));
            }
            $this->session->set_flashdata('success', $msg);
        } catch (Exception $exp) {
            $msg = $exp->getMessage();
            $this->session->set_flashdata('failure', $msg);
        }
        redirect($this->config->item("admin_url") . $this->mod_enc_url['user_login_entry'] . "?_=" . time());
        exit;
    }

    /**
     * notify_events method is used to check desktop notifications for admin user.
     */
    public function notify_events()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $manual = $this->input->get_post('manual', TRUE);
        $call_interval = $this->config->item('ADMIN_NOTIFY_TIME_INTERVAL');
        $enable_desktop_events = $this->config->item('ADMIN_DESKTOP_NOTIFICATIONS');
        $notify_arr = $notify_ids = array();
        if ($this->session->userdata('iAdminId') > 0 && $this->config->item('ADMIN_DESKTOP_NOTIFICATIONS') == "Y") {
            $this->db->select('*');
            $this->db->from("mod_executed_notifications");
            $this->db->where("iEntityId", $this->session->userdata('iAdminId'));
            $this->db->where("eNotificationType", "DesktopNotify");
            $this->db->where("eEntityType", "Admin");
            $this->db->where("eStatus", "Pending");
            $this->db->limit(7);
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (is_array($result_arr) && count($result_arr) > 0) {
                for ($i = 0; $i < count($result_arr); $i++) {
                    $link = $link_target = $link_class = "";
                    $val = $result_arr[$i];
                    if ($val['vRedirectLink'] != "") {
                        $redirect_link_arr = unserialize(stripslashes($val['vRedirectLink']));
                        $link = $redirect_link_arr['link'];
                        if ($redirect_link_arr['target']) {
                            $link_target = "target='" . $redirect_link_arr['target'] . "'";
                        }
                        if ($redirect_link_arr['class']) {
                            $link_class = $redirect_link_arr['class'];
                        }
                    }
                    $notify_arr[$i]['type'] = "success";
                    $notify_arr[$i]['link'] = $this->general->getCustomHashLink($link);
                    if ($notify_arr[$i]['link'] != "") {
                        $notify_arr[$i]['subject'] = "<a href='" . $notify_arr[$i]['link'] . "' class='" . $link_class . "' " . $link_target . " >" . $val['vSubject'] . "</a>";
                        $notify_arr[$i]['message'] = $val['tContent'];
                        $notify_arr[$i]['subject'] = $val['vSubject'];
                    } else {
                        $notify_arr[$i]['subject'] = $val['vSubject'];
                        $notify_arr[$i]['message'] = $val['tContent'];
                    }
                    $notify_ids[] = $val["iExecutedNotificationId"];
                }
                $this->db->where_in('iExecutedNotificationId', $notify_ids);
                $this->db->update('mod_executed_notifications', array('eStatus' => 'Executed'));
            }
            $send_arr['success'] = 1;
            $send_arr['content'] = $notify_arr;
        } else {
            $send_arr['success'] = 0;
            $send_arr['content'] = array();
        }
        if ($manual == "true") {
            $data_arr = array();
            if ($send_arr['success'] == 1) {
                $data_arr['retry'] = intval($call_interval);
            }
            $data_arr['data'] = $send_arr;
            echo json_encode($data_arr);
        } else {
            if ($send_arr['success'] == 1) {
                echo "retry: " . intval($call_interval) . "\n\n";
            }
            echo "data: " . json_encode($send_arr) . "\n\n";
        }
        flush();
        $this->skip_template_view();
    }

    /**
     * manifest method is used to load manifest file for appcache.
     */
    public function manifest()
    {
        $ci_source_appcache = $this->config->item("admin_appcache_src_path") . $this->config->item('ADMIN_THEME_DISPLAY') . DS . $this->config->item('ADMIN_APPCACHE_FILE');
        $ci_target_appcache = $this->config->item("site_path") . $this->config->item('ADMIN_APPCACHE_FILE');

        if ($this->session->userdata('iAdminId') > 0 && $this->config->item("ADMIN_ASSETS_APPCACHE") == 'Y') {
            if ($this->config->item('cdn_activate') == '1') {
                $cdn_url = $this->config->item('cdn_http_url');
                $images_source_dir = $cdn_url . "images/";
                $fonts_source_dir = $cdn_url . "fonts/";
                $js_compile_cache_dir = $cdn_url . "js/common/main_common.js";
                $css_compile_cache_dir = $cdn_url . "css/common/main_common.css";
            } else {
                $this->parser->parse("admin_include_css", array(), true);
                $css_compile_dir = $this->css->css_common_src("common", 1);
                $this->parser->parse("admin_include_js", array(), true);
                $js_compile_dir = $this->js->js_common_src("common", 1);

                $images_source_dir = "admin/public/images/";
                $fonts_source_dir = "admin/public/styles/fonts/";
                $js_compile_cache_dir = "public/js/compiled/" . $js_compile_dir . "/main_common.js";
                $css_compile_cache_dir = "admin/public/styles/compiled/" . $css_compile_dir . "/main_common.css";
            }

            $contents = file_get_contents($ci_source_appcache);
            $find_arr = array("##IMAGES_COMMON_URL##", "##FONTS_COMMON_URL##", "##JS_COMMON_CACHE_FOLDER##", "##CSS_COMMON_CACHE_FOLDER##");
            $replace_arr = array($images_source_dir, $fonts_source_dir, $js_compile_cache_dir, $css_compile_cache_dir);
            $contents = str_replace($find_arr, $replace_arr, $contents);

            $fp = fopen($ci_target_appcache, 'w');
            fwrite($fp, $contents);
            fclose($fp);

            $manifest_file = 'manifest="' . $this->config->item("site_url") . $this->config->item('ADMIN_APPCACHE_FILE') . '"';
            $this->ci_local->write($this->general->getMD5EncryptString("AppCache"), "Yes", -1);
            $update_ready = 1;
        } else {
            $manifest_file = '';
            $update_ready = 0;
        }
        $appcache_status = $this->general->getAppCacheStatus();
        $logout_ready = 0;
        if (!$this->session->userdata('iAdminId') || !$this->session->userdata("isLoggedIn")) {
            $logout_ready = 1;
        }

        $render_arr = array();
        $render_arr["app_cache_status"] = $appcache_status;
        $render_arr["manifest_file"] = $manifest_file;
        $render_arr["update_ready"] = $update_ready;
        $render_arr["logout_ready"] = $logout_ready;

        $this->smarty->assign($render_arr);

        $file_name = "admin_manifest_template";
        $this->set_template($file_name);
    }

    /**
     * tbcontent method is used to load top & bottom panel information for appcache.
     */
    public function tbcontent()
    {
        $render_arr = array();
        $this->smarty->assign($render_arr);

        $file_name = "admin_tbcontent_template";
        $this->set_template($file_name);
    }
}
