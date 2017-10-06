<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Grid Actions Controller
 *
 * @category admin
 *            
 * @package general
 * 
 * @subpackage controllers
 * 
 * @module Gridactions
 * 
 * @class Gridactions.php
 * 
 * @path application\admin\general\controllers\Gridactions.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Gridactions extends Cit_Controller
{

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_request_params();
    }

    /**
     * _request_params method is used to set post/get/request params.
     */
    private function _request_params()
    {
        $this->get_arr = is_array($this->input->get(null)) ? $this->input->get(null) : array();
        $this->post_arr = is_array($this->input->post(null)) ? $this->input->post(null) : array();
        $this->params_arr = array_merge($this->get_arr, $this->post_arr);
        return $this->params_arr;
    }

    /**
     * index method is used to intialize index page.
     */
    public function index()
    {
        
    }

    /**
     * grid_render_action method is used to get data from grid render settings.
     */
    public function grid_render_action()
    {
        $render_module = $this->general->getAdminDecodeURL($this->params_arr['render_module']);
        $render_type = $this->params_arr['render_type'];
        $render_value = $this->params_arr['render_value'];

        $render_html = $this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS");
        try {
            if ($render_type == "general") {
                $render_html = $this->general->$render_value($this->params_arr);
            } elseif ($render_type == "extended") {
                $render_module_arr = explode("/", $render_module);
                $module_folder = trim($render_module_arr[0]);
                $module_ctrl = trim($render_module_arr[1]);

                if (empty($module_folder) || empty($module_ctrl)) {
                    throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                }

                $ctrl_prefix = $this->config->item("cu_controller_prx");
                $extend_ctrl = $ctrl_prefix . ucfirst($module_ctrl);
                $extend_init = strtolower($extend_ctrl);

                $this->load->module($module_folder . "/" . $module_ctrl);
                $this->load->module($module_folder . "/" . $extend_ctrl);

                if (is_object($this->$extend_init)) {
                    if (!method_exists($this->$extend_init, $render_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $render_html = $this->$extend_init->$render_value($this->params_arr);
                } else {
                    if (!method_exists($this->$module_ctrl, $render_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $render_html = $this->$module_ctrl->$render_value($this->params_arr);
                }
            }
        } catch (Exception $e) {
            $render_html = $e->getMessage();
        }
        echo $render_html;
        $this->skip_template_view();
    }

    /**
     * grid_submit_action method is used to perform grid settings action.
     */
    public function grid_submit_action()
    {
        $this->load->library('listing');
        $action_module = $this->general->getAdminDecodeURL($this->params_arr['action_module']);
        $action_type = $this->params_arr['action_type'];
        $action_value = $this->params_arr['action_value'];

        $action_arr = array(
            "success" => 0,
            "message" => $this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS")
        );
        try {
            if ($action_type == "general") {
                $action_arr = $this->general->$action_value($this->params_arr);
            } elseif ($action_type == "extended") {
                $action_module_arr = explode("/", $action_module);
                $module_folder = trim($action_module_arr[0]);
                $module_ctrl = trim($action_module_arr[1]);

                if (empty($module_folder) || empty($module_ctrl)) {
                    throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                }

                $ctrl_prefix = $this->config->item("cu_controller_prx");
                $extend_ctrl = $ctrl_prefix . ucfirst($module_ctrl);
                $extend_init = strtolower($extend_ctrl);
                $this->load->module($module_folder . "/" . $module_ctrl);
                $this->load->module($module_folder . "/" . $extend_ctrl);

                if (is_object($this->$extend_init)) {
                    if (!method_exists($this->$extend_init, $action_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $action_arr = $this->$extend_init->$action_value($this->params_arr);
                } else {
                    if (!method_exists($this->$module_ctrl, $action_value)) {
                        throw new Exception($this->lang->line("GENERIC_INVALID_CONFIGURATION_SETTINGS"));
                    }
                    $action_arr = $this->$module_ctrl->$action_value($this->params_arr);
                }
            } elseif ($action_type == "api") {
                $action_arr = $this->listing->callGridAPIMethod($action_value, $this->params_arr);
            }
        } catch (Exception $e) {
            $action_arr["success"] = 0;
            $action_arr['message'] = $e->getMessage();
        }
        echo json_encode($action_arr);
        $this->skip_template_view();
    }
}
