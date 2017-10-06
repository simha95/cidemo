<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of WS Messages Model
 *
 * test
 *
 * @category admin
 *
 * @package ws_messages
 *
 * @subpackage models
 *
 * @module WS Messages
 *
 * @class Ws_messages_model.php
 *
 * @path application\admin\ws_messages\models\Ws_messages_model.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @date 12.10.2016
 */
class Ws_messages_model extends CI_Model
{

    public $table_name;
    public $table_alias;
    public $primary_key;
    public $primary_alias;
    public $grid_fields;
    public $join_tables;
    public $extra_cond;
    public $groupby_cond;
    public $orderby_cond;
    public $unique_type;
    public $unique_fields;
    public $switchto_fields;
    public $default_filters;
    public $search_config;
    public $relation_modules;
    public $deletion_modules;
    public $print_rec;
    public $multi_lingual;
    public $physical_data_remove;
    //
    public $listing_data;
    public $rec_per_page;
    public $message;

    /**
     * __construct method is used to set model preferences while model object initialization.
     * @created CIT Admin | 11.10.2016
     * @modified CIT Admin | 12.10.2016
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->table_name = "mod_ws_messages";
        $this->table_alias = "mwm";
        $this->primary_key = "iWSMessageId";
        $this->primary_alias = "mwm_ws_message_id";
        $this->physical_data_remove = "Yes";
        $this->grid_fields = array(
            "mwm_apiname",
            "mwm_message_code",
            "mwml_message",
            "mwm_type",
            "mwm_status",
        );
        $this->join_tables = array();
        $this->extra_cond = "";
        $this->groupby_cond = array();
        $this->having_cond = "";
        $this->orderby_cond = array();
        $this->unique_type = "OR";
        $this->unique_fields = array();
        $this->switchto_fields = array(
            $this->db->protect("mwm.vCode")
        );
        $this->default_filters = array();
        $this->search_config = array();
        $this->relation_modules = array();
        $this->deletion_modules = array();
        $this->print_rec = "No";
        $this->multi_lingual = "Yes";

        $this->rec_per_page = $this->config->item('REC_LIMIT');
    }

    /**
     * insert method is used to insert data records to the database table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insert($data = array())
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update method is used to update data records to the database table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while updating records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function update($data = array(), $where = '', $alias = "No", $join = "No")
    {
        if ($alias == "Yes") {
            if ($join == "Yes") {
                $join_tbls = $this->addJoinTables("NR");
            }
            if (trim($join_tbls) != '') {
                $set_cond = array();
                foreach ($data as $key => $val) {
                    $set_cond[] = $this->db->protect($key) . " = " . $this->db->escape($val);
                }
                if (is_numeric($where)) {
                    $extra_cond = " WHERE " . $this->db->protect($this->table_alias . "." . $this->primary_key) . " = " . $this->db->escape($where);
                } elseif ($where) {
                    $extra_cond = " WHERE " . $where;
                } else {
                    return FALSE;
                }
                $update_query = "UPDATE " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias) . " " . $join_tbls . " SET " . implode(", ", $set_cond) . " " . $extra_cond;
                $res = $this->db->query($update_query);
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->table_alias . "." . $this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
            }
        } else {
            if (is_numeric($where)) {
                $this->db->where($this->primary_key, $where);
            } elseif ($where) {
                $this->db->where($where, FALSE, FALSE);
            } else {
                return FALSE;
            }
            $res = $this->db->update($this->table_name, $data);
        }
        return $res;
    }

    /**
     * delete method is used to delete data records from the database table.
     * @param string $where where is the query condition for deletion.
     * @param string $alias alias is to keep aliases for query or not.
     * @param string $join join is to make joins while deleting records.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function delete($where = "", $alias = "No", $join = "No")
    {
        if ($this->config->item('PHYSICAL_RECORD_DELETE') && $this->physical_data_remove == 'No') {
            if ($alias == "Yes") {
                if (is_array($join['joins']) && count($join['joins'])) {
                    $join_tbls = '';
                    if ($join['list'] == "Yes") {
                        $join_tbls = $this->addJoinTables("NR");
                    }
                    $join_tbls .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif ($join == "Yes") {
                    $join_tbls = $this->addJoinTables("NR");
                }
                $data = $this->general->getPhysicalRecordUpdate($this->table_alias);
                if (trim($join_tbls) != '') {
                    $set_cond = array();
                    foreach ($data as $key => $val) {
                        $set_cond[] = $this->db->protect($key) . " = " . $this->db->escape($val);
                    }
                    if (is_numeric($where)) {
                        $extra_cond = " WHERE " . $this->db->protect($this->table_alias . "." . $this->primary_key) . " = " . $this->db->escape($where);
                    } elseif ($where) {
                        $extra_cond = " WHERE " . $where;
                    } else {
                        return FALSE;
                    }
                    $update_query = "UPDATE " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias) . " " . $join_tbls . " SET " . implode(", ", $set_cond) . " " . $extra_cond;
                    $res = $this->db->query($update_query);
                } else {
                    if (is_numeric($where)) {
                        $this->db->where($this->table_alias . "." . $this->primary_key, $where);
                    } elseif ($where) {
                        $this->db->where($where, FALSE, FALSE);
                    } else {
                        return FALSE;
                    }
                    $res = $this->db->update($this->table_name . " AS " . $this->table_alias, $data);
                }
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $data = $this->general->getPhysicalRecordUpdate();
                $res = $this->db->update($this->table_name, $data);
            }
        } else {
            if ($alias == "Yes") {
                $del_query = "DELETE " . $this->db->protect($this->table_alias) . ".* FROM " . $this->db->protect($this->table_name) . " AS " . $this->db->protect($this->table_alias);
                if (is_array($join['joins']) && count($join['joins'])) {
                    if ($join['list'] == "Yes") {
                        $del_query .= $this->addJoinTables("NR");
                    }
                    $del_query .= ' ' . $this->listing->addJoinTables($join['joins'], "NR");
                } elseif ($join == "Yes") {
                    $del_query .= $this->addJoinTables("NR");
                }
                if (is_numeric($where)) {
                    $del_query .= " WHERE " . $this->db->protect($this->table_alias) . "." . $this->db->protect($this->primary_key) . " = " . $this->db->escape($where);
                } elseif ($where) {
                    $del_query .= " WHERE " . $where;
                } else {
                    return FALSE;
                }
                $res = $this->db->query($del_query);
            } else {
                if (is_numeric($where)) {
                    $this->db->where($this->primary_key, $where);
                } elseif ($where) {
                    $this->db->where($where, FALSE, FALSE);
                } else {
                    return FALSE;
                }
                $res = $this->db->delete($this->table_name);
            }
        }
        return $res;
    }

    /**
     * getData method is used to get data records for this module.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are either array or string.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $having_cond having cond is the query condition for getting conditional data.
     * @param boolean $list list is to differ listing fields or form fields.
     * @return array $data_arr returns data records array.
     */
    public function getData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $having_cond = '', $list = FALSE)
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } elseif ($list == TRUE) {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
            if ($this->primary_alias != "") {
                $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
            }
            $this->db->select("mwm.vAPIName AS mwm_apiname");
            $this->db->select("mwm.vCode AS mwm_message_code");
            $this->db->select("mwml.tMessage AS mwml_message");
            $this->db->select("mwm.vType AS mwm_type");
            $this->db->select("mwm.eStatus AS mwm_status");
        } else {
            $this->db->select("mwm.iWSMessageId AS iWSMessageId");
            $this->db->select("mwm.iWSMessageId AS mwm_ws_message_id");
            $this->db->select("mwm.vAPIName AS mwm_apiname");
            $this->db->select("mwm.vCode AS mwm_message_code");
            $this->db->select("mwml.tMessage AS mwml_message");
            $this->db->select("mwm.vType AS mwm_type");
            $this->db->select("mwm.eStatus AS mwm_status");
        }

        $this->db->from($this->table_name . " AS " . $this->table_alias);

        $this->addJoinTables("AR");
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif (is_numeric($extra_cond)) {
            $this->db->where($this->table_alias . "." . $this->primary_key, intval($extra_cond));
        } elseif ($extra_cond) {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            if (is_numeric($limit)) {
                $this->db->limit($limit);
            } else {
                list($offset, $limit) = explode(",", $limit);
                $this->db->limit($offset, $limit);
            }
        }
        $data_obj = $this->db->get();
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    /**
     * getListingData method is used to get grid listing data records for this module.
     * @param array $config_arr config_arr for grid listing settigs.
     * @return array $listing_data returns data records array for grid.
     */
    public function getListingData($config_arr = array())
    {
        $page = $config_arr['page'];
        $rows = $config_arr['rows'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $rec_per_page = (intval($rows) > 0) ? intval($rows) : $this->rec_per_page;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->start_cache();
        $this->db->from($this->table_name . " AS " . $this->table_alias);
        $this->addJoinTables("AR");
        if ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0) {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;
        $filter_config['grid_fields'] = $this->grid_fields;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        if ($filter_main != "") {
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if ($filter_left != "") {
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if ($filter_range != "") {
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if ($this->primary_alias != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("mwm.vAPIName AS mwm_apiname");
        $this->db->select("mwm.vCode AS mwm_message_code");
        $this->db->select("mwml.tMessage AS mwml_message");
        $this->db->select("mwm.vType AS mwm_type");
        $this->db->select("mwm.eStatus AS mwm_status");

        $this->db->stop_cache();
        if ((is_array($group_by) && count($group_by) > 0) || trim($having_cond) != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key);
            $total_records_arr = $this->db->get();
            $total_records = is_object($total_records_arr) ? $total_records_arr->num_rows() : 0;
        } else {
            $total_records = $this->db->count_all_results();
        }

        $total_pages = $this->listing->getTotalPages($total_records, $rec_per_page);
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $orK => $orV) {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "") {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        $limit_offset = $this->listing->getStartIndex($total_records, $page, $rec_per_page);
        $this->db->limit($rec_per_page, $limit_offset);
        $return_data_obj = $this->db->get();
        $return_data = is_object($return_data_obj) ? $return_data_obj->result_array() : array();
        $this->db->flush_cache();
        $this->listing_data = $return_data;
        return $this->listing->getDataForJqGrid($return_data, $filter_config, $page, $total_pages, $total_records);
    }

    /**
     * getExportData method is used to get grid export data records for this module.
     * @param array $config_arr config_arr for grid export settigs.
     * @return array $export_data returns data records array for export.
     */
    public function getExportData($config_arr = array())
    {
        $page = $config_arr['page'];
        $rows = $config_arr['rows'];
        $rowlimit = $config_arr['rowlimit'];
        $sidx = $config_arr['sidx'];
        $sord = $config_arr['sord'];
        $sdef = $config_arr['sdef'];
        $filters = $config_arr['filters'];

        $extra_cond = $config_arr['extra_cond'];
        $group_by = $config_arr['group_by'];
        $having_cond = $config_arr['having_cond'];
        $order_by = $config_arr['order_by'];

        $page = ($page != '') ? $page : 1;
        $extra_cond = ($extra_cond != "") ? $extra_cond : "";

        $this->db->from($this->table_name . " AS " . $this->table_alias);
        $this->addJoinTables("AR");
        if ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if (is_array($group_by) && count($group_by) > 0) {
            $this->db->group_by($group_by);
        }
        if ($having_cond != "") {
            $this->db->having($having_cond, FALSE, FALSE);
        }
        $filter_config = array();
        $filter_config['module_config'] = $config_arr['module_config'];
        $filter_config['list_config'] = $config_arr['list_config'];
        $filter_config['form_config'] = $config_arr['form_config'];
        $filter_config['dropdown_arr'] = $config_arr['dropdown_arr'];
        $filter_config['search_config'] = $this->search_config;
        $filter_config['table_name'] = $this->table_name;
        $filter_config['table_alias'] = $this->table_alias;
        $filter_config['primary_key'] = $this->primary_key;

        $filter_main = $this->filter->applyFilter($filters, $filter_config, "Select");
        $filter_left = $this->filter->applyLeftFilter($filters, $filter_config, "Select");
        $filter_range = $this->filter->applyRangeFilter($filters, $filter_config, "Select");
        if ($filter_main != "") {
            $this->db->where("(" . $filter_main . ")", FALSE, FALSE);
        }
        if ($filter_left != "") {
            $this->db->where("(" . $filter_left . ")", FALSE, FALSE);
        }
        if ($filter_range != "") {
            $this->db->where("(" . $filter_range . ")", FALSE, FALSE);
        }

        $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_key);
        if ($this->primary_alias != "") {
            $this->db->select($this->table_alias . "." . $this->primary_key . " AS " . $this->primary_alias);
        }
        $this->db->select("mwm.vAPIName AS mwm_apiname");
        $this->db->select("mwm.vCode AS mwm_message_code");
        $this->db->select("mwml.tMessage AS mwml_message");
        $this->db->select("mwm.vType AS mwm_type");
        $this->db->select("mwm.eStatus AS mwm_status");
        if ($sdef == "Yes" && is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $orK => $orV) {
                $sort_filed = $orV['field'];
                $sort_order = (strtolower($orV['order']) == "desc") ? "DESC" : "ASC";
                $this->db->order_by($sort_filed, $sort_order);
            }
        }
        if ($sidx != "") {
            $this->listing->addGridOrderBy($sidx, $sord, $config_arr['list_config']);
        }
        if ($rowlimit != "") {
            $offset = $rowlimit;
            $limit = ($rowlimit * $page - $rowlimit);
            $this->db->limit($offset, $limit);
        }
        $export_data_obj = $this->db->get();
        return (is_object($export_data_obj) ? $export_data_obj->result_array() : array());
    }

    /**
     * addJoinTables method is used to make relation tables joins with main table.
     * @param string $type type is to get active record or join string.
     * @param boolean $allow_tables allow_table is to restrict some set of tables.
     * @return string $ret_joins returns relation tables join string.
     */
    public function addJoinTables($type = 'AR', $allow_tables = FALSE)
    {
        $join_tables = $this->join_tables;
        if ($this->config->item("MULTI_LINGUAL_PROJECT") == "Yes") {
            $lang_code = $this->config->item("DEFAULT_LANG");
        } else {
            $lang_code = "EN";
        }
        $lang_arr = array(
            array(
                "table_name" => "mod_ws_messages_lang",
                "table_alias" => "mwml",
                "field_name" => "iWSMessageId",
                "rel_table_name" => "mod_ws_messages",
                "rel_table_alias" => "mwm",
                "rel_field_name" => "iWSMessageId",
                "join_type" => "left",
                "extra_condition" => $this->db->protect("mwml.vLangCode") . " = " . $this->db->escape($this->config->item("DEFAULT_LANG")
                ))
        );
        $join_tables = is_array($join_tables) ? array_merge($lang_arr, $join_tables) : $lang_arr;
        if (!is_array($join_tables) || count($join_tables) == 0) {
            return '';
        }
        return $this->listing->addJoinTables($join_tables, $type, $allow_tables);
    }

    /**
     * getListConfiguration method is used to get listing configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns listing configuration array.
     */
    public function getListConfiguration($name = "")
    {
        $list_config = array(
            "mwm_apiname" => array(
                "name" => "mwm_apiname",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vAPIName",
                "source_field" => "mwm_apiname",
                "display_query" => "mwm.vAPIName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "API Name",
                "label_lang" => $this->lang->line('WS_MESSAGES_API_NAME'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
            ),
            "mwm_message_code" => array(
                "name" => "mwm_message_code",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vCode",
                "source_field" => "mwm_message_code",
                "display_query" => "mwm.vCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "left",
                "label" => "Code",
                "label_lang" => $this->lang->line('WS_MESSAGES_CODE'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "edit_link" => "Yes",
            ),
            "mwml_message" => array(
                "name" => "mwml_message",
                "table_name" => "mod_ws_messages_lang",
                "table_alias" => "mwml",
                "field_name" => "tMessage",
                "source_field" => "",
                "display_query" => "mwml.tMessage",
                "entry_type" => "Table",
                "data_type" => "",
                "show_in" => "Both",
                "type" => "textbox",
                "align" => "center",
                "label" => "Message",
                "label_lang" => $this->lang->line('WS_MESSAGES_MESSAGE'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
                "lang" => "Yes"
            ),
            "mwm_type" => array(
                "name" => "mwm_type",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vType",
                "source_field" => "mwm_type",
                "display_query" => "mwm.vType",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Type",
                "label_lang" => $this->lang->line('WS_MESSAGES_TYPE'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
            ),
            "mwm_status" => array(
                "name" => "mwm_status",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "eStatus",
                "source_field" => "mwm_status",
                "display_query" => "mwm.eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "show_in" => "Both",
                "type" => "dropdown",
                "align" => "center",
                "label" => "Status",
                "label_lang" => $this->lang->line('WS_MESSAGES_STATUS'),
                "width" => 75,
                "search" => "Yes",
                "sortable" => "Yes",
                "addable" => "No",
                "editable" => "No",
                "viewedit" => "No",
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0) {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++) {
                $config_arr[$name[$i]] = $list_config[$name[$i]];
            }
        } elseif ($name != "" && is_string($name)) {
            $config_arr = $list_config[$name];
        } else {
            $config_arr = $list_config;
        }
        return $config_arr;
    }

    /**
     * getFormConfiguration method is used to get form configuration array.
     * @param string $name name is to get specific field configuration.
     * @return array $config_arr returns form configuration array.
     */
    public function getFormConfiguration($name = "")
    {
        $form_config = array(
            "mwm_apiname" => array(
                "name" => "mwm_apiname",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vAPIName",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "API Name",
                "label_lang" => $this->lang->line('WS_MESSAGES_API_NAME')
            ),
            "mwm_message_code" => array(
                "name" => "mwm_message_code",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vCode",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Code",
                "label_lang" => $this->lang->line('WS_MESSAGES_CODE')
            ),
            "mwml_message" => array(
                "name" => "mwml_message",
                "table_name" => "mod_ws_messages_lang",
                "table_alias" => "mwml",
                "field_name" => "tMessage",
                "entry_type" => "Table",
                "type" => "textarea",
                "label" => "Message",
                "label_lang" => $this->lang->line('WS_MESSAGES_MESSAGE'),
                "lang" => "Yes"
            ),
            "mwm_type" => array(
                "name" => "mwm_type",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "vType",
                "entry_type" => "Table",
                "data_type" => "varchar",
                "type" => "textbox",
                "label" => "Type",
                "label_lang" => $this->lang->line('WS_MESSAGES_TYPE')
            ),
            "mwm_status" => array(
                "name" => "mwm_status",
                "table_name" => "mod_ws_messages",
                "table_alias" => "mwm",
                "field_name" => "eStatus",
                "entry_type" => "Table",
                "data_type" => "enum",
                "type" => "dropdown",
                "label" => "Status",
                "label_lang" => $this->lang->line('WS_MESSAGES_STATUS')
            )
        );

        $config_arr = array();
        if (is_array($name) && count($name) > 0) {
            $name_cnt = count($name);
            for ($i = 0; $i < $name_cnt; $i++) {
                $config_arr[$name[$i]] = $form_config[$name[$i]];
            }
        } elseif ($name != "" && is_string($name)) {
            $config_arr = $form_config[$name];
        } else {
            $config_arr = $form_config;
        }
        return $config_arr;
    }

    /**
     * checkRecordExists method is used to check duplication of records.
     * @param array $field_arr field_arr is having fields to check.
     * @param array $field_val field_val is having values of respective fields.
     * @param numeric $id id is to avoid current records.
     * @param string $mode mode is having either Add or Update.
     * @param string $con con is having either AND or OR.
     * @return boolean $exists returns either TRUE of FALSE.
     */
    public function checkRecordExists($field_arr = array(), $field_val = array(), $id = '', $mode = '', $con = 'AND')
    {
        $exists = FALSE;
        if (!is_array($field_arr) || count($field_arr) == 0) {
            return $exists;
        }
        foreach ((array) $field_arr as $key => $val) {
            $extra_cond_arr[] = $this->db->protect($this->table_alias . "." . $field_arr[$key]) . " =  " . $this->db->escape($field_val[$val]);
        }
        $extra_cond = "(" . implode(" " . $con . " ", $extra_cond_arr) . ")";
        if ($mode == "Add") {
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0) {
                $exists = TRUE;
            }
        } elseif ($mode == "Update") {
            $extra_cond = $this->db->protect($this->table_alias . "." . $this->primary_key) . " <> " . $this->db->escape($id) . " AND " . $extra_cond;
            $data = $this->getData($extra_cond, "COUNT(*) AS tot");
            if ($data[0]['tot'] > 0) {
                $exists = TRUE;
            }
        }
        return $exists;
    }

    /**
     * getSwitchTo method is used to get switch to dropdown array.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @return array $switch_data returns data records array.
     */
    public function getSwitchTo($extra_cond = '', $type = 'records', $limit = '')
    {
        $switchto_fields = $this->switchto_fields;
        $switch_data = array();
        if (!is_array($switchto_fields) || count($switchto_fields) == 0) {
            if ($type == "count") {
                return count($switch_data);
            } else {
                return $switch_data;
            }
        }
        $fields_arr = array();
        $fields_arr[] = array(
            "field" => $this->table_alias . "." . $this->primary_key . " AS id",
        );
        $fields_arr[] = array(
            "field" => $this->db->concat($switchto_fields) . " AS val",
            "escape" => TRUE,
        );
        if (trim($this->extra_cond) != "") {
            $extra_cond = (trim($extra_cond) != "") ? $extra_cond . " AND " . $this->extra_cond : $this->extra_cond;
        }
        $switch_data = $this->getData($extra_cond, $fields_arr, "val ASC", "", $limit, "Yes");
        if ($type == "count") {
            return count($switch_data);
        } else {
            return $switch_data;
        }
    }

    /**
     * insertLang method is used to insert data records to the language table.
     * @param array $data data array for insert into table.
     * @return numeric $insert_id returns last inserted id.
     */
    public function insertLang($data = array())
    {
        $this->db->insert("mod_ws_messages_lang", $data);
        return $this->db->insert_id();
    }

    /**
     * updateLang method is used to update data records to the language table.
     * @param array $data data array for update into table.
     * @param string $where where is the query condition for updating.
     * @return boolean $res returns TRUE or FALSE.
     */
    public function updateLang($data = array(), $where = '')
    {
        if (intval($where) > 0) {
            $this->db->where($this->primary_key, $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update("mod_ws_messages_lang", $data);
    }

    /**
     * getLangData method is used to get data records from language table.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are comma seperated values.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param boolean $lang_assoc lang_assoc is to differ assoc data or normal data.
     * @return array $lang_data returns lang data records array.
     */
    public function getLangData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $lang_assoc = TRUE)
    {
        $fields = ($fields == "") ? "mwml.*" : $fields;
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } else {
            $this->db->select($fields);
        }
        $this->db->from("mod_ws_messages_lang AS mwml");
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->db->where("mwml." . $this->primary_key, $extra_cond);
            } else {
                $this->db->where($extra_cond, FALSE, FALSE);
            }
        }
        $this->general->getPhysicalRecordWhere("mod_ws_messages_lang", "mwml", "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $lang_data_obj = $this->db->get();
        $lang_data = is_object($lang_data_obj) ? $lang_data_obj->result_array() : array();
        if ($lang_assoc) {
            $lang_assoc_data = array();
            $lang_data_cnt = count($lang_data);
            for ($i = 0; $i < $lang_data_cnt; $i++) {
                $lang_assoc_data[$lang_data[$i]["vLangCode"]] = $lang_data[$i];
            }
            $lang_data = $lang_assoc_data;
        }
        return $lang_data;
    }

    /**
     * getWSMessageData method is used to get data records from ws message table.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are comma seperated values.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $assoc_field lang_assoc is to differ assoc data or normal data.
     * @return array $data_arr returns ws message data records array.
     */
    public function getWSMessageData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $assoc_field = '')
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select($this->table_alias . ".*");
        }
        $this->db->from($this->table_name . " AS " . $this->table_alias);

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif (is_numeric($extra_cond)) {
            $this->db->where($this->table_alias . "." . $this->primary_key, intval($extra_cond));
        } elseif ($extra_cond != '') {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere($this->table_name, $this->table_alias, "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        if ($assoc_field != '' && is_array($data_arr) && count($data_arr) > 0) {
            $final_array = array();
            foreach ($data_arr as $key => $data) {
                $final_array[$data[$assoc_field]][] = $data;
            }
            $data_arr = $final_array;
        }
        return $data_arr;
    }

    /**
     * getWSMessageLangData method is used to get data records from ws message lang table.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are comma seperated values.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $assoc_field lang_assoc is to differ assoc data or normal data.
     * @return array $data_arr returns ws message lang data records array.
     */
    public function getWSMessageLangData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $assoc_field = '')
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select("mwml.*");
        }
        $this->db->from("mod_ws_messages_lang AS mwml");

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif (is_numeric($extra_cond)) {
            $this->db->where("mwml.iWSMessageId", intval($extra_cond));
        } elseif ($extra_cond != '') {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere("mod_ws_messages_lang", "mwml", "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        if ($assoc_field != '' && is_array($data_arr) && count($data_arr) > 0) {
            $final_array = array();
            foreach ($data_arr as $key => $data) {
                $final_array[$data[$assoc_field]][] = $data;
            }
            $data_arr = $final_array;
        }
        return $data_arr;
    }

    /**
     * getLanguageData method is used to get data records from language table.
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * @param string $fields fields are comma seperated values.
     * @param string $order_by order_by is to append order by condition.
     * @param string $group_by group_by is to append group by condition.
     * @param string $limit limit is to append limit condition.
     * @param string $join join is to make joins with relation tables.
     * @param boolean $assoc_field lang_assoc is to differ assoc data or normal data.
     * @return array $data_arr returns lang data records array.
     */
    public function getLanguageData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $join = "No", $assoc_field = '')
    {
        if (is_array($fields)) {
            $this->listing->addSelectFields($fields);
        } elseif ($fields != "") {
            $this->db->select($fields);
        } else {
            $this->db->select("mll.*");
        }
        $this->db->from("mod_language AS mll");

        if (is_array($extra_cond) && count($extra_cond) > 0) {
            $this->listing->addWhereFields($extra_cond);
        } elseif (is_numeric($extra_cond)) {
            $this->db->where("mod_language.iLangId", intval($extra_cond));
        } elseif ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->general->getPhysicalRecordWhere("mod_language", "mll", "AR");
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($order_by != "") {
            $this->db->order_by($order_by);
        }
        if ($limit != "") {
            list($offset, $limit) = explode(",", $limit);
            $this->db->limit($offset, $limit);
        }
        $data_obj = $this->db->get();
        $data_arr = is_object($data_obj) ? $data_obj->result_array() : array();
        if ($assoc_field != '' && is_array($data_arr) && count($data_arr) > 0) {
            $final_array = array();
            foreach ($data_arr as $key => $data) {
                $final_array[$data[$assoc_field]][] = $data;
            }
            $data_arr = $final_array;
        }
        return $data_arr;
    }
}
