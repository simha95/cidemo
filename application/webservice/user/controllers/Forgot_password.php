<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Forgot Password Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Forgot Password
 *
 * @class Forgot_password.php
 *
 * @path application\webservice\user\controllers\Forgot_password.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 03.10.2017
 */

class Forgot_password extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "get_customer_by_email",
            "change_customer_password",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_forgot_password method is used to validate api input params.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_forgot_password($request_arr = array())
    {
        $valid_arr = array(
            "email" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "email_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "forgot_password");

        return $valid_res;
    }

    /**
     * start_forgot_password method is used to initiate api execution flow.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_forgot_password($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_forgot_password($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                {
                    return $validation_res;
                }
                else
                {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();

            $input_params = $this->get_customer_by_email($input_params);

            $condition_res = $this->is_customer_exists($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->assign_random_password($input_params);

                $condition_res = $this->is_password_generated($input_params);
                if ($condition_res["success"])
                {

                    $input_params = $this->change_customer_password($input_params);

                    $input_params = $this->forgot_password_email($input_params);

                    $output_response = $this->finish_customer_pwd_success($input_params);
                    return $output_response;
                }

                else
                {

                    $output_response = $this->finish_customer_pwd_generation($input_params);
                    return $output_response;
                }
            }

            else
            {

                $output_response = $this->finish_customer_pwd_failure($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    /**
     * get_customer_by_email method is used to process query block.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_customer_by_email($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email = isset($input_params["email"]) ? $input_params["email"] : "";
            $this->block_result = $this->customer_model->get_customer_by_email($email);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_customer_by_email"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_customer_exists method is used to process conditions.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_customer_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_customer_by_email"]) ? 0 : 1);
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }

    /**
     * assign_random_password method is used to process simple variables.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function assign_random_password($input_params = array())
    {
        if (method_exists($this->general, "generateRandomPassword"))
        {
            $input_params["random_password"] = $this->general->generateRandomPassword($input_params);
        }
        $_temp_single_arr["random_password"] = $input_params["random_password"];
        return $input_params;
    }

    /**
     * is_password_generated method is used to process conditions.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_password_generated($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["random_password"];

            $cc_fr_0 = (!is_null($cc_lo_0) && !empty($cc_lo_0) && trim($cc_lo_0) != "") ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }

    /**
     * change_customer_password method is used to process query block.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function change_customer_password($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["mc_customer_id"]))
            {
                $where_arr["mc_customer_id"] = $input_params["mc_customer_id"];
            }
            if (isset($input_params["random_password"]))
            {
                $params_arr["random_password"] = $input_params["random_password"];
            }
            $this->block_result = $this->customer_model->change_customer_password($params_arr, $where_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["change_customer_password"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * forgot_password_email method is used to process email notification.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function forgot_password_email($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email_arr["vEmail"] = $input_params["mc_email"];

            $email_arr["vName"] = $input_params["mc_last_name"];
            $email_arr["vUserEmail"] = $input_params["mc_email"];
            $email_arr["vUserName"] = $input_params["mc_user_name"];
            $email_arr["vPassword"] = $input_params["random_password"];

            $success = $this->general->sendMail($email_arr, "FRONT_FORGOT_PASSWORD");

            $log_arr = array();
            $log_arr['eEntityType'] = 'General';
            $log_arr['vReceiver'] = is_array($email_arr["vEmail"]) ? implode(",", $email_arr["vEmail"]) : $email_arr["vEmail"];
            $log_arr['eNotificationType'] = "EmailNotify";
            $log_arr['vSubject'] = $this->general->getEmailOutput("subject");
            $log_arr['tContent'] = $this->general->getEmailOutput("content");
            if (!$success)
            {
                $log_arr['tError'] = $this->general->getNotifyErrorOutput();
            }
            $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
            $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
            $this->general->insertExecutedNotify($log_arr);
            if (!$success)
            {
                throw new Exception("Failure in sending mail.");
            }
            $success = 1;
            $message = "Email notification send successfully.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        $input_params["forgot_password_email"] = $this->block_result["success"];

        return $input_params;
    }

    /**
     * finish_customer_pwd_success method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_pwd_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_customer_pwd_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "forgot_password";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_pwd_generation method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_pwd_generation($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_pwd_generation",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "forgot_password";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_customer_pwd_failure method is used to process finish flow.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_customer_pwd_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_customer_pwd_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "forgot_password";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
