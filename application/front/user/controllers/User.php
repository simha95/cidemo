<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Controller
 *
 * @category front
 *            
 * @package user
 * 
 * @subpackage controllers
 * 
 * @module User
 * 
 * @class User.php
 * 
 * @path application\front\user\controllers\User.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class User extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * index method is used to define home page content.
     */
    public function index()
    {
        $view_file = "welcome_message";
        $this->loadView($view_file);
    }

    /**
     * dashboard method is used to define dashboard data after user logged in.
     */
    public function dashboard()
    {
        
    }

    /**
     * login method is used to display login page.
     */
    public function login()
    {
        if ($this->session->userdata('iUserId')) {
            redirect($this->config->item("site_url") . 'dashboard.html');
        }
        $view_file = "login";
        $this->loadView($view_file);
    }

    /**
     * login_action method is used to process login page for authentification.
     */
    public function login_action()
    {
        $user = $this->input->get_post('User');

        $username = $user['vUserName'];
        $password = $user['vPassword'];

        $cookie_data = $this->cookie->read('remember_me');

        if (is_array($cookie_data) && $cookie_data['username'] != '') {
            $username = $cookie_data['username'];
            $password = $cookie_data['password'];
        }

        $stay_signed = $this->input->get_post('remember_me');
        $ajaxcall = $this->input->get_post('ajaxcall');

        try {
            $record = $this->user_model->authenticate($username, $password);
            if (!is_array($record) || count($record) == 0) {
                throw new Exception("You have entered wrong user name or password. Please try again.");
            }
            if ($record[0]['eStatus'] != 'Active') {
                throw new Exception('Your login temporarily inactivated. Please contact administrator.');
            }
            $this->session->set_userdata("iUserId", $record[0]["iCustomerId"]);
            $this->session->set_userdata("vFirstName", $record[0]["vFirstName"]);
            $this->session->set_userdata("vLastName", $record[0]["vLastName"]);
            $this->session->set_userdata("vEmail", $record[0]["vEmail"]);
            $this->session->set_userdata("vUserName", $record[0]["vUserName"]);
            $this->session->set_userdata("eStatus", $record[0]["eStatus"]);

            $session_log['iUserId'] = $record[0]['iCustomerId'];
            $session_log['eUserType'] = 'Member';
            $session_log['vIP'] = $this->input->ip_address();
            $session_log['dLoginDate'] = date('Y-m-d H:i:s', now());

            $this->load->model('tools/loghistory');
            $log_id = $this->loghistory->insert($session_log);
            $this->session->set_userdata("iLogId", $log_id);

            $cookie_prefix = $this->config->item("sess_cookie_name");
            if (strtolower($stay_signed) == 'yes') {
                $cookie_data = array(
                    $cookie_prefix . '_username' => $username,
                    $cookie_prefix . '_password' => $password
                );
                $this->cookie->write('remember_me', $cookie_data);
            } else {
                $this->cookie->delete('remember_me');
            }
            
            $var_msg = "Welcome " . $this->session->userdata("vFirstName") . " " . $this->session->userdata("vLastName") . ", you have successfully logged in.";
            $this->session->set_flashdata('success', $var_msg);
            $this->smarty->assign('alldata', $this->session->all_userdata());
            redirect($this->config->item("site_url") . 'dashboard.html');
        } catch (Exception $e) {
            $var_msg = $e->getMessage();
            $this->session->set_flashdata('failure', $var_msg);
            redirect($this->config->item("site_url") . 'login.html');
        }
    }

    /**
     * logout method is used to log out the current login user.
     */
    public function logout()
    {
        $this->load->model('tools/loghistory');
        $log_id = $this->session->userdata('iLogId');
        $this->loghistory->updateLogoutUser($log_id);
        $sess_cookie_name = $this->config->item("sess_cookie_name");
        $cookiedata = array(
            $sess_cookie_name . 'username' => '',
            $sess_cookie_name . 'password' => ''
        );

        $this->cookie->write('userarray', $cookiedata);
        $this->session->sess_destroy();

        redirect($this->config->item("site_url") . 'index.html');
    }

    /**
     * register method is used to display register page.
     */
    public function register()
    {
        if ($this->session->userdata('iUserId')) {
            redirect($this->config->item("site_url") . 'dashboard.html');
        }
        $data['heading'] = "Register";
        $data['type'] = "register";
        $data['user'] = array('firstname' => '', 'lastname' => '', 'email' => '');
        $this->loadView('register', $data);
    }

    /**
     * register_action method is used to process register page for adding customer record.
     */
    public function register_action()
    {
        $post_arr = $this->input->get_post('User');

        $user_arr = array();
        $user_arr['vFirstName'] = $post_arr['vFirstName'];
        $user_arr['vLastName'] = $post_arr['vLastName'];
        $user_arr['vEmail'] = $post_arr['vEmail'];
        $user_arr['vUserName'] = $post_arr['vUserName'];
        $user_arr['vPassword'] = $post_arr['vPassword'];
        $user_arr['dtRegisteredDate'] = date('Y-m-d H:i:s', now());

        $user_id = $this->user_model->insert($user_arr);

        if (!$user_id) {
            $this->session->set_flashdata('failure', "Error occured during registering your profile.");
        } else {
            $this->session->set_flashdata('success', "You have successfully registered.");
        }

        redirect($this->config->item("site_url") . 'index.html');
    }

    /**
     * check_user_email method is used to check wether username or email already exist in data base.
     */
    public function check_user_email()
    {
        $user_arr = $this->input->get_post('User');

        if (isset($user_arr["vEmail"])) {
            $status = $this->user_model->checkUserExists('vEmail', $user_arr);
        }
        if (isset($user_arr["vUserName"])) {
            $status = $this->user_model->checkUserExists('vUserName', $user_arr);
        }
        if (!$status) {
            echo "false";
        } else {
            echo "true";
        }
        $this->skip_template_view();
    }

    /**
     * forgotpassword method is used to display forgot password page.
     */
    public function forgotpassword()
    {
        $view_file = "forgotpassword";
        $this->loadView($view_file);
    }

    /**
     * forgotpassword_action method is used to send forgot password action.
     */
    public function forgotpassword_action()
    {
        $user_arr = $this->input->get_post('User');
        $user_name = $user_arr['vUserName'];
        $where_cond = "(" . $this->db->protect("vUserName") . " = " . $this->db->escape($user_name) . " OR " . $this->db->protect("vEmail") . " = " . $this->db->escape($user_name) . ")";
        $user_details = $this->user_model->getData($where_cond);
        $this->load->model('tools/emailer');
        if (is_array($user_details) && count($user_details) > 0) {
            $response = $this->emailer->send_mail($user_details[0], 'FRONT_FORGOT_PASSWORD');
            if ($response['success']) {
                $this->session->set_flashdata('success', "Forgot password email sent sucessfully. Please check your email.");
            } else {
                $this->session->set_flashdata('failure', "Error in sending mail. Please contact adminstrator.");
            }
        } else {
            $this->session->set_flashdata('failure', "We are unable to find your username or email. Please try again.");
        }
        redirect($this->config->item("site_url") . 'forgot-password.html');
    }

    /**
     * profile method is used to display and  update customer page.
     */
    public function profile()
    {
        $user_id = $this->session->userdata('iUserId');
        if (!$user_id) {
            $this->session->set_flashdata('failure', "Please log in first to update profile.");
            redirect($this->config->item("site_url") . 'login.html');
        } else {
            if ($this->input->post()) {
                $post_arr = $this->input->get_post('User');

                $user_arr = array();
                $user_arr['vFirstName'] = $post_arr['vFirstName'];
                $user_arr['vLastName'] = $post_arr['vLastName'];
                $user_arr['vPassword'] = $post_arr['vPassword'];
                $res = $this->user_model->update($user_arr, $this->input->post('userId'));

                if (!$res) {
                    $this->session->set_flashdata('failure', "Error occured during updating user profile.");
                } else {
                    $this->session->set_flashdata('success', "User profile updated successfully.");
                }
                redirect($this->config->item("site_url") . 'profile.html');
            }
            $where = $this->db->protect("iCustomerId") . " = " . $this->db->escape($user_id);
            $user = $this->user_model->getData($where);
            if (!is_array($user) || count($user) == 0) {
                $this->session->set_flashdata('failure', "User profile not found.");
                redirect($this->config->item("site_url") . 'logout.html');
            }
            $data['user'] = array(
                'id' => $user_id,
                'firstname' => $user[0]['vFirstName'],
                'lastname' => $user[0]['vLastName'],
                'email' => $user[0]['vEmail'],
                'username' => $user[0]['vUserName'],
                'password' => $user[0]['vPassword']
            );
            $data['heading'] = "User Profile";
            $data['type'] = "profile";
        }
        $this->loadView("register", $data);
    }
}
