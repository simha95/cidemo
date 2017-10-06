<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Group Controller
 *
 * @category admin
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Group
 *
 * @class Group.php
 *
 * @path application\admin\user\controllers\Group.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 03.10.2017
 */

class Group extends Cit_Controller
{
    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created CIT Dev Team
     * @modified ---
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('group_model');
        $this->_request_params();
        $this->folder_name = "user";
        $this->module_name = "group";
        $this->mod_enc_url = $this->general->getGeneralEncryptList($this->folder_name, $this->module_name);
        $this->mod_enc_mode = $this->general->getCustomEncryptMode(TRUE);
        $this->module_config = array(
            'module_name' => $this->module_name,
            'folder_name' => $this->folder_name,
            'mod_enc_url' => $this->mod_enc_url,
            'mod_enc_mode' => $this->mod_enc_mode,
            'delete' => "No",
            'xeditable' => "No",
            'top_detail' => "No",
            "multi_lingual" => "No",
            "physical_data_remove" => "Yes",
            "workflow_modes" => array()
        );
        $this->dropdown_arr = array(
            "mgm_grouping_attr" => array(
                "type" => "table",
                "table_name" => "mod_admin_menu",
                "field_key" => "iAdminMenuId",
                "field_val" => array(
                    $this->db->protect("vMenuDisplay")
                ),
                "extra_cond" => "eStatus = ''Active''",
                "order_by" => "val asc",
                "default" => "Yes",
                "opt_group" => "Yes",
                "opt_group_field" => "iParentId",
            ),
            "mgm_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('GROUP_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('GROUP_INACTIVE')
                    )
                )
            )
        );
        $this->parMod = $this->params_arr["parMod"];
        $this->parID = $this->params_arr["parID"];
        $this->parRefer = array();
        $this->expRefer = array();

        $this->topRefer = array();
        $this->dropdown_limit = $this->config->item('ADMIN_DROPDOWN_LIMIT');
        $this->search_combo_limit = $this->config->item('ADMIN_SEARCH_COMBO_LIMIT');
        $this->switchto_limit = $this->config->item('ADMIN_SWITCH_DROPDOWN_LIMIT');
        $this->count_arr = array();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    public function _request_params()
    {
        $this->get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
        $this->post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize grid listing page.
     */
    public function index()
    {
        $params_arr = $this->params_arr;
        $extra_qstr = $extra_hstr = '';
        list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("group", array("List", "View", "Add", "Update", "Delete", "Export"), TRUE, TRUE);
        try
        {
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "group");

            $status_array = array(
                'Active',
                'Inactive',
            );
            $status_label = array(
                'js_lang_label.GROUP_ACTIVE',
                'js_lang_label.GROUP_INACTIVE',
            );

            $list_config = $this->group_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "group");
            $hide_admin_rec = $this->general->getAdminDataRecords($this->group_model->table_name);

            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            $render_arr = array(
                "hide_admin_rec" => $hide_admin_rec,
                'list_config' => $list_config,
                'count_arr' => $this->count_arr,
                'enc_loc_module' => $enc_loc_module,
                'status_array' => $status_array,
                'status_label' => $status_label,
                'view_access' => $view_access,
                'add_access' => $add_access,
                'edit_access' => $edit_access,
                'del_access' => $del_access,
                'expo_access' => $expo_access,
                'folder_name' => $this->folder_name,
                'module_name' => $this->module_name,
                'mod_enc_url' => $this->mod_enc_url,
                'mod_enc_mode' => $this->mod_enc_mode,
                'extra_qstr' => $extra_qstr,
                'extra_hstr' => $extra_hstr,
                'default_filters' => $this->group_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            $this->loadView("group_index");
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * listing method is used to load listing data records in json format.
     */
    public function listing()
    {
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rows = $params_arr['rows'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        $filters = $params_arr['filters'];
        if (!trim($sidx) && !trim($sord))
        {
            $sdef = 'Yes';
        }
        if ($this->general->allowStripSlashes())
        {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $list_config = $this->group_model->getListConfiguration();
        $form_config = $this->group_model->getFormConfiguration();
        $extra_cond = $this->group_model->extra_cond;
        $groupby_cond = $this->group_model->groupby_cond;
        $having_cond = $this->group_model->having_cond;
        $orderby_cond = $this->group_model->orderby_cond;

        $data_config = array();
        $data_config['page'] = $page;
        $data_config['rows'] = $rows;
        $data_config['sidx'] = $sidx;
        $data_config['sord'] = $sord;
        $data_config['sdef'] = $sdef;
        $data_config['filters'] = $filters;
        $data_config['module_config'] = $this->module_config;
        $data_config['list_config'] = $list_config;
        $data_config['form_config'] = $form_config;
        $data_config['dropdown_arr'] = $this->dropdown_arr;
        $data_config['extra_cond'] = $extra_cond;
        $data_config['group_by'] = $groupby_cond;
        $data_config['having_cond'] = $having_cond;
        $data_config['order_by'] = $orderby_cond;
        $data_recs = $this->group_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_GROUP_DATA_FOUND_C46_C46_C33');

        echo json_encode($data_recs);
        $this->skip_template_view();
    }

    /**
     * export method is used to export listing data records in csv or pdf formats.
     */
    public function export()
    {
        $this->filter->getModuleWiseAccess("group", "Export", TRUE);
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rowlimit = $params_arr['rowlimit'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        if (!trim($sidx) && !trim($sord))
        {
            $sdef = 'Yes';
        }
        $export_type = $params_arr['export_type'];
        $export_mode = $params_arr['export_mode'];
        $filters = $params_arr['filters'];
        if ($this->general->allowStripSlashes())
        {
            $filters = stripslashes($filters);
        }
        $filters = json_decode(base64_decode($filters), TRUE);
        $fields = json_decode(base64_decode($params_arr['fields']), TRUE);
        $list_config = $this->group_model->getListConfiguration();
        $form_config = $this->group_model->getFormConfiguration();
        $table_name = $this->group_model->table_name;
        $table_alias = $this->group_modeltable_alias;
        $primary_key = $this->group_model->primary_key;
        $extra_cond = $this->group_model->extra_cond;
        $groupby_cond = $this->group_model->groupby_cond;
        $having_cond = $this->group_model->having_cond;
        $orderby_cond = $this->group_model->orderby_cond;

        $export_config = array();
        $export_config['page'] = $page;
        $export_config['rowlimit'] = $rowlimit;
        $export_config['sidx'] = $sidx;
        $export_config['sord'] = $sord;
        $export_config['sdef'] = $sdef;
        $export_config['filters'] = $filters;
        $export_config['export_mode'] = $export_mode;
        $export_config['module_config'] = $this->module_config;
        $export_config['list_config'] = $list_config;
        $export_config['form_config'] = $form_config;
        $export_config['dropdown_arr'] = $this->dropdown_arr;
        $export_config['table_name'] = $table_name;
        $export_config['table_alias'] = $table_alias;
        $export_config['primary_key'] = $primary_key;
        $export_config['extra_cond'] = $extra_cond;
        $export_config['group_by'] = $groupby_cond;
        $export_config['having_cond'] = $having_cond;
        $export_config['order_by'] = $orderby_cond;
        $db_recs = $this->group_model->getExportData($export_config);
        $db_recs = $this->listing->getDataForList($db_recs, $export_config, "GExport", array());
        if (!is_array($db_recs) || count($db_recs) == 0)
        {
            $this->session->set_flashdata('failure', $this->general->processMessageLabel('GENERIC_GRID_NO_RECORDS_TO_PROCESS'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        $heading = "Group";
        $filename = "Group_".count($db_recs)."_Records";
        $tot_fields_arr = array_keys($db_recs[0]);
        if ($export_mode == "all" && is_array($tot_fields_arr))
        {
            if (($pr_key = array_search($primary_key, $tot_fields_arr)) !== FALSE)
            {
                unset($tot_fields_arr[$pr_key]);
            }
            $fields = array_values($tot_fields_arr);
        }
        $numberOfColumns = count($fields);
        if ($export_type == 'pdf')
        {
            $pdf_style = "TCPDF";
            $columns = $aligns = $widths = $data = array();
            //Table headers info
            for ($i = 0; $i < $numberOfColumns; $i++)
            {
                $size = 10;
                $position = '';
                if (array_key_exists($fields[$i], $list_config))
                {
                    $label = $list_config[$fields[$i]]['label_lang'];
                    $position = $list_config[$fields[$i]]['align'];
                    $size = $list_config[$fields[$i]]['width'];
                }
                elseif (array_key_exists($fields[$i], $form_config))
                {
                    $label = $form_config[$fields[$i]]['label_lang'];
                }
                else
                {
                    $label = $fields[$i];
                }
                $columns[] = $label;
                $aligns[] = in_array($position, array('right', 'center')) ? $position : "left";
                $widths[] = $size;
            }

            //Table data info
            $db_rec_cnt = count($db_recs);
            for ($i = 0; $i < $db_rec_cnt; $i++)
            {
                foreach ((array) $db_recs[$i] as $key => $val)
                {
                    if (is_array($fields) && in_array($key, $fields))
                    {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "pdf", $pdf_style);
                    }
                }
            }

            require_once ($this->config->item('third_party').'Pdf_export.php');
            $pdf = new PDF_Export(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
            $pdf->initialize($heading);

            $pdf->writeGridTable($columns, $data, $widths, $aligns);
            $pdf->Output($filename.".pdf", 'D');
        }
        elseif ($export_type == 'csv')
        {
            require_once ($this->config->item('third_party').'Csv_export.php');
            $columns = $data = array();

            for ($i = 0; $i < $numberOfColumns; $i++)
            {
                if (array_key_exists($fields[$i], $list_config))
                {
                    $label = $list_config[$fields[$i]]['label_lang'];
                }
                elseif (array_key_exists($fields[$i], $form_config))
                {
                    $label = $form_config[$fields[$i]]['label_lang'];
                }
                else
                {
                    $label = $fields[$i];
                }
                $columns[] = $label;
            }
            $db_recs_cnt = count($db_recs);
            for ($i = 0; $i < $db_recs_cnt; $i++)
            {
                foreach ((array) $db_recs[$i] as $key => $val)
                {
                    if (is_array($fields) && in_array($key, $fields))
                    {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "csv");
                    }
                }
            }
            $export_array = array_merge(array($columns), $data);
            $csv = new CSV_Writer($export_array);
            $csv->headers($filename);
            $csv->output();
        }
        $this->skip_template_view();
    }

    /**
     * add method is used to add or update data records.
     */
    public function add()
    {
        $params_arr = $this->params_arr;
        $extra_qstr = $extra_hstr = '';
        $hideCtrl = $params_arr['hideCtrl'];
        $showDetail = $params_arr['showDetail'];
        $mode = (in_array($params_arr['mode'], array("Update", "View"))) ? "Update" : "Add";
        $viewMode = ($params_arr['mode'] == "View") ? TRUE : FALSE;
        $id = $params_arr['id'];
        $enc_id = $this->general->getAdminEncodeURL($id);
        try
        {
            $extra_cond = $this->group_model->extra_cond;
            if ($mode == "Update")
            {
                list($list_access, $view_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("group", array("List", "View", "Update", "Delete", "Export"), TRUE, TRUE);
                if ($this->session->userdata("iAdminId") == $id)
                {
                    $user_allow = FALSE;
                    if (!$edit_access)
                    {
                        $user_allow = $edit_access = TRUE;
                    }
                    else
                    {
                        if ($params_arr["tEditFP"] == "true")
                        {
                            $user_allow = TRUE;
                        }
                    }
                }
                if (!$edit_access && !$view_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            }
            else
            {
                list($list_access, $add_access, $del_access) = $this->filter->getModuleWiseAccess("group", array("List", "Add", "Delete"), TRUE, TRUE);
                if (!$add_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $func = array();
            if ($mode == 'Update')
            {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "group"), $this->session->userdata('iAdminId'));
                $data_arr = $this->group_model->getData(intval($id));
                $data = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $switch_arr = $this->group_model->getSwitchTo($extra_cond, "records", $this->switchto_limit);
                $switch_combo = $this->filter->makeArrayDropDown($switch_arr);
                $switch_cit = array();
                $switch_tot = $this->group_model->getSwitchTo($extra_cond, "count");
                if ($this->switchto_limit > 0 && $switch_tot > $this->switchto_limit)
                {
                    $switch_cit['param'] = "true";
                    $switch_cit['url'] = $this->mod_enc_url['get_self_switch_to'];
                    if (!array_key_exists($id, $switch_combo))
                    {
                        $extra_cond = $this->db->protect($this->group_model->table_alias.".".$this->group_model->primary_key)." = ".$this->db->escape($id);
                        $switch_cur = $this->group_model->getSwitchTo($extra_cond, "records", 1);
                        if (is_array($switch_cur) && count($switch_cur) > 0)
                        {
                            $switch_combo[$switch_cur[0]['id']] = $switch_cur[0]['val'];
                        }
                    }
                }
                $recName = $switch_combo[$id];
                $switch_enc_combo = $this->filter->getSwitchEncryptRec($switch_combo);
                $this->dropdown->combo("array", "vSwitchPage", $switch_enc_combo, $enc_id);
                $next_prev_records = $this->filter->getNextPrevRecords($id, $switch_arr);
                $hide_admin_rec = $this->general->isAdminDataRecord($id, "Update", $this->group_model->table_name, "vUserName");
                $hide_del_status = $this->general->isAdminDataRecord($id, "Delete", $this->group_model->table_name);
                $del_access = ($hide_del_status["success"]) ? FALSE : $del_access;
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "group", $recName);
            }
            else
            {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "group"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "group");
            }
            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $form_config = $this->group_model->getFormConfiguration($config_arr);
            if (is_array($form_config) && count($form_config) > 0)
            {
                foreach ($form_config as $key => $val)
                {
                    if ($params_arr['rmPopup'] == "true" && $params_arr[$key] != "")
                    {
                        $data[$key] = $params_arr[$key];
                    }
                    elseif ($val["dfapply"] != "")
                    {
                        if ($val["dfapply"] == "forceApply" || $val["entry_type"] == "Custom")
                        {
                            $data[$key] = $val['default'];
                        }
                        elseif ($val["dfapply"] == "addOnly")
                        {
                            if ($mode == "Add")
                            {
                                $data[$key] = $val['default'];
                            }
                        }
                        elseif ($val["dfapply"] == "everyUpdate")
                        {
                            if ($mode == "Update")
                            {
                                $data[$key] = $val['default'];
                            }
                        }
                        else
                        {
                            $data[$key] = (trim($data[$key]) != "") ? $data[$key] : $val['default'];
                        }
                    }
                    if ($val['encrypt'] == "Yes")
                    {
                        $data[$key] = $this->general->decryptDataMethod($data[$key], $val["enctype"]);
                    }
                    if ($val['function'] != "")
                    {
                        $fnctype = $val['functype'];
                        $phpfunc = $val['function'];
                        $tmpdata = '';
                        if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE)
                        {
                            $phpfunc = substr($phpfunc, 12);
                            if (method_exists($this, $phpfunc))
                            {
                                $tmpdata = $this->$phpfunc($mode, $data[$key], $data, $id, $key, $key);
                            }
                        }
                        elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                        {
                            $phpfunc = substr($phpfunc, 7);
                            if (method_exists($this->group_model, $phpfunc))
                            {
                                $tmpdata = $this->group_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key);
                            }
                        }
                        elseif (method_exists($this->general, $phpfunc))
                        {
                            $tmpdata = $this->general->$phpfunc($mode, $data[$key], $data, $id, $key, $key);
                        }
                        if ($fnctype == "input" || $fnctype == "status")
                        {
                            $func[$key] = $tmpdata;
                        }
                        else
                        {
                            $data[$key] = $tmpdata;
                        }
                    }
                    $source_field = $val['name'];
                    $combo_config = $this->dropdown_arr[$source_field];
                    if (is_array($combo_config) && count($combo_config) > 0)
                    {
                        if ($combo_config['auto'] == "Yes")
                        {
                            $combo_count = $this->getSourceOptions($source_field, $mode, $id, $data, '', 'count');
                            if ($combo_count[0]['tot'] > $this->dropdown_limit)
                            {
                                $auto_arr[$source_field] = "Yes";
                            }
                        }
                        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data);
                        $final_arr = $this->filter->makeArrayDropdown($combo_arr);
                        if ($combo_config['opt_group'] == "Yes")
                        {
                            $display_arr = $this->filter->makeOPTDropdown($combo_arr);
                        }
                        else
                        {
                            $display_arr = $final_arr;
                        }
                        $this->dropdown->combo("array", $source_field, $display_arr, $data[$key]);
                        $opt_arr[$source_field] = $final_arr;
                    }
                }
            }
            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            if ($mode == 'Update')
            {
                $extra_right_cond = $this->db->protect("iGroupId")." = ".$this->db->escape($id);
                $db_group_rights = $this->group_model->getGroupRights($extra_right_cond, '', '', '', '', 'iAdminMenuId');
            }
            if ($data['mgm_group_code'] == $this->config->item("ADMIN_GROUP_NAME"))
            {
                $is_admin_group = TRUE;
            }
            else
            {
                $is_admin_group = FALSE;
            }
            $action_arr = array(
                "eList" => "List",
                "eView" => "View",
                "eAdd" => "Add",
                "eUpdate" => "Update",
                "eDelete" => "Delete",
                "eExport" => "Export",
                "ePrint" => "Print",
            );
            $extra_parent_cond = $this->db->protect("iParentId")." = 0 AND ".$this->db->protect("eStatus")." IN ('Active', 'Hidden')";
            $db_parent_menus = $this->group_model->systemMenus($extra_parent_cond, "", "iSequenceOrder ASC");
            $extra_child_cond = $this->db->protect("iParentId")." <> 0 AND ".$this->db->protect("eStatus")." IN ('Active', 'Hidden')";
            $db_child_assoc_menus = $this->group_model->systemMenus($extra_child_cond, "", "iSequenceOrder asc", "", "", "iParentId");
            $menu_items_opt = array();
            $db_parent_menus_cnt = count($db_parent_menus);
            for ($i = 0; $i < $db_parent_menus_cnt; $i++)
            {
                $paretn_id = $db_parent_menus[$i]['iAdminMenuId'];
                $parent_name = $db_parent_menus[$i]['vMenuDisplay'];
                $childern_arr = $db_child_assoc_menus[$paretn_id];
                if (is_array($childern_arr) && count($childern_arr) > 0)
                {
                    $opt_items_arr = array();
                    $childern_arr_cnt = count($childern_arr);
                    for ($j = 0; $j < $childern_arr_cnt; $j++)
                    {
                        $children_id = $childern_arr[$j]['iAdminMenuId'];
                        $children_name = $childern_arr[$j]['vMenuDisplay'];
                        $opt_items_arr[$children_id] = $children_name;
                    }
                    $menu_items_opt[$parent_name] = $opt_items_arr;
                }
            }
            $grouping_exists = FALSE;
            if ($mode == "Update")
            {
                $landing_page_arr = unserialize($data['mgm_grouping_attr']);
                if (is_array($landing_page_arr) && count($landing_page_arr) > 0)
                {
                    $data['mgm_grouping_attr'] = $landing_page_arr;
                    $data['mgm_grouping_attr_selected'] = trim($landing_page_arr['phpFunc'] != '') ? $landing_page_arr['phpFunc'] : $landing_page_arr['menuItem'];
                    $grouping_exists = TRUE;
                }
            }
            if (!$grouping_exists)
            {
                $data['mgm_grouping_attr'] = array(
                    "phpFunc" => '',
                    'menuItem' => '',
                );
                $data['mgm_grouping_attr_selected'] = '';
            }
            $this->dropdown->combo("array", "mgm_grouping_attr", $menu_items_opt, $data["mgm_grouping_attr_selected"]);

            /** access controls <<< **/
            $controls_allow = $prev_link_allow = $next_link_allow = $update_allow = $delete_allow = $backlink_allow = $switchto_allow = $discard_allow = $tabing_allow = TRUE;
            if (!$del_access || $this->module_config["delete"] == "Yes")
            {
                $delete_allow = FALSE;
            }
            if (is_array($switch_combo) && count($switch_combo) > 0)
            {
                $prev_link_allow = ($next_prev_records['prev']['id'] != '') ? TRUE : FALSE;
                $next_link_allow = ($next_prev_records['next']['id'] != '') ? TRUE : FALSE;
            }
            else
            {
                $prev_link_allow = $next_link_allow = $switchto_allow = FALSE;
            }
            if (!$list_access)
            {
                $backlink_allow = $discard_allow = FALSE;
            }
            if ($hideCtrl == "true")
            {
                $controls_allow = $prev_link_allow = $next_link_allow = $delete_allow = $backlink_allow = $switchto_allow = $tabing_allow = FALSE;
            }
            if ($user_allow)
            {
                $controls_allow = $prev_link_allow = $next_link_allow = $delete_allow = $backlink_allow = $switchto_allow = $discard_allow = $tabing_allow = FALSE;
            }
            /** access controls >>> **/
            $render_arr = array(
                "hide_del_status" => $hide_del_status["success"],
                "hide_admin_rec" => $hide_admin_rec["success"],
                'db_parent_menus' => $db_parent_menus,
                'db_child_assoc_menus' => $db_child_assoc_menus,
                'action_arr' => $action_arr,
                'db_group_rights' => $db_group_rights,
                'is_admin_group' => $is_admin_group,
                "edit_access" => $edit_access,
                "expo_access" => $expo_access,
                'controls_allow' => $controls_allow,
                'prev_link_allow' => $prev_link_allow,
                'next_link_allow' => $next_link_allow,
                'update_allow' => $update_allow,
                'delete_allow' => $delete_allow,
                'backlink_allow' => $backlink_allow,
                'switchto_allow' => $switchto_allow,
                'discard_allow' => $discard_allow,
                'tabing_allow' => $tabing_allow,
                'enc_id' => $enc_id,
                'id' => $id,
                'mode' => $mode,
                'data' => $data,
                'func' => $func,
                'recName' => $recName,
                "opt_arr" => $opt_arr,
                "img_html" => $img_html,
                "auto_arr" => $auto_arr,
                'ctrl_flow' => $ctrl_flow,
                'switch_combo' => $switch_combo,
                'switch_cit' => $switch_cit,
                'next_prev_records' => $next_prev_records,
                'folder_name' => $this->folder_name,
                'module_name' => $this->module_name,
                'mod_enc_url' => $this->mod_enc_url,
                'mod_enc_mode' => $this->mod_enc_mode,
                'extra_qstr' => $extra_qstr,
                'extra_hstr' => $extra_hstr,
            );
            $this->smarty->assign($render_arr);
            if ($mode == "Update")
            {
                if ($edit_access && $viewMode != TRUE)
                {
                    $this->loadView("group_add_custom");
                }
                else
                {
                    $this->loadView("group_add_custom_view");
                }
            }
            else
            {
                $this->loadView("group_add_custom");
            }
        }
        catch(Exception $e)
        {
            $render_arr['err_message'] = $e->getMessage();
            $this->smarty->assign($render_arr);
            $this->loadView($this->config->item('ADMIN_FORBIDDEN_TEMPLATE'));
        }
    }

    /**
     * addAction method is used to save data, which is posted through form.
     */
    public function addAction()
    {
        $params_arr = $this->params_arr;
        $mode = ($params_arr['mode'] == "Update") ? "Update" : "Add";
        $id = $params_arr['id'];
        try
        {
            $add_edit_access = $this->filter->getModuleWiseAccess("group", $mode, TRUE, TRUE);
            if ($this->session->userdata("iAdminId") == $id)
            {
                if (!$add_edit_access)
                {
                    $add_edit_access = TRUE;
                }
            }
            if (!$add_edit_access)
            {
                if ($mode == "Update")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $form_config = $this->group_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $mgm_group_name = $params_arr["mgm_group_name"];
            $mgm_group_code = $params_arr["mgm_group_code"];
            $mgm_grouping_attr = $params_arr["mgm_grouping_attr"];
            $mgm_status = $params_arr["mgm_status"];

            $unique_arr = array();
            $unique_arr["vGroupCode"] = $mgm_group_code;

            $unique_exists = $this->group_model->checkRecordExists($this->group_model->unique_fields, $unique_arr, $id, $mode, $this->group_model->unique_type);
            if ($unique_exists)
            {
                $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_GROUP_CODE_C46_C46_C33');
                if ($error_msg == "")
                {
                    $error_msg = "Record already exists with these details of Group Code";
                }
                throw new Exception($error_msg);
            }
            $data = $save_data_arr = $file_data = array();
            $data["vGroupName"] = $mgm_group_name;
            $data["vGroupCode"] = $mgm_group_code;
            $data["vGroupingAttr"] = $mgm_grouping_attr;
            $data["eStatus"] = $mgm_status;

            $save_data_arr["mgm_group_name"] = $data["vGroupName"];
            $save_data_arr["mgm_group_code"] = $data["vGroupCode"];
            $save_data_arr["mgm_grouping_attr"] = $data["vGroupingAttr"];
            $save_data_arr["mgm_status"] = $data["eStatus"];

            $mgm_grouping_attr = $params_arr["mgm_grouping_attr"];
            $mgm_group_func = $params_arr["mgm_grouping_func"];
            $landing_page_arr['menuItem'] = $mgm_grouping_attr;
            $landing_page_arr['phpFunc'] = $mgm_group_func;
            $data['vGroupingAttr'] = serialize($landing_page_arr);
            if ($mode == 'Add')
            {
                $id = $this->group_model->insert($data);
                if (intval($id) > 0)
                {
                    $save_data_arr["iGroupId"] = $data["iGroupId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mgm.iGroupId")." = ".$this->db->escape($id);
                $switch_combo = $this->group_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "group", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            elseif ($mode == 'Update')
            {
                $res = $this->group_model->update($data, intval($id));
                if (intval($res) > 0)
                {
                    $save_data_arr["iGroupId"] = $data["iGroupId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mgm.iGroupId")." = ".$this->db->escape($id);
                $switch_combo = $this->group_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "group", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;
            $db_total_menus = $this->group_model->systemMenus('', '', 'vMenuDisplay ASC');
            $this->group_model->deleteGroupRights($id);
            $insert_rights_arr = array();

            $list_arr = $params_arr['eList'];
            $view_arr = $params_arr['eView'];
            $add_arr = $params_arr['eAdd'];
            $update_arr = $params_arr['eUpdate'];
            $delete_arr = $params_arr['eDelete'];
            $export_arr = $params_arr['eExport'];
            $print_arr = $params_arr['ePrint'];
            if (is_array($db_total_menus) && count($db_total_menus) > 0)
            {
                foreach ((array) $db_total_menus as $key => $val)
                {
                    $admin_menu_id = $val['iAdminMenuId'];
                    $e_list = ($list_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_view = ($view_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_add = ($add_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_update = ($update_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_delete = ($delete_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_export = ($export_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    $e_print = ($print_arr[$admin_menu_id] == "Yes") ? "Yes" : "No";
                    if ($e_list == "Yes" || $e_view == "Yes" || $e_add == "Yes" || $e_update == "Yes" || $e_delete == "Yes" || $e_export == "Yes" || $e_print == "Yes")
                    {
                        $insert_rights_arr['iGroupId'] = $id;
                        $insert_rights_arr['iAdminMenuId'] = $admin_menu_id;
                        $insert_rights_arr['eList'] = $e_list;
                        $insert_rights_arr['eView'] = $e_view;
                        $insert_rights_arr['eAdd'] = $e_add;
                        $insert_rights_arr['eUpdate'] = $e_update;
                        $insert_rights_arr['eDelete'] = $e_delete;
                        $insert_rights_arr['eExport'] = $e_export;
                        $insert_rights_arr['ePrint'] = $e_print;
                        $this->group_model->insertGroupRights($insert_rights_arr);
                    }
                }
            }

            $params_arr = $this->_request_params();
        }
        catch(Exception $e)
        {
            $ret_arr['message'] = $e->getMessage();
            $ret_arr['success'] = 0;
        }
        $ret_arr['mod_enc_url']['add'] = $this->mod_enc_url['add'];
        $ret_arr['mod_enc_url']['index'] = $this->mod_enc_url['index'];
        $ret_arr['red_type'] = 'List';
        $this->filter->getPageFlowURL($ret_arr, $this->module_config, $params_arr, $id, $data);

        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * inlineEditAction method is used to save inline editing data records, status field updation,
     * delete records either from grid listing or update form, saving inline adding records from grid
     */
    public function inlineEditAction()
    {
        $params_arr = $this->params_arr;
        $operartor = $params_arr['oper'];
        $all_row_selected = $params_arr['AllRowSelected'];
        $primary_ids = explode(",", $params_arr['id']);
        $primary_ids = count($primary_ids) > 1 ? $primary_ids : $primary_ids[0];
        $filters = $params_arr['filters'];
        if ($this->general->allowStripSlashes())
        {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $extra_cond = '';
        $search_mode = $search_join = $search_alias = 'No';
        $extra_cond = $this->general->getAdminExtraCondtion($this->group_model->table_name, $this->group_model->table_alias);
        $search_alias = 'Yes';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status")))
        {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->group_model->getListConfiguration();
            $config_arr['form_config'] = $this->group_model->getFormConfiguration();
            $config_arr['table_name'] = $this->group_model->table_name;
            $config_arr['table_alias'] = $this->group_model->table_alias;
            $filter_main = $this->filter->applyFilter($filters, $config_arr, $search_mode);
            $filter_left = $this->filter->applyLeftFilter($filters, $config_arr, $search_mode);
            $filter_range = $this->filter->applyRangeFilter($filters, $config_arr, $search_mode);
            if ($filter_main != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_main.")" : $filter_main;
            }
            if ($filter_left != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_left.")" : $filter_left;
            }
            if ($filter_range != "")
            {
                $extra_cond .= ($extra_cond != "") ? " AND (".$filter_range.")" : $filter_range;
            }
        }
        if ($search_alias == "Yes")
        {
            $primary_field = $this->group_model->table_alias.".".$this->group_model->primary_key;
        }
        else
        {
            $primary_field = $this->group_model->primary_key;
        }
        if (is_array($primary_ids))
        {
            $pk_condition = $this->db->protect($primary_field)." IN ('".implode("','", $primary_ids)."')";
        }
        elseif (intval($primary_ids) > 0)
        {
            $pk_condition = $this->db->protect($primary_field)." = ".$this->db->escape($primary_ids);
        }
        else
        {
            $pk_condition = FALSE;
        }
        if ($pk_condition)
        {
            $extra_cond .= ($extra_cond != "") ? " AND (".$pk_condition.")" : $pk_condition;
        }
        $data_arr = $save_data_arr = array();
        try
        {
            switch ($operartor)
            {
                case 'del':
                    $mode = "Delete";

                    $del_access = $this->filter->getModuleWiseAccess("group", "Delete", TRUE, TRUE);
                    if (!$del_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_DELETE_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $params_arr = $this->_request_params();
                    if ($search_mode == 'No' && !is_array($primary_ids))
                    {
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Delete', $this->group_model->table_name);
                        if ($res_arr['success'])
                        {
                            if (intval($res_arr['success']) == 2)
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_YOUR_SELF_C46_C46_C33'));
                            }
                            else
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_ADMIN_DATA_C46_C46_C33'));
                            }
                        }
                    }
                    $success = $this->group_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    break;
                case 'edit':
                    $mode = "Update";
                    $edit_access = $this->filter->getModuleWiseAccess("group", "Update", TRUE, TRUE);
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->group_model->getListConfiguration($post_name);
                    $form_config = $this->group_model->getFormConfiguration($list_config['source_field']);
                    if (!is_array($form_config) || count($form_config) == 0)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if (in_array($form_config['type'], array("date", "date_and_time", "time", 'phone_number')))
                    {
                        $post_val = $this->filter->formatActionData($post_val, $form_config);
                    }
                    if ($form_config["encrypt"] == "Yes")
                    {
                        $post_val = $this->general->encryptDataMethod($post_val, $form_config["enctype"]);
                    }
                    $field_name = $form_config['field_name'];
                    $unique_name = $form_config['name'];
                    if ($search_mode == 'No' && !is_array($primary_ids))
                    {
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Update', $this->group_model->table_name, $field_name);
                        if ($res_arr['success'])
                        {
                            if (intval($res_arr['success']) == 2)
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_YOUR_SELF_C46_C46_C33'));
                            }
                            else
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_ADMIN_DATA_C46_C46_C33'));
                            }
                        }
                    }

                    $unique_arr = array();

                    $unique_arr[$field_name] = $post_val;
                    if (in_array($field_name, $this->group_model->unique_fields))
                    {
                        $unique_exists = $this->group_model->checkRecordExists($this->group_model->unique_fields, $unique_arr, $primary_ids, "Update", $this->group_model->unique_type);
                        if ($unique_exists)
                        {
                            $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_GROUP_CODE_C46_C46_C33');
                            if ($error_msg == "")
                            {
                                $error_msg = "Record already exists with these details of Group Code";
                            }
                            throw new Exception($error_msg);
                        }
                    }

                    $data_arr[$field_name] = $post_val;
                    $success = $this->group_model->update($data_arr, intval($primary_ids));
                    $message = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                    }
                    break;
                case 'status':
                    $mode = "Status";
                    $edit_access = $this->filter->getModuleWiseAccess("group", "Update", TRUE, TRUE);
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $status_field = "eStatus";
                    if ($status_field == "")
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FORM_CONFIGURING_NOT_DONE_C46_C46_C33'));
                    }
                    if ($search_mode == 'No' && !is_array($primary_ids))
                    {
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Update', $this->group_model->table_name, $status_field);
                        if ($res_arr['success'])
                        {
                            if (intval($res_arr['success']) == 2)
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_YOUR_SELF_C46_C46_C33'));
                            }
                            else
                            {
                                throw new Exception($this->general->processMessageLabel('ACTION_CAN_NOT_UPDATE_ADMIN_DATA_C46_C46_C33'));
                            }
                        }
                    }
                    if ($search_mode == "Yes" || $search_alias == "Yes")
                    {
                        $field_name = $this->group_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->group_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_MODIFYING_THESE_RECORDS_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_MODIFIED_SUCCESSFULLY_C46_C46_C33');
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
        }
        catch(Exception $e)
        {
            $ret_arr['success'] = "false";
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    /**
     * processConfiguration method is used to process add and edit permissions for grid intialization
     */
    protected function processConfiguration(&$list_config = array(), $isAdd = TRUE, $isEdit = TRUE, $runCombo = FALSE)
    {
        if (!is_array($list_config) || count($list_config) == 0)
        {
            return $list_config;
        }
        $count_arr = array();
        foreach ((array) $list_config as $key => $val)
        {
            if (!$isAdd)
            {
                $list_config[$key]["addable"] = "No";
            }
            if (!$isEdit)
            {
                $list_config[$key]["editable"] = "No";
            }

            $source_field = $val['source_field'];
            $dropdown_arr = $this->dropdown_arr[$source_field];
            if (is_array($dropdown_arr) && in_array($val['type'], array("dropdown", "radio_buttons", "checkboxes", "multi_select_dropdown")))
            {
                $count_arr[$key]['ajax'] = "No";
                $count_arr[$key]['json'] = "No";
                $count_arr[$key]['data'] = array();
                $combo_arr = FALSE;
                if ($dropdown_arr['auto'] == "Yes")
                {
                    $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                    if ($combo_arr[0]['tot'] > $this->dropdown_limit)
                    {
                        $count_arr[$key]['ajax'] = "Yes";
                    }
                }
                if ($runCombo == TRUE)
                {
                    if (in_array($dropdown_arr['type'], array("enum", "phpfn")))
                    {
                        $data_arr = $this->getSourceOptions($source_field, "Search");
                        $json_arr = $this->filter->makeArrayDropdown($data_arr);
                        $count_arr[$key]['json'] = "Yes";
                        $count_arr[$key]['data'] = json_encode($json_arr);
                    }
                    else
                    {
                        if ($dropdown_arr['opt_group'] != "Yes")
                        {
                            if ($combo_arr == FALSE)
                            {
                                $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                            }
                            if ($combo_arr[0]['tot'] < $this->search_combo_limit)
                            {
                                $data_arr = $this->getSourceOptions($source_field, "Search");
                                $json_arr = $this->filter->makeArrayDropdown($data_arr);
                                $count_arr[$key]['json'] = "Yes";
                                $count_arr[$key]['data'] = json_encode($json_arr);
                            }
                        }
                    }
                }
            }
        }
        $this->count_arr = $count_arr;
        return $list_config;
    }

    /**
     * getSourceOptions method is used to get data array of enum, table, token or php function input types
     * @param string $name unique name of form configuration field.
     * @param string $mode mode for add or update form.
     * @param string $id update record id of add or update form.
     * @param array $data data array of add or update record.
     * @param string $extra extra query condition for searching data array.
     * @param string $rtype type for getting either records list or records count.
     * @return array $data_arr returns data records array
     */
    public function getSourceOptions($name = '', $mode = 'Add', $id = '', $data = array(), $extra = '', $rtype = 'records')
    {
        $combo_config = $this->dropdown_arr[$name];
        $data_arr = array();
        if (!is_array($combo_config) || count($combo_config) == 0)
        {
            return $data_arr;
        }
        $type = $combo_config['type'];
        switch ($type)
        {
            case 'enum':
                $data_arr = is_array($combo_config['values']) ? $combo_config['values'] : array();
                break;
            case 'token':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field)." IN ('".implode("','", $parent_src)."')";
                    }
                    elseif ($mode == "Add")
                    {
                        $extra_cond = $this->db->protect($target_field)." = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                }
                $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                break;
            case 'table':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field)." IN ('".implode("','", $parent_src)."')";
                    }
                    elseif ($mode == "Add")
                    {
                        $extra_cond = $this->db->protect($target_field)." = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                }
                if ($combo_config['parent_child'] == "Yes" && $combo_config['nlevel_child'] == "Yes")
                {
                    $combo_config['main_table'] = $this->group_model->table_name;
                    $data_arr = $this->filter->getTreeLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                else
                {
                    if ($combo_config['parent_child'] == "Yes" && $combo_config['parent_field'] != "")
                    {
                        $parent_field = $combo_config['parent_field'];
                        $extra_cond = "(".$this->db->protect($parent_field)." = '0' OR ".$this->db->protect($parent_field)." = '' OR ".$this->db->protect($parent_field)." IS NULL )";
                        if ($mode == "Update" || ($mode == "Search" && $id > 0))
                        {
                            $extra_cond .= " AND ".$this->db->protect($combo_config['field_key'])." <> ".$this->db->escape($id);
                        }
                        $extra = (trim($extra) != "") ? $extra." AND ".$extra_cond : $extra_cond;
                    }
                    $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                break;
            case 'phpfn':
                $phpfunc = $combo_config['function'];
                $parent_src = '';
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto")))
                {
                    $source_field = $combo_config['source_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "")
                    {
                        $parent_src = $data[$source_field];
                    }
                }
                if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE)
                {
                    $phpfunc = substr($phpfunc, 12);
                    if (method_exists($this, $phpfunc))
                    {
                        $data_arr = $this->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                }
                elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                {
                    $phpfunc = substr($phpfunc, 7);
                    if (method_exists($this->group_model, $phpfunc))
                    {
                        $data_arr = $this->group_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                    }
                }
                elseif (method_exists($this->general, $phpfunc))
                {
                    $data_arr = $this->general->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
                }
                break;
        }
        return $data_arr;
    }

    /**
     * getSelfSwitchToPrint method is used to provide autocomplete for switchto dropdown, which is called through form.
     */
    public function getSelfSwitchTo()
    {
        $params_arr = $this->params_arr;

        $term = strtolower($params_arr['data']['q']);

        $switchto_fields = $this->group_model->switchto_fields;
        $extra_cond = $this->group_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond." AND ".$search_cond;

        $switch_arr = $this->group_model->getSwitchTo($extra_cond);
        $html_arr = $this->filter->getChosenAutoJSON($switch_arr, array(), FALSE, "auto");

        $json_array['q'] = $term;
        $json_array['results'] = $html_arr;
        $html_str = json_encode($json_array);

        echo $html_str;
        $this->skip_template_view();
    }

    /**
     * getListOptions method is used to get  dropdown values searching or inline editing in grid listing (select options in html or json string)
     */
    public function getListOptions()
    {
        $params_arr = $this->params_arr;
        $alias_name = $params_arr['alias_name'];
        $rformat = $params_arr['rformat'];
        $id = $params_arr['id'];
        $mode = ($params_arr['mode'] == "Search") ? "Search" : (($params_arr['mode'] == "Update") ? "Update" : "Add");
        $config_arr = $this->group_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update")
        {
            $data_arr = $this->group_model->getData(intval($id));
        }
        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data_arr[0]);
        if ($rformat == "json")
        {
            $html_str = $this->filter->getChosenAutoJSON($combo_arr, $combo_config, TRUE, "grid");
        }
        else
        {
            if ($combo_config['opt_group'] == "Yes")
            {
                $combo_arr = $this->filter->makeOPTDropdown($combo_arr);
            }
            else
            {
                $combo_arr = $this->filter->makeArrayDropdown($combo_arr);
            }
            $this->dropdown->combo("array", $source_field, $combo_arr, $id);
            $top_option = (in_array($mode, array("Add", "Update")) && $combo_config['default'] == 'Yes') ? "|||" : '';
            $html_str = $this->dropdown->display($source_field, $source_field, ' multiple=true ', $top_option);
        }
        echo $html_str;
        $this->skip_template_view();
    }
}
