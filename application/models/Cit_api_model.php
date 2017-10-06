<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of CIT API Model
 *
 * @category models
 *
 * @package models
 *
 * @module CITAPI
 *
 * @class Cit_api_model.php
 *
 * @path application\models\Cit_api_model.php
 *
 * @version 4.0
 *
 * @author CIT Dev Team
 *
 * @date 03.02.2016
 */
class Cit_api_model extends CI_Model
{

    protected $unset_paths = array();

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * setAPIModulePath method is used to set webservice module paths.
     * @return boolean $res returns TRUE or FALSE.
     */
    protected function setAPIModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && !array_key_exists(APPPATH . 'webservice/', $marr)) {
            $this->unset_paths = $marr;
            $narr = array(
                APPPATH . 'webservice/' => '../webservice/'
            );
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * unsetAPIModulePath method is used to unset webservice module paths and sets previous module paths.
     * @return boolean $res returns TRUE or FALSE.
     */
    protected function unsetAPIModulePath()
    {
        $marr = Modules::$locations;
        if (is_array($marr) && array_key_exists(APPPATH . 'webservice/', $marr)) {
            $narr = $this->unset_paths;
            Modules::$locations = $narr;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @deprecated since 4.2.1 : 01/09/2016
     * getData method is used to get response from webservice.
     * @param string $func_name func_name is the API name to execute partiular API flow.
     * @param array $request_arr request_arr is the input parameters for the API.
     * @return array $response returns API response in array.
     */
    public function getData($func_name = '', $request_arr = array())
    {
        if (method_exists($this, "callAPI")) {
            return "This method is deprecated. Please use 'callAPI' method to run API.";
        }
        $response = array();
        $this->setAPIModulePath();
        try {
            //fetching webservice config details
            $this->config->load('cit_webservices', TRUE);
            $all_methods = $this->config->item('cit_webservices');
            if (empty($all_methods[$func_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            if (isset($request_arr['lang_id']) && $request_arr['lang_id'] != "") {
                $_POST['lang_id'] = $request_arr['lang_id'];
            } else {
                $multi_lingual = $this->config->item('MULTI_LINGUAL_PROJECT');
                if ($multi_lingual == "Yes") {
                    $request_arr['lang_id'] = "en";
                    $_POST['lang_id'] = "en";
                }
            }

            //loading for webservice controller
            $this->load->module($all_methods[$func_name]['folder'] . "/" . $func_name);

            //checking for webservice controller
            if (!is_object($this->$func_name)) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            //checking for webservice start method
            $start_method = "start_" . $func_name;
            if (!method_exists($this->$func_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }

            //initializing for webservice start method
            $response = $this->$func_name->$start_method($request_arr, TRUE);
            if ($response['success'] == -5) {
                $response = array(
                    'settings' => array(
                        "status" => 200,
                        "success" => 0,
                        "message" => $response['message']
                    ),
                    'data' => array()
                );
            }
        } catch (Exception $e) {
            $response = array(
                'settings' => array(
                    "status" => 400,
                    "success" => 0,
                    "message" => $e->getMessage()
                ),
                'data' => array()
            );
        }
        $this->unsetAPIModulePath();
        return $response;
    }

    /**
     * @since version 4.2.1 : 01/09/2016
     * callAPI method is used to get response from API.
     * @param string $api_name api_name is the API name to execute partiular API flow.
     * @param array $params params is the input parameters for the API.
     * @return array $response returns API response in array.
     */
    public function callAPI($api_name = '', $params = array())
    {
        $response = array();
        $this->setAPIModulePath();
        try {
            //fetching webservice config details
            $this->config->load('cit_webservices', TRUE);
            $all_methods = $this->config->item('cit_webservices');
            if (empty($all_methods[$api_name])) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            if (isset($params['lang_id']) && $params['lang_id'] != "") {
                $_POST['lang_id'] = $params['lang_id'];
            } else {
                $multi_lingual = $this->config->item('MULTI_LINGUAL_PROJECT');
                if ($multi_lingual == "Yes") {
                    $params['lang_id'] = "en";
                    $_POST['lang_id'] = "en";
                }
            }

            //loading for webservice controller
            $this->load->module($all_methods[$api_name]['folder'] . "/" . $api_name);

            //checking for webservice controller
            if (!is_object($this->$api_name)) {
                throw new Exception('API code not found. Please save settings or update code.');
            }

            //checking for webservice start method
            $start_method = "start_" . $api_name;
            if (!method_exists($this->$api_name, $start_method)) {
                throw new Exception('API init method not found. Please save settings or update code.');
            }

            //initializing for webservice start method
            $response = $this->$api_name->$start_method($params, TRUE);
            if ($response['success'] == -5) {
                $response = array(
                    'settings' => array(
                        "status" => 200,
                        "success" => 0,
                        "message" => $response['message']
                    ),
                    'data' => array()
                );
            }
        } catch (Exception $e) {
            $response = array(
                'settings' => array(
                    "status" => 400,
                    "success" => 0,
                    "message" => $e->getMessage()
                ),
                'data' => array()
            );
        }
        $this->unsetAPIModulePath();
        return $response;
    }
}
