<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Country List Controller
 *
 * @category webservice
 *
 * @package tools
 *
 * @subpackage controllers
 *
 * @module Country List
 *
 * @class Country_list.php
 *
 * @path application\webservice\tools\controllers\Country_list.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 03.10.2017
 */

class Country_list extends Cit_Controller
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
            "get_country_list",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model("tools/country_model");
    }

    /**
     * rules_country_list method is used to validate api input params.
     * @created  | 28.01.2016
     * @modified  | 29.01.2016
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_country_list($request_arr = array())
    {
        $valid_arr = array();
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "country_list");

        return $valid_res;
    }

    /**
     * start_country_list method is used to initiate api execution flow.
     * @created  | 28.01.2016
     * @modified  | 29.01.2016
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_country_list($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_country_list($request_arr);
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

            $input_params = $this->get_country_list($input_params);

            $condition_res = $this->is_country_list_exists($input_params);
            if ($condition_res["success"])
            {

                $output_response = $this->finish_country_list_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->finish_country_list_failure($input_params);
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
     * get_country_list method is used to process query block.
     * @created  | 28.01.2016
     * @modified  | 28.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_country_list($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $this->block_result = $this->country_model->get_country_list();
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
        $input_params["get_country_list"] = $this->block_result["data"];

        return $input_params;
    }

    /**
     * is_country_list_exists method is used to process conditions.
     * @created  | 28.01.2016
     * @modified  | 28.01.2016
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_country_list_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_country_list"]) ? 0 : 1);
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
     * finish_country_list_success method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified  | 28.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_country_list_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "finish_country_list_success",
        );
        $output_fields = array(
            'mc_country_id',
            'mc_country',
            'mc_country_code',
            'mc_country_code_iso3',
            'mc_status',
        );
        $output_keys = array(
            'get_country_list',
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "country_list";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_country_list_failure method is used to process finish flow.
     * @created  | 28.01.2016
     * @modified  | 28.01.2016
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_country_list_failure($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_country_list_failure",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "country_list";
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(0);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
