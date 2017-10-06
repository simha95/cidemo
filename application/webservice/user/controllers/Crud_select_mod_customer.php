<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of crud_select_mod_customer Controller
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module crud_select_mod_customer
 *
 * @class Crud_select_mod_customer.php
 *
 * @path application\webservice\user\controllers\Crud_select_mod_customer.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 04.10.2017
 */

class Crud_select_mod_customer extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $multiple_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->multiple_keys = array(
            "select_query",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("user/customer_model");
    }

    /**
     * rules_crud_select_mod_customer method is used to validate api input params.
     * @created CIT Admin | 04.10.2017
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_crud_select_mod_customer($request_arr = array())
    {
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "crud_select_mod_customer");

        return $valid_res;
    }

    /**
     * start_crud_select_mod_customer method is used to initiate api execution flow.
     * @created CIT Admin | 04.10.2017
     * @modified ---
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_crud_select_mod_customer($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_crud_select_mod_customer($request_arr);
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

            $input_params = $this->select_query($input_params);

            $output_response = $this->select_finish($input_params);
            return $output_response;
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

    /**
     * select_query method is used to process query block.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function select_query($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $page_index = isset($input_params["page_index"]) ? $input_params["page_index"] : 1;
            $this->block_result = $this->customer_model->select_query($page_index, $this->settings_params);
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
        $input_params["select_query"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * select_finish method is used to process finish flow.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function select_finish($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "select_finish",
        );
        $output_fields = array(
            'mc_customer_id',
            'mc_first_name',
            'mc_last_name',
            'mc_email',
            'mc_user_name',
            'mc_password',
            'mc_profile_image',
            'mc_registered_date',
            'mc_status',
        );
        $output_keys = array(
            'select_query',
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "crud_select_mod_customer";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
