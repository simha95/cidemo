<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Emailer Model
 *
 * @category models
 *            
 * @package tools
 *
 * @module Emailer
 * 
 * @class Emailer.php
 * 
 * @path application\models\general\Emailer.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Emailer extends CI_Model
{

    public $table_name;
    public $primary_key;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->table_name = "mod_system_email";
        $this->primary_key = "iEmailTemplateId";
    }

    public function send_mail($data = array(), $code = "MEMBER_REGISTER")
    {
        $params = array();
        switch ($code) {
            case "USER_REGISTER":
            case "FORGOT_PASSWORD":
            case "FRONT_FORGOT_PASSWORD":
                $params['vName'] = $data['vName'];
                $params['vEmail'] = $data['vEmail'];
                $params['vUserName'] = $data['vUserName'];
                $params['vPassword'] = $data['vPassword'];
                break;
            default:
                $params = $data;
                break;
        }
        $success = $this->general->sendMail($params, $code);
        if (!$success) {
            $message = $this->general->getNotifyErrorOutput();
        }
        $return['success'] = $success;
        $return['message'] = $message;

        return $return;
    }
}
