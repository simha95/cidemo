<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Language Labels Controller
 *
 * @category admin
 *            
 * @package languagelabels
 * 
 * @subpackage controllers
 * 
 * @module Language Labels
 * 
 * @class Cit_Languagelabels.php
 * 
 * @path application\admin\languagelabels\controllers\Cit_Languagelabels.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Cit_Languagelabels extends Languagelabels
{

    /**
     * generateLanguageLabelFiles method is used to write language label file according to language
     */
    protected function generateLanguageLabelFiles()
    {
        $lang_folder_path = APPPATH . "language";
        try {
            if (!is_dir($lang_folder_path)) {
                throw new Exception('Language folder does not exist.');
            }
            $db_languages_arr = $this->languagelabels_model->getLanguageData("", "", "", "", "", "", "iLangId");

            if (!is_array($db_languages_arr) || count($db_languages_arr) == 0) {
                throw new Exception('Languages not found..!');
            }

            $db_labels_arr = $this->languagelabels_model->getLanguageLabelData($this->db->protect("mllt.eStatus") . " = " . $this->db->escape("Active"), "", "", "", "", "", "iLanguageLabelId");
            foreach ($db_languages_arr as $val) {
                $lang_code = $val[0]['vLangCode'];
                $language_code_folder_path = $lang_folder_path . DS . strtolower($lang_code);
                if (!is_dir($language_code_folder_path)) {
                    $this->general->createFolder($language_code_folder_path);
                }
                $module_file = $language_code_folder_path . DS . 'general_lang.php';
                $fp = fopen($module_file, "w");
                if (!$fp) {
                    throw new Exception("You do not have permission to create language label file..!");
                }
                $this->general->createPermission($module_file);
                if (!(is_writable($module_file))) {
                    throw new Exception("You do not have permission to write into language label file..!");
                }

                $extra_cond = $this->db->protect("mllt_lang.vLangCode") . " = " . $this->db->escape($lang_code);
                $db_label_data = $this->languagelabels_model->getLanguageLabelLangData($extra_cond);

                if (!is_array($db_label_data) || count($db_label_data) == 0) {
                    continue;
                }

                $label_str = "";
                foreach ($db_label_data as $inval) {
                    $label_id = $inval['iLanguageLabelId'];
                    if (!is_array($db_labels_arr[$label_id][0]) || count($db_labels_arr[$label_id][0]) == 0) {
                        continue;
                    }
                    $label = $db_labels_arr[$label_id][0]['vLabel'];
                    $title = strip_tags($inval['vTitle']);
                    $title = str_replace("\r\n", '<br>', $title);
                    $title = str_replace(array("\r", "\n"), '<br>', $title);
                    $title = str_replace(array("\r", "\n", '"', "\\"), array('<br>', "<br>", "'", ""), $title);

                    $label_str .= '
$lang["' . $label . '"] = "' . $title . '";';
                }

                $content = '<?php 
' . $label_str;
                fwrite($fp, $content);
                fclose($fp);
            }

            $success = 1;
            $message = "Langugae files created successfully..!";
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        return array(
            'success' => $success,
            'message' => $message
        );
    }

    public function import()
    {
        $this->session->unset_userdata('lbl_import_file');
        $render_arr = array(
            "upload_url" => "languagelabels/languagelabels/uploadImportFiles"
        );
        $this->loadView('languagelabels_import', $render_arr);
    }

    public function uploadImportFiles()
    {
        $this->load->library('upload');
        $this->general->createUploadFolderIfNotExists('__temp');
        $temp_folder_path = $this->config->item('admin_upload_temp_path');
        $temp_folder_url = $this->config->item('admin_upload_temp_url');

        $old_data = $this->input->get_post('oldFile');

        $upload_files = $_FILES['Filedata'];
        list($file_name, $ext) = $this->general->get_file_attributes($upload_files["name"]);

        $upload_config['upload_path'] = $temp_folder_path;
        $upload_config['allowed_types'] = '*';
        $upload_config['max_size'] = 30720;
        $upload_config['file_name'] = $file_name;
        $this->upload->initialize($upload_config);

        try {
            if ($upload_files['name'] == "") {
                throw new Exception($this->general->processMessageLabel('ACTION_UPLOAD_FILE_NOT_FOUND_C46_C46_C33'));
            }
            if (in_array($ext, $this->config->item("IMAGE_EXTENSION_ARR"))) {
                $file_type = "image";
            } else {
                $file_type = "file";
            }
            if (!$this->upload->do_upload('Filedata')) {
                $upload_error = $this->upload->display_errors('', '');
                throw new Exception($upload_error);
            } else {
                $data = $this->upload->data();
                $res_msg = "FILE_UPLOAD_OK";
            }

            $file_name = $data['file_name'];
            $ret_arr['success'] = 1;
            $ret_arr['message'] = $res_msg;
            $ret_arr['uploadfile'] = $file_name;
            $ret_arr['oldfile'] = $file_name;
            $ret_arr['fileURL'] = $temp_folder_url . $file_name;
            $ret_arr['fileType'] = $file_type;
            $this->session->set_userdata('lbl_import_file', $file_name);

            if (is_file($temp_folder_path . $old_data) && trim($old_data) != "") {
                unlink($temp_folder_path . $old_data);
            }
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function importAction()
    {
        try {
            if (!$this->session->userdata('lbl_import_file') || $this->session->userdata('lbl_import_file') == "") {
                throw new Exception("Please upload csv file.");
            }

            $ret_arr['success'] = 1;
            $ret_arr['redHash'] = "languagelabels/languagelabels/importStep2";
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
            $ret_arr['redHash'] = "languagelabels/languagelabels/import";
        }
        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function importStep2()
    {
        $res_arr = $this->getImportFiledata();
        $render_arr = array();
        $render_arr['data'] = $res_arr['data'];
        $render_arr['label_data'] = $res_arr['db_data'];
        $this->loadView("languagelabels_importstep2", $render_arr);
    }

    public function importStep2Action()
    {
        try {
            $result_arr = $this->getImportFiledata();
            $data = $result_arr['data'];
            $label_data = $result_arr['db_data'];
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $lv) {
                    $val_updated = FALSE;
                    $up_arr = array(
                        'vModule' => $lv['vModule'],
                        'eStatus' => $lv['eStatus']
                    );
                    if (array_key_exists($lv['vLabel'], $label_data)) {
                        $id = $label_data[$lv['vLabel']][0]['iLanguageLabelId'];
                        if (trim($label_data[$lv['vLabel']][0]['vValue']) == trim($lv['vValue'])) {
                            continue;
                        }
                        $up_arr['vLabel'] = $lv['vLabel'];
                        $val_updated = $this->languagelabels_model->update($up_arr, intval($id));
                        $mode = "Update";
                    } else {
                        $up_arr['vLabel'] = $lv['vLabel'];
                        $val_updated = $this->languagelabels_model->insert($up_arr);
                        $mode = "Add";
                    }

                    $db_lang_data = array();
                    if ($mode == "Update") {
                        $extra_lang_cond = "iLanguageLabelId = '" . $id . "'";
                        $db_lang_data = $this->languagelabels_model->getLangData($extra_lang_cond);
                    }
                    if ($this->config->item("MULTI_LINGUAL_PROJECT") == 'Yes') {
                        $prlang = $this->config->item("PRIME_LANG");
                    } else {
                        $prlang = 'EN';
                    }
                    $exlang_arr = $this->config->item("OTHER_LANG");

                    // primary language operations
                    $primary_arr = array(
                        'vTitle' => $lv['vValue']
                    );
                    if (is_array($db_lang_data[$prlang]) && count($db_lang_data[$prlang]) > 0) {
                        $extra_lang_cond = "iLanguageLabelId = '" . $id . "' AND vLangCode = '" . $prlang . "'";
                        $this->languagelabels_model->updateLang($primary_arr, $extra_lang_cond);
                    } else {
                        $primary_arr["iLanguageLabelId"] = $id;
                        $primary_arr["vLangCode"] = $prlang;
                        $this->languagelabels_model->insertLang($primary_arr);
                    }
                    // other language operations
                    if (is_array($exlang_arr) && count($exlang_arr) > 0) {
                        foreach ((array) $exlang_arr as $mlVal) {
                            $other_arr = array();
                            $dest_lang = strtolower($mlVal);
                            $src_lang = strtolower($prlang);
                            $dest_lang_data = $this->general->languageTranslation($src_lang, $dest_lang, $lv['vValue']);
                            $other_arr["vTitle"] = $dest_lang_data;
                            if (is_array($db_lang_data[$mlVal]) && count($db_lang_data[$mlVal]) > 0) {
                                $extra_lang_cond = "iLanguageLabelId = '" . $id . "' AND vLangCode = '" . $mlVal . "'";
                                $this->languagelabels_model->updateLang($other_arr, $extra_lang_cond);
                            } else {
                                $other_arr["iLanguageLabelId"] = $id;
                                $other_arr["vLangCode"] = $mlVal;
                                $this->languagelabels_model->insertLang($other_arr);
                            }
                        }
                    }
                }
            }

            $this->generateLanguageLabelFiles();
            $target_path = $this->config->item('admin_upload_temp_path') . $this->session->userdata('lbl_import_file');
            if (is_file($target_path)) {
                unlink($target_path);
            }

            $ret_arr['success'] = 1;
            $ret_arr['message'] = "Data imported successfully.";
            $ret_arr['redHash'] = "languagelabels/languagelabels/index";
        } catch (Exception $e) {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = $e->getMessage();
            $ret_arr['redHash'] = "languagelabels/languagelabels/import";
        }

        echo json_encode($ret_arr);
        $this->skip_template_view();
    }

    public function getImportFiledata()
    {
        $data = $label_data = array();
        try {
            $target_path = $this->config->item('admin_upload_temp_path') . $this->session->userdata('lbl_import_file');
            if ($this->session->userdata('lbl_import_file') == "" || !is_file($target_path)) {
                throw new Exception("Please upload a file to import labels.");
            }
            $config['file_path'] = $target_path;
            $config['include_first'] = 1;
            $config['encode_data'] = 1;
            $config['separator'] = ",";

            $data_fields_arr = array('vLabel' => 'Label', 'vValue' => 'Value', 'vModule' => 'Module', 'eStatus' => 'Status');
            $upload_fields = array_keys($data_fields_arr);

            $this->load->library('csv_import');
            $this->csv_import->setconfigs($config, $upload_fields);
            $data = $this->csv_import->importfile();

            if (is_array($data) && count($data) > 0) {
                $labels = array();
                foreach ($data as $lv) {
                    if (!in_array($lv['vLabel'], $labels)) {
                        $labels[] = $lv['vLabel'];
                    }
                }
                $labels = array_unique($labels);
                $labels = array_filter($labels);
                if (is_array($labels) && count($labels) > 0) {
                    $label_cond = "mllt.vLabel IN ('" . implode("','", $labels) . "')";
                    $join = array(array('table' => 'mod_language_label_lang mlll', 'cond' => "mlll.iLanguageLabelId = mllt.iLanguageLabelId AND mlll.vLangCode='EN'", 'type' => 'left'));
                    $label_data = $this->languagelabels_model->getLanguageLabelData($label_cond, "mllt.iLanguageLabelId,mllt.vLabel,mllt.vModule,mllt.eStatus,mlll.vTitle vValue", "", "", "", $join, "vLabel");
                }
            }
        } catch (Exception $e) {
            $data = $label_data = array();
        }

        return array(
            'data' => $data,
            'db_data' => $label_data
        );
    }
}
