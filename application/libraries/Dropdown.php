<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Dropdown Render Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module Dropdown
 * 
 * @class Dropdown.php
 * 
 * @path application\libraries\Dropdown.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Dropdown
{

    protected $CI;
    protected $selected;
    protected $combo_array;
    protected $options_only;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function combo()
    {
        $arg_list = func_get_args();
        $combo_type = $arg_list[0];
        $combo_code = $arg_list[1];
        switch ($combo_type) {
            case "array":
                $this->selected[$combo_code] = $arg_list[3];
                $this->combo_array[$combo_code] = $arg_list[2];
                $this->options_only[$combo_code] = $arg_list[4];
                break;
            case "table":
                $table = $arg_list[2];
                $field1 = $arg_list[3];
                $field2 = $arg_list[4];
                $this->selected[$combo_code] = $arg_list[5];
                $extra_condition = $arg_list[6];
                $limit = null;
                $offset = null;
                $order_by = "";
                if ($arg_list[7] != "")
                    $order_by = $arg_list[7];

                if ($arg_list[8] != "")
                    $limit = $arg_list[8];
                if ($arg_list[9] != "")
                    $offset = $arg_list[9];
                $this->combo_array[$combo_code] = $this->CI->db->select_combo($table, $field1, $field2, $limit, $offset, $extra_condition, $order_by);
                break;
        }
    }

    /**
     *  display	 *
     *  @category function
     *  @access public
     *  @param  string   $code
     *  @param  string   $name
     *  @param  string   $extra    ' '
     *  @param  string   $defult_top_option  "|||Select:::-1|||All",$defult_bottom_option=""
     *  @param  string   $defult_bottom_option  ""
     *  @return   view
     */
    public function display($code, $name, $extra = '', $default_top_option = "", $default_bottom_option = "", $selected = "", $id = "")
    {
        $combo_array = $this->combo_array[$code];
        $top_array = $bottom_array = array();
        if ($default_top_option != "") {
            $top_option = explode(":::", $default_top_option);
            if (is_array($top_option) && count($top_option) > 0) {
                foreach ($top_option as $key => $val) {
                    $val_arr = explode("|||", $val);
                    $top_array[$val_arr[0]] = $val_arr[1];
                }
            }
            $combo_array = (is_array($combo_array)) ? $top_array + $combo_array : $top_array;
        }
        if ($default_bottom_option != "") {
            $bottom_option = explode(":::", $default_bottom_option);
            if (is_array($bottom_option) && count($bottom_option) > 0) {
                foreach ($bottom_option as $key => $val) {
                    $val_arr = explode("|||", $val);
                    $bottom_array[$val_arr[0]] = $val_arr[1];
                }
            }
            $combo_array = (is_array($combo_array)) ? $combo_array + $bottom_array : $bottom_array;
        }
        if ($selected != "") {
            if (is_string($selected) && strstr($selected, ",")) {
                $selected = explode(",", $selected);
            }
            $this->selected[$code] = $selected;
        }
        if ($id == '') {
            $id = str_replace("[]", "", $name);
        }
        $combo_arr = array(
            'combo_id' => $id,
            'combo_name' => $name,
            'combo_extra' => $extra,
            'combo_array' => $combo_array,
            'combo_selected' => $this->selected[$code],
            'options_only' => $this->options_only[$code]
        );
        return $this->CI->parser->parse("libraries/dropdown.tpl", $combo_arr, true);
    }

    /**
     *  render	 *
     *  @category function
     *  @access public
     *  @param  string   $code
     *  @param  string   $name
     *  @param  string   $extra    ' '
     *  @param  string   $defult_top_option  "|||Select:::-1|||All",$defult_bottom_option=""
     *  @param  string   $defult_bottom_option  ""
     *  @return   view
     */
    public function render($begin = 1, $end = 10, $name = '', $extra = '', $selected = "", $id = "", $options = FALSE)
    {
        if ($id == '') {
            $id = str_replace("[]", "", $name);
        }
        $combo_array = array();
        for ($i = $begin; $i <= $end; $i++) {
            $combo_array[$i] = $i;
        }
        if ($selected != "") {
            if (is_string($selected) && strstr($selected, ",")) {
                $selected = explode(",", $selected);
            }
        }
        $combo_arr = array(
            'combo_id' => $id,
            'combo_name' => $name,
            'combo_extra' => $extra,
            'combo_array' => $combo_array,
            'combo_selected' => $selected,
            'options_only' => $options
        );
        return $this->CI->parser->parse("libraries/dropdown.tpl", $combo_arr, true);
    }

    public function clear($code = '')
    {
        if (!is_array($this->selected) || !array_key_exists($code, $this->selected)) {
            return;
        }
        unset($this->selected[$code]);
    }
}

/* End of file Dropdown.php */
/* Location: ./application/libraries/Dropdown.php */