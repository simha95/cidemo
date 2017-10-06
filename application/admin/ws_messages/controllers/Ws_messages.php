<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of WS Messages Controller
 *
 * test
 *
 * @category admin
 *
 * @package ws_messages
 *
 * @subpackage controllers
 *
 * @module WS Messages
 *
 * @class Ws_messages.php
 *
 * @path application\admin\ws_messages\controllers\Ws_messages.php
 *
 * @version 4.2
 *
 * @author CIT Dev Team
 *
 * @since 12.10.2016
 */
class Ws_messages extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     * @created CIT Admin | 11.10.2016
     * @modified CIT Admin | 12.10.2016
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('listing');
        $this->load->library('filter');
        $this->load->library('dropdown');
        $this->load->model('ws_messages_model');
        $this->_request_params();
        $this->folder_name = "ws_messages";
        $this->module_name = "ws_messages";
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
            "multi_lingual" => "Yes",
            "physical_data_remove" => "Yes",
            "workflow_modes" => array()
        );
        $this->dropdown_arr = array(
            "mwm_type" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Input',
                        'val' => $this->lang->line('WS_MESSAGES_INPUT')
                    ),
                    array(
                        'id' => 'Finish',
                        'val' => $this->lang->line('WS_MESSAGES_FINISH')
                    )
                )
            ),
            "mwm_status" => array(
                "type" => "enum",
                "default" => "Yes",
                "values" => array(
                    array(
                        'id' => 'Active',
                        'val' => $this->lang->line('WS_MESSAGES_ACTIVE')
                    ),
                    array(
                        'id' => 'Inactive',
                        'val' => $this->lang->line('WS_MESSAGES_INACTIVE')
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
        list($list_access, $view_access, $add_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("ws_messages", array("List", "View", "Add", "Update", "Delete", "Export"), TRUE, TRUE);
        try {
            if (!$list_access) {
                throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
            }
            $enc_loc_module = $this->general->getMD5EncryptString("ListPrefer", "ws_messages");

            $status_array = array(
                'Active',
                'Inactive',
            );

            $status_label = array(
                'js_lang_label.WS_MESSAGES_ACTIVE',
                'js_lang_label.WS_MESSAGES_INACTIVE',
            );

            $status_array = $status_values = array();
            $status_values = $this->dropdown_arr["mwm_status"]["values"];
            $status_values_count = count($status_values);
            for ($i = 0; $i < $status_values_count; $i++) {
                $status_array[$i] = $status_values[$i]["id"];
            }

            $list_config = $this->ws_messages_model->getListConfiguration();
            $this->processConfiguration($list_config, $add_access, $edit_access, TRUE);
            $this->general->trackModuleNavigation("Module", "List", "Viewed", $this->mod_enc_url["index"], "ws_messages");

            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();
            $render_arr = array(
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
                'default_filters' => $this->ws_messages_model->default_filters,
            );
            $this->smarty->assign($render_arr);
            $this->loadView("ws_messages_index");
        } catch (Exception $e) {
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
        if (!trim($sidx) && !trim($sord)) {
            $sdef = 'Yes';
        }
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $list_config = $this->ws_messages_model->getListConfiguration();
        $form_config = $this->ws_messages_model->getFormConfiguration();
        $extra_cond = $this->ws_messages_model->extra_cond;
        $groupby_cond = $this->ws_messages_model->groupby_cond;
        $having_cond = $this->ws_messages_model->having_cond;
        $orderby_cond = $this->ws_messages_model->orderby_cond;

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
        $data_recs = $this->ws_messages_model->getListingData($data_config);
        $data_recs['no_records_msg'] = '';

        echo json_encode($data_recs);
        $this->skip_template_view();
    }

    /**
     * export method is used to export listing data records in csv or pdf formats.
     */
    public function export()
    {
        $this->filter->getModuleWiseAccess("ws_messages", "Export", TRUE);
        $params_arr = $this->params_arr;
        $page = $params_arr['page'];
        $rowlimit = $params_arr['rowlimit'];
        $sidx = $params_arr['sidx'];
        $sord = $params_arr['sord'];
        $sdef = $params_arr['sdef'];
        if (!trim($sidx) && !trim($sord)) {
            $sdef = 'Yes';
        }
        $export_type = $params_arr['export_type'];
        $export_mode = $params_arr['export_mode'];
        $filters = $params_arr['filters'];
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode(base64_decode($filters), TRUE);
        $fields = json_decode(base64_decode($params_arr['fields']), TRUE);
        $list_config = $this->ws_messages_model->getListConfiguration();
        $form_config = $this->ws_messages_model->getFormConfiguration();
        $table_name = $this->ws_messages_model->table_name;
        $table_alias = $this->ws_messages_modeltable_alias;
        $primary_key = $this->ws_messages_model->primary_key;
        $extra_cond = $this->ws_messages_model->extra_cond;
        $groupby_cond = $this->ws_messages_model->groupby_cond;
        $having_cond = $this->ws_messages_model->having_cond;
        $orderby_cond = $this->ws_messages_model->orderby_cond;

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
        $db_recs = $this->ws_messages_model->getExportData($export_config);
        $db_recs = $this->listing->getDataForList($db_recs, $export_config, "GExport", array());
        if (!is_array($db_recs) || count($db_recs) == 0) {
            $this->session->set_flashdata('failure', $this->lang->line('GENERIC_GRID_NO_RECORDS_TO_PROCESS'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        $filename = "WS_Messages_" . count($db_recs) . "_Records";
        $heading = "WS Messages";
        if ($export_mode == "all" && is_array($tot_fields_arr)) {
            if (($pr_key = array_search($primary_key, $tot_fields_arr)) !== FALSE) {
                unset($tot_fields_arr[$pr_key]);
            }
            $fields = array_values($tot_fields_arr);
        }
        $numberOfColumns = count($fields);
        if ($export_type == 'pdf') {
            $pdf_style = "TCPDF";
            $columns = $aligns = $widths = $data = array();
            //Table headers info
            for ($i = 0; $i < $numberOfColumns; $i++) {
                $size = 10;
                $position = '';
                if (array_key_exists($fields[$i], $list_config)) {
                    $label = $list_config[$fields[$i]]['label_lang'];
                    $position = $list_config[$fields[$i]]['align'];
                    $size = $list_config[$fields[$i]]['width'];
                } elseif (array_key_exists($fields[$i], $form_config)) {
                    $label = $form_config[$fields[$i]]['label_lang'];
                } else {
                    $label = $fields[$i];
                }
                $columns[] = $label;
                $aligns[] = in_array($position, array('right', 'center')) ? $position : "left";
                $widths[] = $size;
            }

            //Table data info
            $db_rec_cnt = count($db_recs);
            for ($i = 0; $i < $db_rec_cnt; $i++) {
                foreach ((array) $db_recs[$i] as $key => $val) {
                    if (is_array($fields) && in_array($key, $fields)) {
                        $data[$i][$key] = $this->listing->dataForExportMode($val, "pdf", $pdf_style);
                    }
                }
            }

            require_once ($this->config->item('third_party') . 'Pdf_export.php');
            $pdf = new PDF_Export(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
            $pdf->initialize($heading);

            $pdf->writeGridTable($columns, $data, $widths, $aligns);
            $pdf->Output($filename . ".pdf", 'D');
        } elseif ($export_type == 'csv') {
            require_once ($this->config->item('third_party') . 'Csv_export.php');
            $columns = $data = array();

            for ($i = 0; $i < $numberOfColumns; $i++) {
                if (array_key_exists($fields[$i], $list_config)) {
                    $label = $list_config[$fields[$i]]['label_lang'];
                } elseif (array_key_exists($fields[$i], $form_config)) {
                    $label = $form_config[$fields[$i]]['label_lang'];
                } else {
                    $label = $fields[$i];
                }
                $columns[] = $label;
            }
            $db_recs_cnt = count($db_recs);
            for ($i = 0; $i < $db_recs_cnt; $i++) {
                foreach ((array) $db_recs[$i] as $key => $val) {
                    if (is_array($fields) && in_array($key, $fields)) {
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
        $mode = (in_array($params_arr['mode'], array("Update", "View"))) ? "Update" : "Add";
        $view_mode = ($params_arr['mode'] == "View") ? TRUE : FALSE;
        $id = $params_arr['id'];
        $enc_id = $this->general->getAdminEncodeURL($id);
        try {
            $extra_cond = $this->ws_messages_model->extra_cond;
            if ($mode == "Update") {
                list($list_access, $view_access, $edit_access, $del_access, $expo_access) = $this->filter->getModuleWiseAccess("ws_messages", array("List", "View", "Update", "Delete", "Export"), TRUE, TRUE);
                if (!$edit_access && !$view_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_VIEW_THIS_PAGE_C46_C46_C33'));
                }
            } else {
                list($list_access, $add_access, $del_access) = $this->filter->getModuleWiseAccess("ws_messages", array("List", "Add", "Delete"), TRUE, TRUE);
                if (!$add_access) {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $data = array();
            if ($mode == 'Update') {
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowEdit", "ws_messages"), $this->session->userdata('iAdminId'));
                $data_arr = $this->ws_messages_model->getData(intval($id));
                $data = $data_arr[0];
                if ((!is_array($data) || count($data) == 0) && $params_arr['rmPopup'] != "true") {
                    throw new Exception($this->general->processMessageLabel('ACTION_RECORDS_WHICH_YOU_ARE_TRYING_TO_ACCESS_ARE_NOT_AVAILABLE_C46_C46_C33'));
                }
                $switch_arr = $this->ws_messages_model->getSwitchTo($extra_cond, "records", $this->switchto_limit);
                $switch_combo = $this->filter->makeArrayDropDown($switch_arr);
                $switch_cit = array();
                $switch_tot = $this->ws_messages_model->getSwitchTo($extra_cond, "count");
                if ($this->switchto_limit > 0 && $switch_tot > $this->switchto_limit) {
                    $switch_cit['param'] = "true";
                    $switch_cit['url'] = $this->mod_enc_url['get_self_switch_to'];
                    if (!array_key_exists($id, $switch_combo)) {
                        $extra_cond = $this->db->protect($this->ws_messages_model->table_alias . "." . $this->ws_messages_model->primary_key) . " = " . $this->db->escape($id);
                        $switch_cur = $this->ws_messages_model->getSwitchTo($extra_cond, "records", 1);
                        if (is_array($switch_cur) && count($switch_cur) > 0) {
                            $switch_combo[$switch_cur[0]['id']] = $switch_cur[0]['val'];
                        }
                    }
                }
                $recName = $switch_combo[$id];
                $switch_enc_combo = $this->filter->getSwitchEncryptRec($switch_combo);
                $this->dropdown->combo("array", "vSwitchPage", $switch_enc_combo, $enc_id);
                $next_prev_records = $this->filter->getNextPrevRecords($id, $switch_arr);

                $extra_lang_cond = $this->db->protect("iWSMessageId") . " = " . $this->db->escape($id);
                $lang_data = $this->ws_messages_model->getLangData($extra_lang_cond);
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "ws_messages", $recName);
            } else {
                $recName = '';
                $ctrl_flow = $this->ci_local->read($this->general->getMD5EncryptString("FlowAdd", "ws_messages"), $this->session->userdata('iAdminId'));
                $this->general->trackModuleNavigation("Module", "Form", "Viewed", $this->mod_enc_url["add"], "ws_messages");
            }
            $opt_arr = $img_html = $auto_arr = $config_arr = array();

            $form_config = $this->ws_messages_model->getFormConfiguration($config_arr);
            if (is_array($form_config) && count($form_config) > 0) {
                foreach ($form_config as $key => $val) {
                    if ($params_arr['rmPopup'] == "true" && $params_arr[$key] != "") {
                        $data[$key] = $params_arr[$key];
                    } elseif ($val["dfapply"] != "") {
                        if ($val["dfapply"] == "forceApply") {
                            $data[$key] = $val['default'];
                        } elseif ($val["dfapply"] == "addOnly") {
                            if ($mode == "Add") {
                                $data[$key] = $val['default'];
                            }
                        } elseif ($val["dfapply"] == "everyUpdate") {
                            if ($mode == "Update") {
                                $data[$key] = $val['default'];
                            }
                        } else {
                            $data[$key] = (trim($data[$key]) != "") ? $data[$key] : $val['default'];
                        }
                    }
                    if ($val['encrypt'] == "Yes") {
                        $data[$key] = $this->general->decryptDataMethod($data[$key], $val["enctype"]);
                    }
                    if ($val['function'] != "") {
                        $phpfunc = $val['function'];
                        if (method_exists($this->general, $phpfunc)) {
                            $data[$key] = $this->general->$phpfunc($mode, $data[$key], $data, $id, $key, $key);
                        }
                    }
                    $source_field = $val['name'];
                    $combo_config = $this->dropdown_arr[$source_field];
                    if (is_array($combo_config) && count($combo_config) > 0) {
                        if ($combo_config['auto'] == "Yes") {
                            $combo_count = $this->getSourceOptions($source_field, $mode, $id, $data, '', 'count');
                            if ($combo_count[0]['tot'] > $this->dropdown_limit) {
                                $auto_arr[$source_field] = "Yes";
                            }
                        }
                        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data);
                        $final_arr = $this->filter->makeArrayDropdown($combo_arr);
                        if ($combo_config['opt_group'] == "Yes") {
                            $display_arr = $this->filter->makeOPTDropdown($combo_arr);
                        } else {
                            $display_arr = $final_arr;
                        }
                        $this->dropdown->combo("array", $source_field, $display_arr, $data[$key]);
                        $opt_arr[$source_field] = $final_arr;
                    }
                }
            }
            $extra_qstr .= $this->general->getRequestURLParams();
            $extra_hstr .= $this->general->getRequestHASHParams();

            /** access controls <<< * */
            $controls_allow = $prev_link_allow = $next_link_allow = $update_allow = $delete_allow = $backlink_allow = $switchto_allow = $discard_allow = $tabing_allow = TRUE;
            if (!$del_access || $this->module_config["delete"] == "Yes") {
                $delete_allow = FALSE;
            }
            if (is_array($switch_combo) && count($switch_combo) > 0) {
                $prev_link_allow = ($next_prev_records['prev']['id'] != '') ? TRUE : FALSE;
                $next_link_allow = ($next_prev_records['next']['id'] != '') ? TRUE : FALSE;
            } else {
                $prev_link_allow = $next_link_allow = $switchto_allow = FALSE;
            }
            if (!$list_access) {
                $backlink_allow = $discard_allow = FALSE;
            }
            if ($hideCtrl == "true") {
                $controls_allow = $prev_link_allow = $next_link_allow = $delete_allow = $backlink_allow = $switchto_allow = $tabing_allow = FALSE;
            }

            $multiling = $this->config->item("MULTI_LINGUAL_PROJECT");
            if ($multiling == 'No') {
                $prlang = 'EN';
            } else {
                $prlang = $this->config->item("PRIME_LANG");
            }

            /** access controls >>> * */
            $render_arr = array(
                "lang_data" => $lang_data,
                "prlang" => $prlang,
                "exlang_arr" => $this->config->item("OTHER_LANG"),
                "lang_info" => $this->config->item("LANG_INFO"),
                "dflang" => $this->config->item("DEFAULT_LANG"),
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
            if ($mode == "Update") {
                if ($edit_access && $view_mode != TRUE) {
                    $this->loadView("ws_messages_add");
                } else {
                    $this->loadView("ws_messages_add_view");
                }
            } else {
                $this->loadView("ws_messages_add");
            }
        } catch (Exception $e) {
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
        try {
            $add_edit_access = $this->filter->getModuleWiseAccess("ws_messages", $mode, TRUE, TRUE);
            if (!$add_edit_access) {
                if ($mode == "Update") {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_MODIFY_THESE_DETAILS_C46_C46_C33'));
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_YOU_ARE_NOT_AUTHORIZED_TO_ADD_THESE_DETAILS_C46_C46_C33'));
                }
            }

            $form_config = $this->ws_messages_model->getFormConfiguration();
            $params_arr = $this->_request_params();
            $mwm_apiname = $params_arr["mwm_apiname"];
            $mwm_message_code = $params_arr["mwm_message_code"];
            $mwml_message = $params_arr["mwml_message"];
            $mwm_type = $params_arr["mwm_type"];
            $mwm_status = $params_arr["mwm_status"];

            $data = $save_data_arr = $file_data = array();
            $data["vAPIName"] = $mwm_apiname;
            $data["vCode"] = $mwm_message_code;
            $data["vType"] = $mwm_type;
            $data["eStatus"] = $mwm_status;

            $lang_keys["tMessage"] = "mwml_message";
            $lang_data["tMessage"] = $mwml_message;

            $save_data_arr["mwm_apiname"] = $data["vAPIName"];
            $save_data_arr["mwm_message_code"] = $data["vCode"];
            $save_data_arr["mwm_type"] = $data["vType"];
            $save_data_arr["mwm_status"] = $data["eStatus"];
            if ($mode == 'Add') {
                $id = $this->ws_messages_model->insert($data);
                if (intval($id) > 0) {
                    $save_data_arr["iWSMessageId"] = $data["iWSMessageId"] = $id;
                    $msg = '';
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_ADDING_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mwm.iWSMessageId") . " = " . $this->db->escape($id);
                $switch_combo = $this->ws_messages_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Added", $this->mod_enc_url["add"], "ws_messages", $recName, "mode|" . $this->general->getAdminEncodeURL("Update") . "|id|" . $this->general->getAdminEncodeURL($id));
            } elseif ($mode == 'Update') {
                $res = $this->ws_messages_model->update($data, intval($id));
                if (intval($res) > 0) {
                    $save_data_arr["iWSMessageId"] = $data["iWSMessageId"] = $id;
                    $msg = '';
                } else {
                    throw new Exception($this->general->processMessageLabel('ACTION_FAILURE_IN_UPDATING_OF_THIS_RECORD_C46_C46_C33'));
                }
                $track_cond = $this->db->protect("mwm.iWSMessageId") . " = " . $this->db->escape($id);
                $switch_combo = $this->ws_messages_model->getSwitchTo($track_cond);
                $recName = $switch_combo[0]["val"];
                $this->general->trackModuleNavigation("Module", "Form", "Modified", $this->mod_enc_url["add"], "ws_messages", $recName, "mode|" . $this->general->getAdminEncodeURL("Update") . "|id|" . $this->general->getAdminEncodeURL($id));
            }
            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;

            if (is_array($lang_data) && count($lang_data) > 0) {
                if ($mode == "Update") {
                    $extra_lang_cond = $this->db->protect("iWSMessageId") . " = " . $this->db->escape($id);
                    $db_lang_data = $this->ws_messages_model->getLangData($extra_lang_cond);
                } else {
                    $db_lang_data = array();
                }
                $prlang = $this->config->item("PRIME_LANG");
                $exlang_arr = $this->config->item("OTHER_LANG");

                // primary language operations
                $primary_arr = $lang_data;
                if (is_array($db_lang_data[$prlang]) && count($db_lang_data[$prlang]) > 0) {
                    $extra_lang_cond = $this->db->protect("iWSMessageId") . " = " . $this->db->escape($id) . " AND " . $this->db->protect("vLangCode") . " = " . $this->db->escape($prlang);
                    $this->ws_messages_model->updateLang($primary_arr, $extra_lang_cond);
                } else {
                    $primary_arr["iWSMessageId"] = $id;
                    $primary_arr["vLangCode"] = $prlang;
                    $this->ws_messages_model->insertLang($primary_arr);
                }

                // other language operations
                if (is_array($exlang_arr) && count($exlang_arr) > 0) {
                    foreach ((array) $exlang_arr as $mlKey => $mlVal) {
                        $other_arr = array();
                        foreach ((array) $lang_keys as $lfKey => $lfVal) {
                            $post_lang_data = $this->input->get_post("lang" . $lfVal, FALSE);
                            $other_arr[$lfKey] = $post_lang_data[$mlVal];
                        }
                        if (is_array($db_lang_data[$mlVal]) && count($db_lang_data[$mlVal]) > 0) {
                            $extra_lang_cond = $this->db->protect("iWSMessageId") . " = " . $this->db->escape($id) . " AND " . $this->db->protect("vLangCode") . " = " . $this->db->escape($mlVal);
                            $this->ws_messages_model->updateLang($other_arr, $extra_lang_cond);
                        } else {
                            $other_arr["iWSMessageId"] = $id;
                            $other_arr["vLangCode"] = $mlVal;
                            $this->ws_messages_model->insertLang($other_arr);
                        }
                    }
                }
            }

            $params_arr = $this->_request_params();

            $res_arr = $this->generateWSMessageFiles();
            if (!$res_arr['success']) {
                throw new Exception($res_arr['message']);
            }

            $ret_arr['message'] = $msg;
            $ret_arr['success'] = 1;
        } catch (Exception $e) {
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
        if ($this->general->allowStripSlashes()) {
            $filters = stripslashes($filters);
        }
        $filters = json_decode($filters, TRUE);
        $extra_cond = '';
        $search_mode = $search_join = $search_alias = 'No';
        if ($all_row_selected == "true" && in_array($operartor, array("del", "status"))) {
            $search_mode = ($operartor == "del") ? "Delete" : "Update";
            $search_join = $search_alias = "Yes";
            $config_arr['module_name'] = $this->module_name;
            $config_arr['list_config'] = $this->ws_messages_model->getListConfiguration();
            $config_arr['form_config'] = $this->ws_messages_model->getFormConfiguration();
            $config_arr['table_name'] = $this->ws_messages_model->table_name;
            $config_arr['table_alias'] = $this->ws_messages_model->table_alias;
            $filter_main = $this->filter->applyFilter($filters, $config_arr, $search_mode);
            $filter_left = $this->filter->applyLeftFilter($filters, $config_arr, $search_mode);
            $filter_range = $this->filter->applyRangeFilter($filters, $config_arr, $search_mode);
            if ($filter_main != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_main . ")" : $filter_main;
            }
            if ($filter_left != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_left . ")" : $filter_left;
            }
            if ($filter_range != "") {
                $extra_cond .= ($extra_cond != "") ? " AND (" . $filter_range . ")" : $filter_range;
            }
        }
        if ($search_alias == "Yes") {
            $primary_field = $this->ws_messages_model->table_alias . "." . $this->ws_messages_model->primary_key;
        } else {
            $primary_field = $this->ws_messages_model->primary_key;
        }
        if (is_array($primary_ids)) {
            $pk_condition = $this->db->protect($primary_field) . " IN ('" . implode("','", $primary_ids) . "')";
        } elseif (intval($primary_ids) > 0) {
            $pk_condition = $this->db->protect($primary_field) . " = " . $this->db->escape($primary_ids);
        } else {
            $pk_condition = FALSE;
        }
        if ($pk_condition) {
            $extra_cond .= ($extra_cond != "") ? " AND (" . $pk_condition . ")" : $pk_condition;
        }
        $data_arr = $save_data_arr = array();
        try {
            switch ($operartor) {
                case 'del':
                    $mode = "Delete";

                    $del_access = $this->filter->getModuleWiseAccess("ws_messages", "Delete", TRUE, TRUE);
                    if (!$del_access) {
                        throw new Exception('');
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE) {
                        throw new Exception('');
                    }

                    $params_arr = $this->_request_params();

                    $success = $this->ws_messages_model->delete($extra_cond, $search_alias, $search_join);
                    if (!$success) {
                        throw new Exception('');
                    }
                    $message = '';
                    break;
                case 'edit':
                    $mode = "Update";
                    $edit_access = $this->filter->getModuleWiseAccess("ws_messages", "Update", TRUE, TRUE);
                    if (!$edit_access) {
                        throw new Exception('');
                    }
                    $post_name = $params_arr['name'];
                    $post_val = is_array($params_arr['value']) ? implode(",", $params_arr['value']) : $params_arr['value'];

                    $list_config = $this->ws_messages_model->getListConfiguration($post_name);
                    $form_config = $this->ws_messages_model->getFormConfiguration($list_config['source_field']);
                    if (!is_array($form_config) || count($form_config) == 0) {
                        throw new Exception('');
                    }
                    if (in_array($form_config['type'], array("date", "date_and_time", "time", 'phone_number'))) {
                        $post_val = $this->filter->formatActionData($post_val, $form_config);
                    }
                    if ($form_config["encrypt"] == "Yes") {
                        $post_val = $this->general->encryptDataMethod($post_val, $form_config["enctype"]);
                    }
                    $field_name = $form_config['field_name'];
                    $unique_name = $form_config['name'];
                    if ($form_config["lang"] == "Yes") {
                        if ($this->session->userdata("DEFAULT_LANG") == $this->config->item("PRIME_LANG")) {

                            $data_arr[$field_name] = $post_val;
                            $success = $this->ws_messages_model->update($data_arr, intval($primary_ids));
                        }
                        $lang_data_arr[$field_name] = $post_val;
                        $extra_lang_cond = $this->db->protect("iWSMessageId") . " = " . $this->db->escape($primary_ids) . " AND " . $this->db->protect("vLangCode") . " = " . $this->db->escape($this->session->userdata("DEFAULT_LANG"));
                        $success = $this->ws_messages_model->updateLang($lang_data_arr, $extra_lang_cond);
                    } else {

                        $data_arr[$field_name] = $post_val;
                        $success = $this->ws_messages_model->update($data_arr, intval($primary_ids));
                    }
                    if (!$success) {
                        throw new Exception('');
                    }
                    $message = '';
                    break;
                case 'status':
                    $mode = "Status";
                    $edit_access = $this->filter->getModuleWiseAccess("ws_messages", "Update", TRUE, TRUE);
                    if (!$edit_access) {
                        throw new Exception('');
                    }
                    if ($search_mode == "No" && $pk_condition == FALSE) {
                        throw new Exception('');
                    }
                    $status_field = "eStatus";
                    if ($status_field == "") {
                        throw new Exception('');
                    }
                    if ($search_mode == "Yes" || $search_alias == "Yes") {
                        $field_name = $this->ws_messages_model->table_alias . ".eStatus";
                    } else {
                        $field_name = $status_field;
                    }
                    $data_arr[$field_name] = $params_arr['status'];
                    $success = $this->ws_messages_model->update($data_arr, $extra_cond, $search_alias, $search_join);
                    if (!$success) {
                        throw new Exception('');
                    }
                    $message = '';
                    break;
            }
            $ret_arr['success'] = "true";
            $ret_arr['message'] = $message;
        } catch (Exception $e) {
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
        if (!is_array($list_config) || count($list_config) == 0) {
            return $list_config;
        }
        $count_arr = array();
        foreach ((array) $list_config as $key => $val) {
            if (!$isAdd) {
                $list_config[$key]["addable"] = "No";
            }
            if (!$isEdit) {
                $list_config[$key]["editable"] = "No";
            }

            $source_field = $val['source_field'];
            $dropdown_arr = $this->dropdown_arr[$source_field];
            if (is_array($dropdown_arr) && in_array($val['type'], array("dropdown", "radio_buttons", "checkboxes", "multi_select_dropdown"))) {
                $count_arr[$key]['ajax'] = "No";
                $count_arr[$key]['json'] = "No";
                $count_arr[$key]['data'] = array();
                $combo_arr = FALSE;
                if ($dropdown_arr['auto'] == "Yes") {
                    $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                    if ($combo_arr[0]['tot'] > $this->dropdown_limit) {
                        $count_arr[$key]['ajax'] = "Yes";
                    }
                }
                if ($runCombo == TRUE) {
                    if (in_array($dropdown_arr['type'], array("enum", "phpfn"))) {
                        $data_arr = $this->getSourceOptions($source_field, "Search");
                        $json_arr = $this->filter->makeArrayDropdown($data_arr);
                        $count_arr[$key]['json'] = "Yes";
                        $count_arr[$key]['data'] = json_encode($json_arr);
                    } else {
                        if ($dropdown_arr['opt_group'] != "Yes") {
                            if ($combo_arr == FALSE) {
                                $combo_arr = $this->getSourceOptions($source_field, "Search", '', array(), '', 'count');
                            }
                            if ($combo_arr[0]['tot'] < $this->search_combo_limit) {
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
    protected function getSourceOptions($name = '', $mode = 'Add', $id = '', $data = array(), $extra = '', $rtype = 'records')
    {
        $combo_config = $this->dropdown_arr[$name];
        $data_arr = array();
        if (!is_array($combo_config) || count($combo_config) == 0) {
            return $data_arr;
        }
        $type = $combo_config['type'];
        switch ($type) {
            case 'enum':
                $data_arr = is_array($combo_config['values']) ? $combo_config['values'] : array();
                break;
            case 'token':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field) . " IN ('" . implode("','", $parent_src) . "')";
                    } elseif ($mode == "Add") {
                        $extra_cond = $this->db->protect($target_field) . " = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                }
                $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                break;
            case 'table':
                if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                    $source_field = $combo_config['source_field'];
                    $target_field = $combo_config['target_field'];
                    if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                        $parent_src = (is_array($data[$source_field])) ? $data[$source_field] : explode(",", $data[$source_field]);
                        $extra_cond = $this->db->protect($target_field) . " IN ('" . implode("','", $parent_src) . "')";
                    } elseif ($mode == "Add") {
                        $extra_cond = $this->db->protect($target_field) . " = ''";
                    }
                    $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                }
                if ($combo_config['parent_child'] == "Yes" && $combo_config['nlevel_child'] == "Yes") {
                    $combo_config['main_table'] = $this->ws_messages_model->table_name;
                    $data_arr = $this->filter->getTreeLevelDropdown($combo_config, $id, $extra, $rtype);
                } else {
                    if ($combo_config['parent_child'] == "Yes" && $combo_config['parent_field'] != "") {
                        $parent_field = $combo_config['parent_field'];
                        $extra_cond = "(" . $this->db->protect($parent_field) . " = '0' OR " . $this->db->protect($parent_field) . " = '' OR " . $this->db->protect($parent_field) . " IS NULL )";
                        if ($mode == "Update" || ($mode == "Search" && $id > 0)) {
                            $par_cond .= " AND " . $this->db->protect($combo_config['field_key']) . " <> " . $this->db->escape($id);
                        }
                        $extra = (trim($extra) != "") ? $extra . " AND " . $extra_cond : $extra_cond;
                    }
                    $data_arr = $this->filter->getTableLevelDropdown($combo_config, $id, $extra, $rtype);
                }
                break;
            case 'phpfn':
                $phpfunc = $combo_config['function'];
                if (method_exists($this->general, $phpfunc)) {
                    $parent_src = '';
                    if ($combo_config['parent_src'] == "Yes" && in_array($mode, array("Add", "Update", "Auto"))) {
                        $source_field = $combo_config['source_field'];
                        if (in_array($mode, array("Update", "Auto")) || $data[$source_field] != "") {
                            $parent_src = $data[$source_field];
                        }
                    }
                    $data_arr = $this->general->$phpfunc($data[$name], $mode, $id, $data, $parent_src);
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

        $switchto_fields = $this->ws_messages_model->switchto_fields;
        $extra_cond = $this->ws_messages_model->extra_cond;

        $concat_fields = $this->db->concat_cast($switchto_fields);
        $search_cond = "(LOWER(" . $concat_fields . ") LIKE '" . $this->db->escape_like_str($term) . "%' OR LOWER(" . $concat_fields . ") LIKE '% " . $this->db->escape_like_str($term) . "%')";
        $extra_cond = ($extra_cond == "") ? $search_cond : $extra_cond . " AND " . $search_cond;

        $switch_arr = $this->ws_messages_model->getSwitchTo($extra_cond);
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
        $config_arr = $this->ws_messages_model->getListConfiguration($alias_name);
        $source_field = $config_arr['source_field'];
        $combo_config = $this->dropdown_arr[$source_field];
        $data_arr = array();
        if ($mode == "Update") {
            $data_arr = $this->ws_messages_model->getData(intval($id));
        }
        $combo_arr = $this->getSourceOptions($source_field, $mode, $id, $data_arr[0]);
        if ($rformat == "json") {
            $html_str = $this->filter->getChosenAutoJSON($combo_arr, $combo_config, TRUE, "grid");
        } else {
            if ($combo_config['opt_group'] == "Yes") {
                $combo_arr = $this->filter->makeOPTDropdown($combo_arr);
            } else {
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
     * generateWSMessageFiles method is used to write WS message file according to language
     */
    protected function generateWSMessageFiles()
    {
        $lang_folder_path = APPPATH . "language";
        try {
            if (!is_dir($lang_folder_path)) {
                throw new Exception('Language folder does not exist.');
            }
            $db_languages_arr = $this->ws_messages_model->getLanguageData("", "", "", "", "", "", "iLangId");

            if (!is_array($db_languages_arr) || count($db_languages_arr) == 0) {
                throw new Exception('Languages not found..!');
            }

            $db_ws_message_arr = $this->ws_messages_model->getWSMessageData($this->db->protect("mwm.eStatus") . " = " . $this->db->escape("Active"), "", "", "", "", "", "iWSMessageId");

            foreach ($db_languages_arr as $key => $val) {

                $iLanguageId = $val[0]['iLangId'];
                $vLangCode = $val[0]['vLangCode'];
                $langCode = strtolower($vLangCode);
                $language_code_folder_path = $lang_folder_path . DS . $langCode;
                if (!is_dir($language_code_folder_path)) {
                    $this->general->createFolder($language_code_folder_path);
                }
                $module_file = $language_code_folder_path . DS . 'webservice_lang.php';
                $fp = fopen($module_file, "w");
                if (!$fp) {
                    throw new Exception("You do not have permission to create WS message file..!");
                }
                $this->general->createPermission($module_file);
                if (!(is_writable($module_file))) {
                    throw new Exception("You do not have permission to write into WS message file..!");
                }

                $extra_cond = $this->db->protect("mwml.vLangCode") . " = " . $this->db->escape($vLangCode);
                $db_ws_message_data = $this->ws_messages_model->getWSMessageLangData($extra_cond);

                if (!is_array($db_ws_message_data) || count($db_ws_message_data) == 0) {
                    continue;
                }

                $label_str = "";
                foreach ($db_ws_message_data as $inkey => $inval) {
                    $ws_message_id = $inval['iWSMessageId'];
                    if (!is_array($db_ws_message_arr[$ws_message_id][0]) || count($db_ws_message_arr[$ws_message_id][0]) == 0) {
                        continue;
                    }
                    $api_name = $db_ws_message_arr[$ws_message_id][0]['vAPIName'];
                    $code = $db_ws_message_arr[$ws_message_id][0]['vCode'];
                    $message = strip_tags($inval['tMessage']);
                    $message = str_replace("\r\n", '<br>', $message);
                    $message = str_replace(array("\r", "\n"), '<br>', $message);
                    $message = str_replace(array("\r", "\n", '"', "\\"), array('<br>', "<br>", "'", ""), $message);

                    $ws_message_str .= '
$lang["' . $api_name . '"]["' . $code . '"] = "' . $message . '";';
                }

                $content = '<?php   
' . $ws_message_str;
                fwrite($fp, $content);
                fclose($fp);
            }

            $success = 1;
            $message = "WS Message files created successfully..!";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $res_arr['success'] = $success;
        $res_arr['message'] = $message;

        return $res_arr;
    }
}
