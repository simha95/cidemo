<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of State Model
 *
 * @category webservice
 *
 * @package tools
 *
 * @subpackage models
 *
 * @module State
 *
 * @class State_model.php
 *
 * @path application\webservice\tools\models\State_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 03.10.2017
 */

class State_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    /**
     * get_state_list method is used to execute database queries for Country With States API.
     * @created  | 28.01.2016
     * @modified  | 28.01.2016
     * @param string $mc_country_id mc_country_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_state_list($mc_country_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_state AS ms");

            $this->db->select("ms.iStateId AS ms_state_id");
            $this->db->select("ms.vState AS ms_state");
            $this->db->select("ms.vStateCode AS ms_state_code");
            $this->db->select("ms.eStatus AS ms_status");
            if (isset($mc_country_id) && $mc_country_id != "")
            {
                $this->db->where("ms.iCountryId =", $mc_country_id);
            }

            $this->db->order_by("ms.vState", "asc");

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
