<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Authenticate Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Authnticate
 * 
 * @class Authenticate.php
 * 
 * @path application\libraries\Authenticate.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Authenticate
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->checkEntryAuth();
    }

    protected function checkEntryAuth()
    {
        $admin_allow_arr = array(
            'user' => array(
                'login' => array('entry', 'entry_a', 'logout', 'sess_expire', 'resetpassword', 'resetpassword_action', 'forgot_password_action')
            )
        );
        $front_allow_arr = array(
            'user' => array(
                'user' => array('index', 'login', 'login_action', 'check_user_email', 'register', 'register_action', 'forgotpassword', 'forgotpassword_action')
            ),
            "content" => array(
                'content' => array('index', 'staticpage', 'error', 'captcha')
            )
        );
        $current_method = $this->CI->router->method;
        $current_module = $this->CI->router->fetch_module();
        $current_class = $this->CI->router->class;
        if ($this->CI->config->item('is_admin') == 1) {
            $temp_auth_arr = $admin_allow_arr[$current_module][$current_class];
            if (!is_array($temp_auth_arr) || !in_array($current_method, $temp_auth_arr)) {
                if (!$this->checkValidType('Admin')) {
                    $this->CI->load->library("cit_general", array(), "general");
                    if ($this->CI->input->is_ajax_request()) {
                        header("Cit-auth-requires: 1");
                        echo "<script>callAdminSessionExpired();</script>";
                    } else {
                        if ($this->CI->input->get_post('iframe', TRUE) == 'true') {
                            if ($auth_callback != "" && method_exists($this->CI->general, $auth_callback)) {
                                $this->CI->general->$auth_callback('admin', 'iframe');
                            }
                            echo "<script>parent.callAdminSessionExpired();parent.$.fancybox.close();</script>";
                        } else {
                            redirect($this->CI->general->getAdminEncodeURL('user/login/entry', 1) . "?_=" . time());
                        }
                    }
                    exit;
                }
            }
        } elseif ($this->CI->config->item('is_webservice') == 1 || $this->CI->config->item('is_notification') == 1) {
            return;
        } elseif ($this->CI->config->item('is_citparseapi') == 1) {
            $this->CI->config->load('cit_parse', TRUE);
            $this->CI->load->library('parse_lib');
            $this->CI->parse_lib->setParseAuthConfig();
            $this->CI->parse_lib->setParseResHeaders();
            $this->CI->parse_lib->setParseReqHeaders();
        } else {
            /* Need to un-comment below "return" for web-front */
            //return;
            $temp_auth_arr = $front_allow_arr[$current_module][$current_class];
            if (!is_array($temp_auth_arr) || !in_array($current_method, $temp_auth_arr)) {
                if (!$this->checkValidType('Member')) {
                    $redirect = true;
                    $cookie_data = $this->CI->cookie->read('remember_me');
                    $cookie_prefix = $this->CI->config->item("sess_cookie_name");
                    $username = $cookie_list[$cookie_prefix . '_username'];
                    $password = $cookie_list[$cookie_prefix . '_password'];
                    if (is_array($cookie_data) && trim($username) != '' && trim($password) != '') {
                        $response = $this->authValidUser($username, $password);
                        if ($response['success']) {
                            $cookie_data = array(
                                $cookie_prefix . '_username' => $username,
                                $cookie_prefix . '_password' => $password
                            );
                            $this->CI->cookie->write('remember_me', $cookie_data);
                            $redirect = false;
                        }
                    }
                    if ($redirect) {
                        redirect($this->CI->config->item('site_url') . "login.html");
                        exit;
                    }
                }
            }
        }
    }

    protected function checkValidType($type = '')
    {
        $flag = FALSE;
        if ($type == 'Admin') {
            if (is_object($this->CI->session) && $this->CI->session->userdata('iAdminId') > 0) {
                $flag = TRUE;
            }
        } elseif ($type == 'Member') {
            if (is_object($this->CI->session) && $this->CI->session->userdata('iUserId') > 0) {
                $flag = TRUE;
            }
        }
        return $flag;
    }

    protected function authValidUser($username = '', $password = '')
    {
        try {
            $this->CI->load->model('user/user_model');
            $record = $this->CI->user_model->authenticate($username, $password);
            if (!is_array($record) || count($record) == 0) {
                throw new Exception("You have entered wrong user name or password. Please try again.");
            }
            if ($record[0]['eStatus'] != 'Active') {
                throw new Exception('Your login temporarily inactivated. Please contact administrator.');
            }

            $this->CI->session->set_userdata("iUserId", $record[0]["iCustomerId"]);
            $this->CI->session->set_userdata("vFirstName", $record[0]["vFirstName"]);
            $this->CI->session->set_userdata("vLastName", $record[0]["vLastName"]);
            $this->CI->session->set_userdata("vEmail", $record[0]["vEmail"]);
            $this->CI->session->set_userdata("vUserName", $record[0]["vUserName"]);
            $this->CI->session->set_userdata("eStatus", $record[0]["eStatus"]);

            $session_log['iUserId'] = $record[0]['iCustomerId'];
            $session_log['eUserType'] = 'Member';
            $session_log['vIP'] = $this->CI->input->ip_address();
            $session_log['dLoginDate'] = date('Y-m-d H:i:s', now());

            $this->CI->load->model('tools/loghistory');
            $iLogId = $this->CI->loghistory->insert($session_log);
            $this->CI->session->set_userdata("iLogId", $iLogId);

            $success = 1;
            $message = "Login information found.";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        $return['success'] = $success;
        $return['message'] = $message;
        return $return;
    }
}

/* End of file Authenticate.php */
/* Location: ./application/libraries/Authenticate.php */    