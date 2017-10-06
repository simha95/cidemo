<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Customer Model
 *
 * @category webservice
 *
 * @package user
 *
 * @subpackage models
 *
 * @module Customer
 *
 * @class Customer_model.php
 *
 * @path application\webservice\user\models\Customer_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 04.10.2017
 */

class Customer_model extends CI_Model
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
     * insert_customer_data method is used to execute database queries for Customer Add API.
     * @created  | 28.01.2016
     * @modified  | 29.01.2016
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function insert_customer_data($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["username"]))
            {
                $this->db->set("vUserName", $params_arr["username"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["profile_image"]) && !empty($params_arr["profile_image"]))
            {
                $this->db->set("vProfileImage", $params_arr["profile_image"]);
            }
            $this->db->set($this->db->protect("dtRegisteredDate"), $params_arr["_dtregistereddate"], FALSE);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->insert("mod_customer");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "customer_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * check_reg_email_exists method is used to execute database queries for Customer Add API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param string $email email is used to process query block.
     * @param string $username username is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_reg_email_exists($email = '', $username = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            if (isset($email) && $email != "")
            {
                $this->db->where("mc.vEmail =", $email);
            }
            if (isset($username) && $username != "")
            {
                $this->db->where("mc.vUserName =", $username);
            }

            $this->db->limit(1);

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

    /**
     * get_customer_login_details method is used to execute database queries for Customer Login API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param string $username username is used to process query block.
     * @param string $password password is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_customer_login_details($username = '', $password = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS u");

            $this->db->select("u.iCustomerId AS u_customer_id");
            $this->db->select("u.vFirstName AS u_first_name");
            $this->db->select("u.vLastName AS u_last_name");
            $this->db->select("u.vEmail AS u_email");
            $this->db->select("u.vUserName AS u_user_name");
            $this->db->select("u.vProfileImage AS u_profile_image");
            $this->db->select("u.eStatus AS u_status");
            if (isset($username) && $username != "")
            {
                $this->db->where("u.vUserName =", $username);
            }
            if (isset($password) && $password != "")
            {
                $this->db->where("u.vPassword =", $password);
            }

            $this->db->limit(1);

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

    /**
     * update_customer_data method is used to execute database queries for Customer Update API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_customer_data($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();

            $this->db->start_cache();
            if (isset($where_arr["customer_id"]) && $where_arr["customer_id"] != "")
            {
                $this->db->where("iCustomerId =", $where_arr["customer_id"]);
            }
            $this->db->stop_cache();
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["profile_image"]) && !empty($params_arr["profile_image"]))
            {
                $this->db->set("vProfileImage", $params_arr["profile_image"]);
            }
            $res = $this->db->update("mod_customer");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_customer_detail method is used to execute database queries for Customer Detail API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param string $customer_id customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_customer_detail($customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            $this->db->select("mc.vFirstName AS mc_first_name");
            $this->db->select("mc.vLastName AS mc_last_name");
            $this->db->select("mc.vEmail AS mc_email");
            $this->db->select("mc.vUserName AS mc_user_name");
            $this->db->select("mc.vProfileImage AS mc_profile_image");
            $this->db->select("mc.eStatus AS mc_status");
            if (isset($customer_id) && $customer_id != "")
            {
                $this->db->where("mc.iCustomerId =", $customer_id);
            }

            $this->db->limit(1);

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

    /**
     * check_customer_password method is used to execute database queries for Change Password API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param string $customer_id customer_id is used to process query block.
     * @param string $old_password old_password is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function check_customer_password($customer_id = '', $old_password = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            $this->db->select("mc.vPassword AS mc_password");
            if (isset($customer_id) && $customer_id != "")
            {
                $this->db->where("mc.iCustomerId =", $customer_id);
            }
            if (isset($old_password) && $old_password != "")
            {
                $this->db->where("mc.vPassword =", $old_password);
            }

            $this->db->limit(1);

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

    /**
     * update_customer_password method is used to execute database queries for Change Password API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_customer_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["customer_id"]) && $where_arr["customer_id"] != "")
            {
                $this->db->where("iCustomerId =", $where_arr["customer_id"]);
            }
            if (isset($params_arr["new_password"]))
            {
                $this->db->set("vPassword", $params_arr["new_password"]);
            }
            $res = $this->db->update("mod_customer");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_customer_by_email method is used to execute database queries for Forgot Password API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param string $email email is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_customer_by_email($email = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            $this->db->select("mc.vFirstName AS mc_first_name");
            $this->db->select("mc.vLastName AS mc_last_name");
            $this->db->select("mc.vEmail AS mc_email");
            $this->db->select("mc.vUserName AS mc_user_name");
            if (isset($email) && $email != "")
            {
                $this->db->where("mc.vEmail =", $email);
            }

            $this->db->limit(1);

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

    /**
     * change_customer_password method is used to execute database queries for Forgot Password API.
     * @created  | 29.01.2016
     * @modified  | 29.01.2016
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function change_customer_password($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["mc_customer_id"]) && $where_arr["mc_customer_id"] != "")
            {
                $this->db->where("iCustomerId =", $where_arr["mc_customer_id"]);
            }
            if (isset($params_arr["random_password"]))
            {
                $this->db->set("vPassword", $params_arr["random_password"]);
            }
            $res = $this->db->update("mod_customer");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * select_query method is used to execute database queries for crud_select_mod_customer API.
     * @created CIT Dev Team
     * @modified ---
     * @param array $settings_params settings_params are used for paging parameters.
     * @return array $return_arr returns response of query block.
     */
    public function select_query($page_index = 1, &$settings_params = array())
    {
        try
        {
            $result_arr = array();

            $this->db->start_cache();
            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            $this->db->select("mc.vFirstName AS mc_first_name");
            $this->db->select("mc.vLastName AS mc_last_name");
            $this->db->select("mc.vEmail AS mc_email");
            $this->db->select("mc.vUserName AS mc_user_name");
            $this->db->select("mc.vPassword AS mc_password");
            $this->db->select("mc.vProfileImage AS mc_profile_image");
            $this->db->select("mc.dtRegisteredDate AS mc_registered_date");
            $this->db->select("mc.eStatus AS mc_status");

            $this->db->stop_cache();
            $total_records = $this->db->count_all_results();

            $settings_params['count'] = $total_records;

            $record_limit = 50;
            $current_page = intval($page_index) > 0 ? intval($page_index) : 1;
            $total_pages = getTotalPages($total_records, $record_limit);
            $start_index = getStartIndex($total_records, $current_page, $record_limit);
            $settings_params['per_page'] = $record_limit;
            $settings_params['curr_page'] = $current_page;
            $settings_params['prev_page'] = ($current_page > 1) ? 1 : 0;
            $settings_params['next_page'] = ($current_page+1 > $total_pages) ? 0 : 1;

            $this->db->limit($record_limit, $start_index);
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            $this->db->flush_cache();
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

    /**
     * sel_id_query method is used to execute database queries for crud_sel_id_mod_customer API.
     * @created CIT Dev Team
     * @modified ---
     * @param string $customer_id customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function sel_id_query($customer_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("mod_customer AS mc");

            $this->db->select("mc.iCustomerId AS mc_customer_id");
            $this->db->select("mc.vFirstName AS mc_first_name");
            $this->db->select("mc.vLastName AS mc_last_name");
            $this->db->select("mc.vEmail AS mc_email");
            $this->db->select("mc.vUserName AS mc_user_name");
            $this->db->select("mc.vPassword AS mc_password");
            $this->db->select("mc.vProfileImage AS mc_profile_image");
            $this->db->select("mc.dtRegisteredDate AS mc_registered_date");
            $this->db->select("mc.eStatus AS mc_status");
            if (isset($customer_id) && $customer_id != "")
            {
                $this->db->where("mc.iCustomerId =", $customer_id);
            }

            $this->db->limit(1);

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

    /**
     * insert_query method is used to execute database queries for crud_insert_mod_customer API.
     * @created CIT Dev Team
     * @modified ---
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function insert_query($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["profile_image"]))
            {
                $this->db->set("vProfileImage", $params_arr["profile_image"]);
            }
            if (isset($params_arr["registered_date"]))
            {
                $this->db->set("dtRegisteredDate", $params_arr["registered_date"]);
            }
            if (isset($params_arr["status"]))
            {
                $this->db->set("eStatus", $params_arr["status"]);
            }
            $this->db->insert("mod_customer");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "ret_insert_id";
            $result_arr[0][$result_param] = $insert_id;
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

    /**
     * update_query method is used to execute database queries for crud_update_mod_customer API.
     * @created CIT Dev Team
     * @modified ---
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_query($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            if (isset($where_arr["customer_id"]) && $where_arr["customer_id"] != "")
            {
                $this->db->where("iCustomerId =", $where_arr["customer_id"]);
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["user_name"]))
            {
                $this->db->set("vUserName", $params_arr["user_name"]);
            }
            if (isset($params_arr["password"]))
            {
                $this->db->set("vPassword", $params_arr["password"]);
            }
            if (isset($params_arr["profile_image"]))
            {
                $this->db->set("vProfileImage", $params_arr["profile_image"]);
            }
            if (isset($params_arr["registered_date"]))
            {
                $this->db->set("dtRegisteredDate", $params_arr["registered_date"]);
            }
            if (isset($params_arr["status"]))
            {
                $this->db->set("eStatus", $params_arr["status"]);
            }
            $res = $this->db->update("mod_customer");
            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * delete_query method is used to execute database queries for crud_delete_mod_customer API.
     * @created CIT Dev Team
     * @modified ---
     * @param string $mc_customer_id mc_customer_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function delete_query($mc_customer_id = '')
    {
        try
        {
            $result_arr = array();
            if (isset($mc_customer_id) && $mc_customer_id != "")
            {
                $this->db->where("iCustomerId =", $mc_customer_id);
            }
            $res = $this->db->delete("mod_customer");
            if (!$res)
            {
                throw new Exception("Failure in deletion.");
            }
            $affected_rows = $this->db->affected_rows();
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
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
