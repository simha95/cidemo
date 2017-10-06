<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Admin Controller
 *
 * @category admin
 *
 * @package user
 *
 * @subpackage controllers
 *
 * @module Admin
 *
 * @class Admin.php
 *
 * @path application\admin\user\controllers\Admin.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 03.10.2017
 */

class Admin extends Cit_Controller
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
        $this->load->model('admin_model');
        $this->_request_params();
        $this->folder_name = "user";
        $this->module_name = "admin";
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
            "ma_group_id" => array(
                "type" => "table",
                "table_name" => "mod_group_master",
                "field_key" => "iGroupId",
                "field_val" => array(
                    $this->db->protect("vGroupName")
                ),
                "order_by" => "val asc",
                "default" => "Yes",
            ),
            "ma_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('ADMIN_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('ADMIN_INACTIVE')
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
        list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("admin", array("List", "View", "Add", "Update", "Delete", "Export"), TRUE, TRUE);
        try
        {
            if (!$list_access)
            {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "admin");

            $status_array = array(
                'Active',
                'Inactive',
            );
            $status_label = array(
                'js_lang_label.ADMIN_ACTIVE',
                'js_lang_label.ADMIN_INACTIVE',
            );

            $list_config = $this->admin_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "admin");
            $hide_admin_rec = $this->general->getAdminDataRecords($this->admin_model->table_name);

            $search_arr = $this->getLeftSearchContent();

            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            $render_arr = array(
                "hide_admin_rec" => $hide_admin_rec,
                "search_arr" => $search_arr,
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
                'default_filters' => $this->admin_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            $this->loadView("admin_index");
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
        $list_config = $this->admin_model->getListConfiguration();
        $form_config = $this->admin_model->getFormConfiguration();
        $extra_cond = $this->admin_model->extra_cond;
        $groupby_cond = $this->admin_model->groupby_cond;
        $having_cond = $this->admin_model->having_cond;
        $orderby_cond = $this->admin_model->orderby_cond;

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
        $data_recs = $this->admin_model->getListingData($data_config);
        $data_recs['no_records_msg'] = $this->general->processMessageLabel('ACTION_NO_ADMIN_DATA_FOUND_C46_C46_C33');

        echo json_encode($data_recs);
        $this->skip_template_view();
    }

    /**
     * export method is used to export listing data records in csv or pdf formats.
     */
    public function export()
    {
        $this->filter->getModuleWiseAccess("admin", "Export", TRUE);
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
        $list_config = $this->admin_model->getListConfiguration();
        $form_config = $this->admin_model->getFormConfiguration();
        $table_name = $this->admin_model->table_name;
        $table_alias = $this->admin_modeltable_alias;
        $primary_key = $this->admin_model->primary_key;
        $extra_cond = $this->admin_model->extra_cond;
        $groupby_cond = $this->admin_model->groupby_cond;
        $having_cond = $this->admin_model->having_cond;
        $orderby_cond = $this->admin_model->orderby_cond;

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
        $db_recs = $this->admin_model->getExportData($export_config);
        $db_recs = $this->listing->getDataForList($db_recs, $export_config, "GExport", array());
        if (!is_array($db_recs) || count($db_recs) == 0)
        {
            $this->session->set_flashdata('failure', $this->general->processMessageLabel('GENERIC_GRID_NO_RECORDS_TO_PROCESS'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        $heading = "Admin";
        $filename = "Admin_".count($db_recs)."_Records";
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
            $extra_cond = $this->admin_model->extra_cond;
            if ($mode == "Update")
            {
                list($list_access, $view_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("admin", array("List", "View", "Update", "Delete", "Export"), TRUE, TRUE);
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
                list($list_access, $add_access, $del_access) = $this->filter->getModuleWiseAccess("admin", array("List", "Add", "Delete"), TRUE, TRUE);
                if (!$add_access)
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = $func = array();
            if ($mode == 'Update')
            {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "admin"), $this->session->userdata('iAdminId'));
                $data_arr = $this->admin_model->getData(intval($id));
                $data = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true")
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $switch_arr = $this->admin_model->getSwitchTo($extra_cond, "records", $this->switchto_limit);
                $switch_combo = $this->filter->makeArrayDropDown($switch_arr);
                $switch_cit = array();
                $switch_tot = $this->admin_model->getSwitchTo($extra_cond, "count");
                if ($this->switchto_limit > 0 && $switch_tot > $this->switchto_limit)
                {
                    $switch_cit['param'] = "true";
                    $switch_cit['url'] = $this->mod_enc_url['get_self_switch_to'];
                    if (!array_key_exists($id, $switch_combo))
                    {
                        $extra_cond = $this->db->protect($this->admin_model->table_alias.".".$this->admin_model->primary_key)." = ".$this->db->escape($id);
                        $switch_cur = $this->admin_model->getSwitchTo($extra_cond, "records", 1);
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
                $hide_admin_rec = $this->general->isAdminDataRecord($id, "Update", $this->admin_model->table_name, "vUserName");
                $hide_del_status = $this->general->isAdminDataRecord($id, "Delete", $this->admin_model->table_name);
                $del_access = ($hide_del_status["success"]) ? FALSE : $del_access;
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "admin", $recName);
            }
            else
            {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "admin"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "admin");
            }
            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $form_config = $this->admin_model->getFormConfiguration($config_arr);
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
                            if (method_exists($this->admin_model, $phpfunc))
                            {
                                $tmpdata = $this->admin_model->$phpfunc($mode, $data[$key], $data, $id, $key, $key);
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
                    $this->loadView("admin_add");
                }
                else
                {
                    $this->loadView("admin_add_view");
                }
            }
            else
            {
                $this->loadView("admin_add");
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
            $add_edit_access = $this->filter->getModuleWiseAccess("admin", $mode, TRUE, TRUE);
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

            $form_config = $this->admin_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $ma_name = $params_arr["ma_name"];
            $ma_email = $params_arr["ma_email"];
            $ma_user_name = $params_arr["ma_user_name"];
            $ma_password = $params_arr["ma_password"];
            $ma_phonenumber = $params_arr["ma_phonenumber"];
            $ma_group_id = $params_arr["ma_group_id"];
            $ma_status = $params_arr["ma_status"];
            $ma_last_access = $params_arr["ma_last_access"];

            $unique_arr = array();
            $unique_arr["vEmail"] = $ma_email;
            $unique_arr["vUserName"] = $ma_user_name;

            $unique_exists = $this->admin_model->checkRecordExists($this->admin_model->unique_fields, $unique_arr, $id, $mode, $this->admin_model->unique_type);
            if ($unique_exists)
            {
                $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_EMAIL_OR_USER_NAME_C46_C46_C33');
                if ($error_msg == "")
                {
                    $error_msg = "Record already exists with these details of Email or User Name";
                }
                throw new Exception($error_msg);
            }
            $data = $save_data_arr = $file_data = array();
            $data["vName"] = $ma_name;
            $data["vEmail"] = $ma_email;
            $data["vUserName"] = $ma_user_name;
            $data["vPassword"] = $ma_password;
            $data["vPhonenumber"] = $this->filter->formatActionData($ma_phonenumber, $form_config["ma_phonenumber"]);
            $data["iGroupId"] = $ma_group_id;
            $data["eStatus"] = $ma_status;
            $data["dLastAccess"] = $this->filter->formatActionData($ma_last_access, $form_config["ma_last_access"]);

            $save_data_arr["ma_name"] = $data["vName"];
            $save_data_arr["ma_email"] = $data["vEmail"];
            $save_data_arr["ma_user_name"] = $data["vUserName"];
            $save_data_arr["ma_password"] = $data["vPassword"];
            $save_data_arr["ma_phonenumber"] = $data["vPhonenumber"];
            $save_data_arr["ma_group_id"] = $data["iGroupId"];
            $save_data_arr["ma_status"] = $data["eStatus"];
            $save_data_arr["ma_last_access"] = $data["dLastAccess"];
            if ($mode == 'Add')
            {
                $id = $this->admin_model->insert($data);
                if (intval($id) > 0)
                {
                    $save_data_arr["iAdminId"] = $data["iAdminId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_ADDED_SUCCESSFULLY_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("ma.iAdminId")." = ".$this->db->escape($id);
                $switch_combo = $this->admin_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "admin", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            elseif ($mode == 'Update')
            {
                $res = $this->admin_model->update($data, intval($id));
                if (intval($res) > 0)
                {
                    $save_data_arr["iAdminId"] = $data["iAdminId"] = $id;
                    $msg = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                }
                else
                {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("ma.iAdminId")." = ".$this->db->escape($id);
                $switch_combo = $this->admin_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "admin", $recName, "mode|".$this->general->getAdminEncodeURL("Update")."|id|".$this->general->getAdminEncodeURL($id));
            }
            $ret_arr['id'] = $id;
            $ret_arr['mode'] = $mode;
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

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
        $extra_cond = $this->general->getAdminExtraCondtion($this->admin_model->table_name, $this->admin_model->table_alias);
        $search_alias = 'Yes';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status")))
        {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->admin_model->getListConfiguration();
            $config_arr['form_config'] = $this->admin_model->getFormConfiguration();
            $config_arr['table_name'] = $this->admin_model->table_name;
            $config_arr['table_alias'] = $this->admin_model->table_alias;
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
            $primary_field = $this->admin_model->table_alias.".".$this->admin_model->primary_key;
        }
        else
        {
            $primary_field = $this->admin_model->primary_key;
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

                    $del_access = $this->filter->getModuleWiseAccess("admin", "Delete", TRUE, TRUE);
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
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Delete', $this->admin_model->table_name);
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
                    $success = $this->admin_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_DELETION_THIS_RECORD_C46_C46_C33'));
                    }
                    $message = $this->general->processMessageLabel('ACTION_RECORD_C40S_C41_DELETED_SUCCESSFULLY_C46_C46_C33');
                    break;
                case 'edit':
                    $mode = "Update";
                    $edit_access = $this->filter->getModuleWiseAccess("admin", "Update", TRUE, TRUE);
                    if (!$edit_access)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->admin_model->getListConfiguration($post_name);
                    $form_config = $this->admin_model->getFormConfiguration($list_config['source_field']);
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
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Update', $this->admin_model->table_name, $field_name);
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
                    if (in_array($field_name, $this->admin_model->unique_fields))
                    {
                        $rec_arr = $this->admin_model->getData(intval($primary_ids), array('ma.vEmail', 'ma.vUserName'));
                        $unique_arr = is_array($rec_arr[0]) ? array_merge($rec_arr[0], $unique_arr) : $unique_arr;
                        $unique_exists = $this->admin_model->checkRecordExists($this->admin_model->unique_fields, $unique_arr, $primary_ids, "Update", $this->admin_model->unique_type);
                        if ($unique_exists)
                        {
                            $error_msg = $this->general->processMessageLabel('ACTION_RECORD_ALREADY_EXISTS_WITH_THESE_DETAILS_OF_EMAIL_OR_USER_NAME_C46_C46_C33');
                            if ($error_msg == "")
                            {
                                $error_msg = "Record already exists with these details of Email or User Name";
                            }
                            throw new Exception($error_msg);
                        }
                    }

                    $data_arr[$field_name] = $post_val;
                    $success = $this->admin_model->update($data_arr, intval($primary_ids));
                    $message = $this->general->processMessageLabel('ACTION_RECORD_SUCCESSFULLY_UPDATED_C46_C46_C33');
                    if (!$success)
                    {
                        throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                    }
                    break;
                case 'status':
                    $mode = "Status";
                    $edit_access = $this->filter->getModuleWiseAccess("admin", "Update", TRUE, TRUE);
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
                        $res_arr = $this->general->isAdminDataRecord($primary_ids, 'Update', $this->admin_model->table_name, $status_field);
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
                        $field_name = $this->admin_model->table_alias.".eStatus";
                    }
                    else
                    {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->admin_model->update($data_arr, $extra_cond, $search_alias, $search_join);
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
                    $combo_config['main_table'] = $this->admin_model->table_name;
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
                    if (method_exists($this->admin_model, $phpfunc))
                    {
                        $data_arr = $this->admin_model->$phpfunc($data[$name], $mode, $id, $data, $parent_src, $this->term);
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

        $switchto_fields = $this->admin_model->switchto_fields;
        $extra_cond = $this->admin_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond." AND ".$search_cond;

        $switch_arr = $this->admin_model->getSwitchTo($extra_cond);
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
        $config_arr = $this->admin_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update")
        {
            $data_arr = $this->admin_model->getData(intval($id));
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

    /**
     * getSearchAutoComplete method is used to get dataset values for left search panel in grid listing (array values in json string)
     */
    public function getSearchAutoComplete()
    {
        $params_arr = $this->params_arr;
        $alias_name = $params_arr['alias_name'];
        $term = $this->term = strtolower($params_arr['q']);
        $combo_arr = $this->getLeftSearchContent('Single', $alias_name, $term);
        $records = $combo_arr[$alias_name]['records'];
        $token_array = array();
        $records_cnt = count($records);
        for ($i = 0; $i < $records_cnt; $i++)
        {
            $token_array[$i]["id"] = $records[$i]['id'];
            $token_array[$i]["val"] = utf8_encode($records[$i]['val']);
            $token_array[$i]["count"] = $records[$i]['tot'];
        }
        $html_str = json_encode($token_array);
        echo $html_str;
        $this->skip_template_view();
    }
    /**
     * getLeftSearchContent method is used to get grid left search template or search results in left search
     * @param string $type type for getting either search template string or individual search.
     * @param string $alias_name alias name of grid list fields for individual search.
     * @param string $term term for searching specified results for individual search.
     * @return array $search_arr returns search data array for individual search
     */
    public function getLeftSearchContent($type = 'All', $alias_name = array(), $term = '')
    {
        $params_arr = $this->params_arr;
        $search_config = $this->admin_model->search_config;
        if ($type == "Single")
        {
            $search_arr[$alias_name] = $search_config[$alias_name];
        }
        else
        {
            $search_arr = $search_config;
        }
        $where_cond = $this->admin_model->extra_cond;
        if (is_array($search_arr) && count($search_arr) > 0)
        {
            $limit = $this->config->item('ADMIN_GRID_SEARCH_LS');
            foreach ($search_arr as $key => $val)
            {
                $alias_name = $val['name'];
                $range_type = $val['range'];
                $config_arr = $this->admin_model->getListConfiguration($alias_name);
                $form_config = $this->admin_model->getFormConfiguration($config_arr['source_field']);
                if (!is_array($config_arr) || count($config_arr) == 0)
                {
                    continue;
                }
                $display_query = $config_arr['display_query'];
                $display_query_pro = $this->db->protect($display_query);
                $source_field = $config_arr['source_field'];
                $field_type = $config_arr['type'];
                $data_arr = $fields_arr = $range_assoc = array();
                if ($range_type == "Yes")
                {
                    $range_values = $val['values'];
                    if (is_array($range_values) && count($range_values) > 0)
                    {
                        foreach ($range_values as $rkey => $rval)
                        {
                            $min_val = $rval['min'];
                            $max_val = $rval['max'];
                            if (!is_numeric($min_val) && !is_numeric($max_val))
                            {
                                continue;
                            }
                            if ($min_val == "")
                            {
                                $case_query = " ".$display_query_pro." <= ".$this->db->escape($max_val);
                            }
                            else
                            if ($max_val == "")
                            {
                                $case_query = " ".$display_query_pro." <= ".$this->db->escape($min_val);
                            }
                            else
                            {
                                $case_query = " ".$display_query_pro." >= ".$this->db->escape($min_val)." AND ".$display_query_pro." <= ".$this->db->escape($max_val);
                            }
                            $range_values[$rkey]["label"] = $min_val." - ".$max_val;
                            if ($rkey == 0)
                            {
                                if ($min_val == "" && is_numeric($max_val))
                                {
                                    $case_query = " ".$display_query_pro." <= ".$this->db->escape($max_val);
                                    $range_values[$rkey]['level'] = "below";
                                    $range_values[$rkey]["label"] = $max_val." ".$this->general->processMessageLabel('GENERIC_AND_BELOW');
                                }
                            }
                            elseif ($rkey == count($range_values)-1)
                            {
                                if ($max_val == "" && is_numeric($min_val))
                                {
                                    $case_query = " ".$display_query_pro." >= ".$this->db->escape($min_val);
                                    $range_values[$rkey]['level'] = "above";
                                    $range_values[$rkey]["label"] = $min_val." ".$this->general->processMessageLabel('GENERIC_AND_ABOVE');
                                }
                            }
                            $fields_arr[] = array(
                                "field" => "SUM(CASE WHEN ".$case_query." THEN 1 ELSE 0 END) AS tot_".$rkey,
                                "escape" => TRUE,
                            );
                            $range_assoc["tot_".$rkey] = $range_values[$rkey];
                        }
                        if (is_array($fields_arr) && count($fields_arr) > 0)
                        {
                            $limit_fields = array();
                            $limit_fields[] = array(
                                "field" => "MIN(".$display_query_pro.") AS min",
                                "escape" => TRUE,
                            );
                            $limit_fields[] = array(
                                "field" => "MAX(".$display_query_pro.") as max",
                                "escape" => TRUE,
                            );
                            $range_limit = $this->admin_model->getData($where_cond, $limit_fields, "", "", "", 'Yes');
                            $range_arr = $this->admin_model->getData($where_cond, $fields_arr, "", "", "", 'Yes');
                            if (is_array($range_arr[0]) && count($range_arr[0]) > 0)
                            {
                                $rc = 0;
                                foreach ($range_arr[0] as $drkey => $drval)
                                {
                                    $temp_range['min'] = $range_assoc[$drkey]['min'];
                                    $temp_range['max'] = $range_assoc[$drkey]['max'];
                                    $temp_range['level'] = $range_assoc[$drkey]['level'];
                                    $temp_range['tot'] = $drval;
                                    $data_arr[$rc] = $temp_range;
                                    $rc++;
                                }
                            }
                        }
                    }
                }
                else
                {
                    if ($type == "Single")
                    {
                        $extra_cond = "(LOWER(".$display_query.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$display_query.") LIKE '% ".$this->db->escape_like_str($term)."%')";
                    }
                    else
                    {
                        $extra_cond = '';
                    }
                    if ($where_cond != '')
                    {
                        $extra_cond = ($extra_cond != '') ? $extra_cond." AND ".$where_cond : $where_cond;
                    }
                    $group_by = $display_query;
                    if ($val['key'] != "")
                    {
                        $fields_arr[] = array(
                            "field" => $val['key']." AS id",
                        );
                        $fields_arr[] = array(
                            "field" => $display_query." AS val",
                        );
                        $group_by .= ",".$val['key'];
                    }
                    else
                    {
                        $fields_arr[] = array(
                            "field" => $display_query." AS id",
                        );
                        $fields_arr[] = array(
                            "field" => $display_query." AS val",
                        );
                    }
                    $fields_arr[] = array(
                        "field" => "COUNT(*) AS tot",
                        "escape" => TRUE,
                    );

                    $order_by = "id ".((strtolower($val['order']) == "desc") ? 'DESC' : 'ASC');
                    $data_arr = $this->admin_model->getData($extra_cond, $fields_arr, $order_by, $group_by, $limit, 'Yes');
                }
                if ($range_type == "Yes")
                {
                    $search_arr[$key]['records'] = $data_arr;
                    $search_arr[$key]['values'] = $range_values;
                    $search_arr[$key]['range_min'] = intval($range_limit[0]['min']);
                    $search_arr[$key]['range_max'] = intval($range_limit[0]['max']);
                }
                else
                {
                    $search_arr[$key]['records'] = $data_arr;
                    $search_tbl_arr = $this->dropdown_arr[$source_field];

                    $isRefReq = (is_array($data_arr) && count($data_arr) < $limit) ? TRUE : FALSE;
                    $isRefSet = ($val['set'] == 'reference') ? TRUE : FALSE;
                    $isSetExt = (is_array($search_tbl_arr)) ? TRUE : FALSE;

                    $search_arr[$key]['records'] = $data_arr;
                    $search_arr[$key]['auto'] = (!$isRefReq) ? "Yes" : "No";
                    $arr_diff = $refer_arr = $temp_arr = array();
                    if ($isSetExt && $isRefSet && $isRefReq)
                    {
                        if ($type == "Single")
                        {
                            $concat_fields = $this->db->concat_cast($search_tbl_arr['field_val']);
                            $search_cond = "(LOWER(".$concat_fields.") LIKE '".$this->db->escape_like_str($term)."%' OR LOWER(".$concat_fields.") LIKE '% ".$this->db->escape_like_str($term)."%')";
                        }
                        else
                        {
                            $search_cond = "";
                        }
                        $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), $search_cond, '');
                        $temp_arr = $this->filter->makeArrayDropdown($data_arr);
                        if ($search_tbl_arr['type'] == 'enum')
                        {
                            if (is_array($combo_arr) && count($combo_arr) > 0)
                            {
                                $combo_arr_cnt = count($combo_arr);
                                for ($i = 0; $i < $combo_arr_cnt; $i++)
                                {
                                    if ($type == "Single")
                                    {
                                        $reg_exp = '/\b'.$term.'[a-zA-Z0-9_]*\b/i';
                                        if (preg_match($reg_exp, $combo_arr[$i]["val"], $matches))
                                        {
                                            $refer_arr[$i] = $combo_arr[$i]["val"];
                                        }
                                    }
                                    else
                                    {
                                        $refer_arr[$i] = $combo_arr[$i]["val"];
                                    }
                                }
                            }
                        }
                        else
                        {
                            $refer_arr = $this->filter->makeArrayDropdown($combo_arr);
                        }
                        $arr_diff = array_diff($refer_arr, $temp_arr);
                        if (is_array($arr_diff) && count($arr_diff) > 0)
                        {
                            foreach ((array) $arr_diff as $key_diff => $val_diff)
                            {
                                if (count($search_arr[$key]['records']) >= $limit)
                                {
                                    $search_arr[$key]['auto'] = "Yes";
                                    break;
                                }
                                $search_arr[$key]['records'][] = array(
                                    "id" => $key_diff,
                                    'val' => $val_diff,
                                    'tot' => 0,
                                );
                            }
                        }
                    }
                    if ($search_tbl_arr['type'] == "enum")
                    {
                        $temp_data_arr = $search_arr[$key]['records'];
                        $temp_data_arr = (is_array($temp_data_arr) && count($temp_data_arr) > 0) ? $temp_data_arr : array();
                        foreach ($temp_data_arr as $dKey => $dVal)
                        {
                            $temp_data_arr[$dKey]['val'] = $this->listing->getEnumDisplayVal($search_tbl_arr['values'], $dVal['id']);
                        }
                        $search_arr[$key]['records'] = $temp_data_arr;
                    }
                    elseif ($field_type == 'date' || $field_type == 'date_and_time' || $field_type == 'time')
                    {
                        $temp_data_arr = $search_arr[$key]['records'];
                        $temp_data_arr = (is_array($temp_data_arr) && count($temp_data_arr) > 0) ? $temp_data_arr : array();
                        foreach ($temp_data_arr as $dKey => $dVal)
                        {
                            $temp_data_arr[$dKey]['val'] = $this->listing->formatListingData($dVal['val'], $dVal['id'], $temp_data_arr[$dKey], $config_arr, $form_config, array(), "MGrid");
                        }
                        $search_arr[$key]['records'] = $temp_data_arr;
                    }
                }
                if ($val['php_func'] != '')
                {
                    $phpfunc = $val['php_func'];
                    if (substr($phpfunc, 0, 12) == 'controller::' && substr($phpfunc, 12) !== FALSE)
                    {
                        $phpfunc = substr($phpfunc, 12);
                        if (method_exists($this, $phpfunc))
                        {
                            $search_arr[$key] = $this->$phpfunc($search_arr[$key]);
                        }
                    }
                    elseif (substr($phpfunc, 0, 7) == 'model::' && substr($phpfunc, 7) !== FALSE)
                    {
                        $phpfunc = substr($phpfunc, 7);
                        if (method_exists($this->admin_model, $phpfunc))
                        {
                            $search_arr[$key] = $this->admin_model->$phpfunc($search_arr[$key]);
                        }
                    }
                    elseif (method_exists($this->general, $phpfunc))
                    {
                        $search_arr[$key] = $this->general->$phpfunc($search_arr[$key]);
                    }
                }
            }
        }
        else
        {
            $search_arr = array();
        }
        if ($params_arr['tempalte'] == "Yes")
        {
            $render_arr = array(
                "search_arr" => $search_arr,
            );
            $html_str = $parse_html = $this->parser->parse("admin_search", $render_arr, TRUE);
            echo $html_str;
            $this->skip_template_view();
        }
        else
        {
            return $search_arr;
        }
    }
}
