<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of System Settings Model
 *
 * @category models
 *            
 * @package general
 *
 * @module SystemSettings
 * 
 * @class Systemsettings.php
 * 
 * @path application\models\general\Systemsettings.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Systemsettings extends CI_Model
{

    protected $_settings_array = array();

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createQueryLogPath();
        $this->setSettingsFromDB();
        if ($_ENV['debug_action'] === FALSE || $this->config->item('is_admin') == 1) {
            $this->db->db_debug = FALSE;
        } else {
            $this->db->db_debug = TRUE;
        }
        $_cache_prefer = 0;
        if ($this->applyQueryCacheSettings()) {
            $this->setCacheTablesList();
            $_cache_prefer = 1;
        }
        if ($this->config->item('is_admin') == 1) {
            $this->load->library('ci_admin');
            $this->load->library('ci_local');
            $this->ci_admin->addMultiLingualFile();
            $this->setAdminLangSettings();
            $this->setAdminThemeSettings();
            if ($this->config->item('ADMIN_ASSETS_APPCACHE') == "Y" || $this->config->item('GRID_SEARCH_PREFERENCES') == "Y") {
                $_cache_prefer = 1;
            }
        } elseif ($this->config->item('is_front') == 1) {
            $this->load->library('ci_front');
            $this->ci_front->addMultiLingualFile();
            $this->setFrontLangSettings();
        }
        $this->config->set_item('__CACHE_PREFERENCES', $_cache_prefer);
        $this->checkOSPlatform();
    }

    /**
     * setSettingsFromDB method is used to get all settings from the mod_setting table.
     */
    private function setSettingsFromDB()
    {
        $this->db->select("vName, vValue");
        $result_obj = $this->db->get('mod_setting');
        $result = is_object($result_obj) ? $result_obj->result_array() : array();
        for ($i = 0; $i < count($result); $i++) {
            $this->_settings_array[$result[$i]['vName']] = $result[$i]['vValue'];
            $this->config->set_item($result[$i]['vName'], $result[$i]['vValue']);
        }
        if ($this->config->item("COPYRIGHTED_TEXT")) {
            $copyright_text = str_replace(array("#CURRENT_YEAR#", "#COMPANY_NAME#"), array(date("Y"), $this->config->item("COMPANY_NAME")), $this->config->item("COPYRIGHTED_TEXT"));
            $this->config->set_item("COPYRIGHTED_TEXT", $copyright_text);
        }
        $_ENV['debug_action'] = ($this->config->item('PROJECT_DEBUG_LEVEL') == "production") ? FALSE : TRUE;
        $this->db->max_fetch_limit = $this->config->item('db_max_limit');
    }

    /**
     * applyQueryCacheSettings method is used to assign query caching settings.
     */
    public function applyQueryCacheSettings()
    {
        if ($this->config->item('DATABASE_QUERY_CACHING') != "Y") {
            return FALSE;
        }
        $query_cache_dir = $this->config->item('query_cache_path');
        if (!is_dir($query_cache_dir)) {
            $this->general->createFolder($query_cache_dir);
        }

        if (is_dir($query_cache_dir)) {
            $this->db->cache_on();
            $this->db->cache_set_path($query_cache_dir);
            $this->db->cache_autodel = TRUE;
            return TRUE;
        }
        return FALSE;
    }

    private function setCacheTablesList()
    {
        $cache_expire_calc = array(
            '1H' => 1 * 60 * 60,
            '12H' => 12 * 60 * 60,
            '1D' => 1 * 24 * 60 * 60,
            '1W' => 7 * 24 * 60 * 60,
            '15D' => 15 * 24 * 60 * 60,
            '1M' => 30 * 24 * 60 * 60,
            '3M' => 90 * 24 * 60 * 60,
            '6M' => 180 * 24 * 60 * 60,
            'FOREVER' => 'FOREVER'
        );
        $cache_tables = $expire_times = array();
        $allow_cache_tables = array(
            'mod_cache_tables' => $cache_expire_calc['1D']
        );
        $this->config->set_item("CACHE_ALLOW_TABLES", array_keys($allow_cache_tables));
        $this->config->set_item("CACHE_EXPIRE_TIMES", $allow_cache_tables);
        $this->db->select("vTableName, eExpireTime");
        $this->db->where("eStatus", "Active");
        $db_cache_tbls_obj = $this->db->get("mod_cache_tables");
        $db_cache_tbls = is_object($db_cache_tbls_obj) ? $db_cache_tbls_obj->result_array() : array();
        if (is_array($db_cache_tbls) && count($db_cache_tbls) > 0) {
            for ($i = 0; $i < count($db_cache_tbls); $i++) {
                $tbl_name = $db_cache_tbls[$i]['vTableName'];
                $expire_time = $db_cache_tbls[$i]['eExpireTime'];
                $cache_tables[] = $tbl_name;
                $expire_times[$tbl_name] = $cache_expire_calc[$expire_time];
            }
        }
        $this->config->set_item("CACHE_ALLOW_TABLES", $cache_tables);
        $this->config->set_item("CACHE_EXPIRE_TIMES", $expire_times);
    }

    /**
     * setFrontLangSettings method is used to get data from mod_language table, if project is multilingual.
     */
    private function setFrontLangSettings()
    {
        if ($this->config->item('MULTI_LINGUAL_PROJECT') != "Yes") {
            return;
        }
        $this->db->where("eStatus = 'Active'");
        $result_obj = $this->db->get('mod_language');
        $result = is_object($result_obj) ? $result_obj->result_array() : array();
        $sess_lang_id = 'EN';
        $sess_lang_title = 'English';
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['ePrimary'] == "Yes") {
                $sess_lang_id = $result[$i]['vLangCode'];
                $sess_lang_title = $result[$i]['vLangTitle'];
                break;
            }
        }
        if (!$this->session->userdata('sess_lang_id')) {
            $this->session->set_userdata('sess_lang_id', $sess_lang_id);
        }
        if (!$this->session->userdata('sess_lang_title')) {
            $this->session->set_userdata('sess_lang_title', $sess_lang_title);
        }
        $this->config->set_item('sess_lang_id', $this->session->userdata('sess_lang_id'));
        $this->config->set_item('sess_lang_title', $this->session->userdata('sess_lang_title'));
        if ($this->session->userdata('sess_lang_id')) {
            $this->db->from("mod_setting AS ms");
            $this->db->select("ms.vName");
            $this->db->select("IF(" . $this->db->protect("ms.eLang") . " = " . $this->db->escape("Yes") . ", " . $this->db->protect("msl.vValue") . ", " . $this->db->protect("ms.vValue") . ") AS " . $this->db->protect("lang_value"), FALSE);
            $this->db->join("mod_setting_lang AS msl", "msl.vName = ms.vName", "left");
            $this->db->where("msl.vLangCode", $this->session->userdata('DEFAULT_LANG'));
            $result_setting_obj = $this->db->get();
            $result_setting = is_object($result_setting_obj) ? $result_setting_obj->result_array() : array();
            for ($i = 0; $i < count($result_setting); $i++) {
                if ($result_setting[$i]['lang_value'] != '') {
                    $this->_settings_array[$result_setting[$i]['vName']] = $result_setting[$i]['lang_value'];
                    $this->config->set_item($result_setting[$i]['vName'], $result_setting[$i]['lang_value']);
                }
            }
            if ($this->config->item("COPYRIGHTED_TEXT")) {
                $copyright_text = str_replace(array("#CURRENT_YEAR#", "#COMPANY_NAME#"), array(date("Y"), $this->config->item("COMPANY_NAME")), $this->config->item("COPYRIGHTED_TEXT"));
                $this->config->set_item("COPYRIGHTED_TEXT", $copyright_text);
            }
        }
    }

    /**
     * setAdminLangSettings method is used to get data from mod_language table, if project is multilingual.
     */
    private function setAdminLangSettings()
    {
        if ($this->config->item('MULTI_LINGUAL_PROJECT') != "Yes") {
            return;
        }
        $this->db->where("eStatus = 'Active'");
        $result_obj = $this->db->get('mod_language');
        $result = is_object($result_obj) ? $result_obj->result_array() : array();
        $prime_done = false;
        $other_lang = $lang_info = array();
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['ePrimary'] == "Yes" && !$prime_done) {
                $prime_done = true;
                $prime_lang = $result[$i]['vLangCode'];
            } else {
                $other_lang[] = $result[$i]['vLangCode'];
            }
            $lang_info[$result[$i]['vLangCode']]['vLangTitle'] = $result[$i]['vLangTitle'];
        }
        $this->config->set_item("PRIME_LANG", $prime_lang);
        $this->config->set_item("OTHER_LANG", $other_lang);
        $this->config->set_item("LANG_INFO", $lang_info);
        if ($this->session->userdata('DEFAULT_LANG') == "") {
            $this->session->set_userdata('DEFAULT_LANG', $prime_lang);
        }
        $this->config->set_item('DEFAULT_LANG', $this->session->userdata('DEFAULT_LANG'));
        if ($this->session->userdata('DEFAULT_LANG') != '') {
            $this->db->from("mod_setting AS ms");
            $this->db->select("ms.vName");
            $this->db->select("IF(" . $this->db->protect("ms.eLang") . " = " . $this->db->escape("Yes") . ", " . $this->db->protect("msl.vValue") . ", " . $this->db->protect("ms.vValue") . ") AS " . $this->db->protect("lang_value"), FALSE);
            $this->db->join("mod_setting_lang AS msl", "msl.vName = ms.vName", "left");
            $this->db->where("msl.vLangCode", $this->session->userdata('DEFAULT_LANG'));
            $result_setting_obj = $this->db->get();
            $result_setting = is_object($result_setting_obj) ? $result_setting_obj->result_array() : array();
            for ($i = 0; $i < count($result_setting); $i++) {
                if ($result_setting[$i]['lang_value'] != '') {
                    $this->_settings_array[$result_setting[$i]['vName']] = $result_setting[$i]['lang_value'];
                    $this->config->set_item($result_setting[$i]['vName'], $result_setting[$i]['lang_value']);
                }
            }
        }
    }

    /**
     * setAdminThemeSettings method is used to set theme configs.
     */
    private function setAdminThemeSettings()
    {
        $admin_theme_settings = $this->config->item('ADMIN_THEME_SETTINGS');
        $theme_settings_arr = explode("@", $admin_theme_settings);
        $main_theme = $theme_settings_arr[0];
        switch ($main_theme) {
            case "metronic":
                $theme_color = (empty($theme_settings_arr[1])) ? "default" : $theme_settings_arr[1];
                $theme_custom = (empty($theme_settings_arr[2])) ? "none" : $theme_settings_arr[2];
                $this->config->set_item("ADMIN_THEME_DISPLAY", "metronic");
                $this->config->set_item("ADMIN_THEME_PATTERN", "theme_" . $theme_color . ".css");
                $this->config->set_item("ADMIN_THEME_CUSTOMIZE", $theme_custom . ".css");
                break;
            case "cit":
                $theme_color = (empty($theme_settings_arr[1])) ? "default" : $theme_settings_arr[1];
                $theme_custom = (empty($theme_settings_arr[2])) ? "none" : $theme_settings_arr[2];
                $this->config->set_item("ADMIN_THEME_DISPLAY", "cit");
                $this->config->set_item("ADMIN_THEME_PATTERN", "theme_" . $theme_color . ".css");
                $this->config->set_item("ADMIN_THEME_CUSTOMIZE", $theme_custom . ".css");
                break;
            default:
                $pattern_arr = explode("||", $theme_settings_arr[1]);
                $theme_custom = (trim($theme_settings_arr[2]) == "") ? "none" : $theme_settings_arr[2];
                $this->config->set_item("ADMIN_THEME_DISPLAY", "supr");
                $this->config->set_item("ADMIN_THEME_PATTERN", "theme_pattern.css");
                $this->config->set_item("ADMIN_THEME_PATTERN_HEAD", $pattern_arr[0]);
                $this->config->set_item("ADMIN_THEME_PATTERN_LEFT", $pattern_arr[1]);
                $this->config->set_item("ADMIN_THEME_PATTERN_BODY", $pattern_arr[2]);
                $this->config->set_item("ADMIN_THEME_CUSTOMIZE", $theme_custom . ".css");
                break;
        }
        $this->config->set_item('ADMIN_THEME_CREATE', "0");
        if ($this->session->userdata('vUserName') == $this->config->item("ADMIN_USER_NAME")) {
            if ($this->config->item('ADMIN_THEME_ACTIVATE') == "Y") {
                $this->config->set_item('ADMIN_THEME_CREATE', "1");
            }
        }
    }

    /**
     * getSettings method is used to get settings of field.
     * 
     * @return array or false.
     */
    public function getSettings($var_name)
    {
        if (array_key_exists($var_name, $this->_settings_array)) {
            return $this->_settings_array[$var_name];
        } else {
            return FALSE;
        }
    }

    /**
     * getAllSettings method is used to get all system settings.
     * 
     * @return array array of all settings.
     */
    public function getAllSettings()
    {
        return $this->_settings_array;
    }

    /**
     * getMenuArray method is used to get all menus.
     * 
     * @param string $extra_cond extra condition will be used for where clause.
     * 
     * @return array $ret_arr menu array will be return.
     */
    public function getMenuArray($extra_cond = '', $type = "")
    {
        $fields = array(
            "iAdminMenuId", "iParentId", "vMenuDisplay", "vIcon", "vURL",
            "vUniqueMenuCode", "iColumnNumber", "iSequenceOrder"
        );
        $this->db->select($fields);
        if ($extra_cond != '') {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->db->where('eStatus', 'Active');
        $this->db->order_by('iParentId', 'ASC');
        $this->db->order_by('iSequenceOrder', 'ASC');
        $result_obj = $this->db->get('mod_admin_menu');
        $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
        $encrypt_arr = $this->config->item("FRAMEWORK_ENCRYPTS");
        $return_arr = array();
        for ($i = 0; $i < count($result_arr); $i++) {
            $data_arr = array();
            $admin_menu_id = $result_arr[$i]['iAdminMenuId'];
            $parent_id = $result_arr[$i]['iParentId'];
            $unique_menu_code = $result_arr[$i]['vUniqueMenuCode'];
            $menu_display = $result_arr[$i]['vMenuDisplay'];
            $column_number = $result_arr[$i]['iColumnNumber'];
            $v_url = $result_arr[$i]['vURL'];
            $v_icon = $result_arr[$i]['vIcon'];
            $is_external = $this->general->isExternalURL($v_url);

            $menu_display_lang = $this->general->getDisplayLabel("Generic", $menu_display, "label");
            $menu_display_text = $this->lang->line($menu_display_lang);
            if ($v_icon == "") {
                $v_icon = ($parent_id > 0) ? "icomoon-icon-file" : "icomoon-icon-stats-up";
            }
            $data_arr['id'] = $admin_menu_id;
            $data_arr['parent_id'] = $parent_id;
            $data_arr['label'] = $menu_display;
            $data_arr['label_lang'] = ($menu_display_text == '') ? $menu_display : $menu_display_text;
            $data_arr['icon'] = $v_icon;
            $data_arr['code'] = strtolower($unique_menu_code);
            if ($is_external) {
                $data_arr['url'] = $v_url;
                $data_arr['url_enc'] = $v_url;
                $data_arr['target'] = "_blank";
            } else {
                $extra_attr = '';
                $url_arr = explode("|", $v_url);
                if (is_array($url_arr) && count($url_arr) > 1) {
                    $url_dec = $url_arr[0];
                    for ($j = 1; $j < count($url_arr); $j+=2) {
                        $param_key = $url_arr[$j];
                        $param_val = $url_arr[$j + 1];
                        if (is_array($encrypt_arr) && in_array($param_key, $encrypt_arr)) {
                            $param_val = $this->general->getAdminEncodeURL($param_val);
                        }
                        $extra_attr .= "|" . $param_key . "|" . $param_val;
                    }
                } else {
                    $url_dec = $v_url;
                }
                $url_enc = $this->general->getAdminEncodeURL($url_dec);
                $data_arr['target'] = "_self";
                $data_arr['url'] = $this->config->item('admin_url') . "#" . $url_enc . "" . $extra_attr;
            }
            if ($type == "Top") {
                if ($column_number < 0 || $column_number > 3) {
                    $column_number = 1;
                }
                $return_arr[$parent_id][$column_number][] = $data_arr;
            } else {
                $return_arr[$parent_id][] = $data_arr;
            }
        }
        $hurl_enc = $this->general->getAdminEncodeURL("dashboard/dashboard/sitemap");
        $home_arr['url'] = $this->config->item('admin_url') . "#" . $hurl_enc;

        $purl_enc = $this->general->getAdminEncodeURL("user/admin/add", 0);
        $pmode_enc = $this->general->getAdminEncodeURL("Update", 0);
        $pid_enc = $this->general->getAdminEncodeURL($this->session->userdata('iAdminId'));

        $profile_arr['url'] = $this->config->item('admin_url') . "#" . $purl_enc . "|mode|" . $pmode_enc . "|id|" . $pid_enc . "|tEditFP|true|hideCtrl|true";
        $profile_arr['icon'] = "icomoon-icon-user-3";
        $profile_arr['label'] = "Edit Profile";
        $profile_arr['label_lang'] = $this->lang->line('GENERIC_EDIT_PROFILE');
        $profile_arr['code'] = "static_edit_profile";

        $curl_enc = $this->general->getAdminEncodeURL("user/login/changepassword");
        $password_arr['url'] = $this->config->item('admin_url') . "#" . $curl_enc;
        $password_arr['icon'] = "icomoon-icon-key";
        $password_arr['label'] = "Change Password";
        $password_arr['label_lang'] = $this->lang->line('GENERIC_CHANGE_PASSWORD');
        $password_arr['code'] = "static_change_password";

        $lurl_enc = $this->general->getAdminEncodeURL("user/login/logout");
        $logout_arr['url'] = $this->config->item('admin_url') . $lurl_enc;
        $logout_arr['icon'] = "icomoon-icon-exit";
        $logout_arr['label'] = "Log Out";
        $logout_arr['label_lang'] = $this->lang->line('GENERIC_LOGOUT');
        $logout_arr['code'] = "static_logout";

        $menu_list = array(
            'menu' => $return_arr,
            'home' => $home_arr,
            'password' => $password_arr,
            'profile' => $profile_arr,
            'logout' => $logout_arr
        );
        $menu_callback = $this->config->item('menu_callback');
        if ($menu_callback != "" && method_exists($this->general, $menu_callback)) {
            $menu_list = $this->general->$menu_callback($menu_list, $type);
        }
        return $menu_list;
    }

    /**
     * getAdminAccessModulesList method is using to get list of accessible modules.
     * 
     * @return array $ret_arr returns array of modules
     */
    public function getAdminAccessModulesList()
    {
        $ADMIN_GROUP_NAME = $this->config->item('ADMIN_GROUP_NAME');
        $group_code = $this->session->userdata('vGroupCode');
        $group_id = $this->session->userdata('iGroupId');
        $extra_menu_cond = '';
        $is_admin_group = false;
        $db_group_assoc_rights = array();
        if ($group_code == $ADMIN_GROUP_NAME) {
            $is_admin_group = true;
        } else {
            $extra_cond = $this->db->protect("mgr.iGroupId") . " = " . $this->db->escape($group_id);
            $db_group_assoc_rights = $this->getAdminGroupAccessRights($extra_cond, "", "", "", "", "iAdminMenuId");
            $db_group_rights = (is_array($db_group_assoc_rights)) ? array_keys($db_group_assoc_rights) : array();
            if (is_array($db_group_rights) && count($db_group_rights) > 0) {
                $extra_menu_cond = $this->db->protect("iAdminMenuId") . " IN ('" . implode("','", $db_group_rights) . "')";
            } else {
                $extra_menu_cond = $this->db->protect("iAdminMenuId") . " = 0";
            }
        }

        return array(
            'admin' => $is_admin_group,
            'menuArr' => $db_group_assoc_rights,
            'menuCond' => $extra_menu_cond
        );
    }

    /**
     * getAdminUserGroupDetails method is used to get group details of logged user.
     * 
     * @return array $db_admin_group group details will be returned.
     */
    public function getAdminUserGroupDetails()
    {
        $group_id = $this->session->userdata('iGroupId');
        $this->db->select("iGroupId, vGroupName, vGroupCode");
        $this->db->from("mod_group_master");
        $this->db->where("iGroupId", $group_id);
        $db_admin_group_obj = $this->db->get();
        return (is_object($db_admin_group_obj) ? $db_admin_group_obj->result_array() : array());
    }

    /**
     * getAdminGroupAccessRights method is used to get access rights of group.
     * 
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * 
     * @param string $fields fields are either array or string.
     * 
     * @param string $order_by order_by is to append order by condition.
     * 
     * @param string $group_by group_by is to append group by condition.
     * 
     * @param type $left_join left join condition is used.
     * 
     * @param string $assoc_field field name for associative array will be used.
     * 
     * @return array $list_data array of access right will be return.
     */
    public function getAdminGroupAccessRights($extracond = "", $fields = "", $orderby = "", $groupby = "", $left_join = "", $assoc_field = "")
    {
        if (empty($fields)) {
            $fields = array(
                "mgr.iGroupId", "mgr.iAdminMenuId", "mgr.eList", "mgr.eView", "mgr.eAdd",
                "mgr.eUpdate", "mgr.eDelete", "mgr.eExport", "mgr.ePrint"
            );
        }
        if (empty($left_join)) {
            $join_arr = array();
            $join_arr[0]['table_name'] = 'mod_admin_menu mam';
            $join_arr[0]['cond'] = 'mam.iAdminMenuId = mgr.iAdminMenuId';
            $join_arr[0]['type'] = 'inner';
        }

        $this->db->select($fields);
        for ($i = 0; $i < count($join_arr); $i++) {
            $this->db->join($join_arr[$i]['table_name'], $join_arr[$i]['cond'], $join_arr[$i]['type']);
        }
        if ($extracond != "") {
            $this->db->where($extracond, FALSE, FALSE);
        }
        if ($groupby != '') {
            $this->db->group_by($groupby);
        }
        if ($orderby != '') {
            $this->db->order_by($orderby);
        } else {
            $this->db->order_by("mam.iParentId", "ASC");
            $this->db->order_by("mam.iSequenceOrder", "ASC");
        }

        if ($assoc_field != "") {
            $list_data = $this->db->select_assoc("mod_group_rights AS mgr", $assoc_field);
            $assoc_flag = true;
        } else {
            $this->db->from("mod_group_rights AS mgr");
            $list_data_obj = $this->db->get();
            $list_data = is_object($list_data_obj) ? $list_data_obj->result_array() : array();
            $assoc_flag = false;
        }
        return $list_data;
    }

    /**
     * getSettingsMaster method is used to get all settings from the mod_setting table.
     * 
     * @param string $fields table fields will be used as parameter
     * 
     * @param string $extra_cond extra condition will be used for where clause.
     * 
     * @param string $config_type config type will be used such as Appearance, Company etc.
     * 
     * @param boolean $assoc_value true or false will be used for using associative value.
     * 
     * @return array $list_data array of mod_setting data will be return.
     */
    public function getSettingsMaster($fields = "", $extra_cond = '', $config_type = "", $assoc_value = FALSE)
    {
        if (empty($fields)) {
            $fields = array(
                "vName", "vDesc", "vValue", "iOrderBy", "vGroupType", "eConfigType", "eDisplayType", "eSource", "vSourceValue",
                "eSelectType", "vDefValue", "eLang", "vValidateCode", "vValidateMessage", "tSettingAttr", "tHelpText"
            );
        }
        $this->db->select($fields);
        $this->db->where("eStatus", "Active");
        if ($config_type != '') {
            $this->db->where("eConfigType", $config_type);
        }
        if ($extra_cond != "") {
            $this->db->where($extra_cond, FALSE, FALSE);
        }
        $this->db->order_by("iOrderBy", "ASC");
        $this->db->order_by("eConfigType", "ASC");
        if ($assoc_value != false) {
            $list_data = $this->db->select_assoc("mod_setting", $assoc_value);
        } else {
            $this->db->from("mod_setting");
            $list_data_obj = $this->db->get();
            $list_data = is_object($list_data_obj) ? $list_data_obj->result_array() : array();
        }
        return $list_data;
    }

    /**
     * updateLang method is used to update data records to the language table.
     * 
     * @param array $data data array for update into table.
     * 
     * @param string $where where is the query condition for updating.
     * 
     * @return boolean $res returns true or false.
     */
    public function updateSetting($data = array(), $where = '')
    {
        if (is_numeric($where)) {
            $this->db->where("vName", $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update("mod_setting", $data);
    }

    /**
     * insertLang method is used to insert data records to the language table.
     * 
     * @param array $data data array for insert into table.
     * 
     * @return numeric $insert_id returns last inserted id.
     */
    public function insertLang($data = array())
    {
        $this->db->insert("mod_setting_lang", $data);
        return $this->db->insert_id();
    }

    /**
     * updateLang method is used to update data records to the language table.
     * 
     * @param array $data data array for update into table.
     * 
     * @param string $where where is the query condition for updating.
     * 
     * @return boolean $res returns TRUE or FALSE.
     */
    public function updateLang($data = array(), $where = '')
    {
        if (intval($where) > 0) {
            $this->db->where("vName", $where);
        } else {
            $this->db->where($where, FALSE, FALSE);
        }
        return $this->db->update("mod_setting_lang", $data);
    }

    /**
     * getLangData method is used to get data records from language table.
     * 
     * @param string $extra_cond extra_cond is the query condition for getting filtered data.
     * 
     * @param string $fields fields are comma seperated values.
     * 
     * @param string $order_by order_by is to append order by condition.
     * 
     * @param string $group_by group_by is to append group by condition.
     * 
     * @param string $limit limit is to append limit condition.
     * 
     * @param boolean $lang_assoc lang_assoc is to differ assoc data or normal data.
     * 
     * @return array $lang_data returns lang data records array.
     */
    public function getLangData($extra_cond = "", $fields = "", $order_by = "", $group_by = "", $limit = "", $lang_assoc = TRUE)
    {
        if (empty($fields)) {
            $fields = array("mllt_lang.vName", "mllt_lang.vLangCode", "mllt_lang.vValue");
        }
        $this->db->select($fields);
        $this->db->from("mod_setting_lang AS mllt_lang");
        if (is_array($extra_cond) && count($extra_cond) > 0) {
            foreach ($extra_cond as $key => $val) {
                $this->db->where($val['field'], $val['value']);
            }
        } elseif ($extra_cond != "") {
            if (is_numeric($extra_cond)) {
                $this->db->where("mllt_lang.vName", $extra_cond);
            } else {
                $this->db->where($extra_cond, FALSE, FALSE);
            }
        }
        $this->general->getPhysicalRecordWhere('mod_setting_lang', 'mllt_lang', "AR");
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
            for ($i = 0; $i < count($lang_data); $i++) {
                $lang_assoc_data[$lang_data[$i]["vLangCode"]][$lang_data[$i]["vName"]] = $lang_data[$i]['vValue'];
            }
            $lang_data = $lang_assoc_data;
        }
        return $lang_data;
    }

    public function getQueryResult($query)
    {
        $data_obj = $this->db->query($query);
        return (is_object($data_obj) ? $data_obj->result_array() : array());
    }

    public function checkOSPlatform()
    {
        if ($this->agent->is_mobile()) {
            $this->_settings_array['LOGIN_PASSWORD_TYPE'] = 'N';
            $this->config->set_item('LOGIN_PASSWORD_TYPE', 'N');
        }
        if ($this->agent->browser() == "Internet Explorer") {
            $_admin_appcache_file = 'legacy.appcache';
        } elseif ($this->agent->browser() == "Safari" || $this->agent->is_mobile()) {
            $_admin_appcache_file = 'mobile.appcache';
        } else {
            $_admin_appcache_file = 'window.appcache';
        }
        $this->_settings_array['ADMIN_APPCACHE_FILE'] = $_admin_appcache_file;
        $this->config->set_item('ADMIN_APPCACHE_FILE', $_admin_appcache_file);
    }

    public function createQueryLogPath()
    {
        // will call one time for each day
        $day_wise_folders = array(
            'ws_query_log_path' => $this->config->item('ws_query_log_path'),
            'ns_query_log_path' => $this->config->item('ns_query_log_path'),
            'parse_query_log_path' => $this->config->item('parse_query_log_path'),
            'admin_query_log_path' => $this->config->item('admin_query_log_path'),
            'front_query_log_path' => $this->config->item('front_query_log_path')
        );
        $curr_date = date("Y-m-d");
        $past_days = $this->config->item('QUERY_LOG_TRUNCATE');

        for ($i = 0; $i < $past_days; $i++) {
            $last_thirty_days[] = date('Y-m-d', strtotime(' -' . $i . ' day'));
        }

        foreach ($day_wise_folders as $key => $dir) {
            $log_new = $dir . $curr_date . DS;
            $this->config->set_item($key, $log_new);
            if (is_dir($log_new)) {
                continue;
            }
            $this->general->createFolder($log_new);
            $old_dirs = scandir($dir);
            for ($i = 0; $i < count($old_dirs); $i++) {
                if (in_array($old_dirs[$i], array(".", "..", ".svn"))) {
                    continue;
                }
                if (in_array($old_dirs[$i], $last_thirty_days)) {
                    continue;
                }
                if (is_dir($dir . $old_dirs[$i])) {
                    $this->removeLogFolder($dir . $old_dirs[$i] . DS);
                } else {
                    unlink($dir . $old_dirs[$i]);
                }
            }
        }
        return TRUE;
    }

    public function removeLogFolder($folder_path = '')
    {
        $site_path = $this->config->item('site_path');
        if (strlen($folder_path) <= strlen($site_path)) {
            return FALSE;
        }
        if (is_dir($folder_path)) {
            $handle = opendir($folder_path);
            while (FALSE !== ($inner_path = readdir($handle))) {
                $files[] = $inner_path;
            }
            foreach ($files as $filename) {
                if (in_array($filename, array(".", "..", ".svn"))) {
                    continue;
                }
                if (is_dir($folder_path . $filename)) {
                    $inner_path = $folder_path . $filename . DS;
                    $this->removeLogFolder($inner_path);
                } else {
                    unlink($folder_path . $filename);
                }
            }
            closedir($handle);
        }
        rmdir($folder_path);
        return TRUE;
    }
}
