<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of WS Controller
 *
 * @category webservice
 *            
 * @package wsengine
 * 
 * @subpackage controllers
 * 
 * @module WS Controller
 * 
 * @class Wscontroller.php
 * 
 * @path application\webservice\wsengine\controllers\Wscontroller.php
 *
 * @version 4.0
 * 
 * @author CIT Dev Team
 *
 * @since 01.08.2016
 */
class Wscontroller extends Cit_Controller
{

    protected $_debug_loop = array();
    protected $_debug_curr = array();
    protected $_req_format = [
        'json' => 'application/json',
        'xml' => 'application/xml',
        'serialized' => 'application/vnd.php.serialized'
    ];

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('wschecker');
        $this->load->library('wsresponse');
    }

    /**
     * listWSMethods method is used to get all webservices list.
     */
    public function listWSMethods()
    {
        if ($_ENV['debug_action']) {
            $this->config->load('cit_webservices', TRUE);
            $all_methods = $this->config->item('cit_webservices');
        }
        $all_methods = empty($all_methods) ? array() : $all_methods;
        $render_arr = array(
            'all_methods' => $all_methods,
            'ws_url' => $this->config->item('site_url') . "WS/"
        );
        $this->smarty->assign($render_arr);
    }

    /**
     * WSExecuter method is used to get individual webservice list.
     * @param string $func_name func_name is the webservice name.
     */
    public function WSExecuter($func_arg = '', $id_arg = NULL)
    {
        header('Access-Control-Allow-Origin: *');
        $this->config->load('cit_webservices', TRUE);
        $all_methods = $this->config->item('cit_webservices');
        $res_format = NULL;
        if (stristr($func_arg, ".") !== FALSE) {
            $func_arr = explode(".", $func_arg);
            $func_name = $func_arr[0];
            $res_format = $func_arr[1];
        } else {
            $func_name = $func_arg;
        }
        if (empty($all_methods[$func_name])) {
            show_error('API code not found. Please save settings or update code.', 400);
        }
        $this->load->module($all_methods[$func_name]['folder'] . "/" . $func_name);
        //request params
        $request_arr = $this->WSRequestData($all_methods[$func_name]['method'], $id_arg);
        //data encryption process
        if ($this->config->item('WS_RESPONSE_ENCRYPTION') == "Y") {
            $request_arr = $this->wschecker->decrypt_params($request_arr);
        }
        //token and checksum validation
        $verify_res = $this->wschecker->verify_webservice($request_arr);
        if ($verify_res['success'] != "1") {
            $this->wschecker->show_error_code($verify_res);
        }
        //checking for webservice controller
        if (!is_object($this->$func_name)) {
            show_error('API code not found. Please save settings or update code.', 400);
        }
        //setup for debugger
        if (!is_null($this->input->get_post("ws_debug")) && !is_null($this->input->get_post("ws_ctrls"))) {
            //initiate debugger
            $output_arr = $this->WSDebugger($func_name, $request_arr);
            //print debug response
            $this->wsresponse->sendWSResponse($output_arr, $this->wsresponse->ws_debug_params);
        } else {
            //initiate webservice
            $start_method = "start_" . $func_name;
            if (!method_exists($this->$func_name, $start_method)) {
                show_error('API init method not found. Please save settings or update code.', 400);
            }
            $output_arr = $this->$func_name->$start_method($request_arr);
            //print output response
            $this->wsresponse->sendWSResponse($output_arr, array(), $res_format);
        }
    }

    public function WSDebugger($func_name = '', $request_arr = array())
    {
        $this->wsresponse->ws_log_file = $this->input->get_post("ws_log");
        $debug_cache_dir = $this->config->item('ws_debug_log_path');
        if (!is_dir($debug_cache_dir)) {
            $this->general->createFolder($debug_cache_dir);
        }
        $next_flow = $loop_name = '';
        if ($this->wsresponse->ws_log_file && is_file($debug_cache_dir . $this->wsresponse->ws_log_file)) {
            $_log_params = file_get_contents($debug_cache_dir . $this->wsresponse->ws_log_file);
            $_log_params = unserialize($_log_params);
            if (is_array($_log_params) && count($_log_params) > 0) {
                $this->wsresponse->ws_debug_params = $_log_params['debug'];
                $next_flow = $_log_params['next_flow'];
                $loop_name = $_log_params['loop_name'];
                $this->_debug_loop = is_array($_log_params['debug_loop']) ? $_log_params['debug_loop'] : array();
            }
        }
        $this->config->load('cit_wsdebugger', TRUE);
        $all_debugger = $this->config->item('cit_wsdebugger');
        if (empty($all_debugger[$func_name])) {
            show_error('API code not found. Please save settings or update code.', 400);
        }
        $curr_debuger = $all_debugger[$func_name];
        $input_params = $_log_params['params'];
        if ($next_flow == "") {
            $flow_keys = array_keys($curr_debuger);
            $next_flow = $flow_keys[0];
            $this->wsresponse->ws_log_file = md5("debug_" . date("YmdHis") . "_" . rand(1000, 9999));
            $rules_method = "rules_" . $func_name;
            if (method_exists($this->$func_name, $rules_method)) {
                $validation_res = $this->$func_name->$rules_method($request_arr);
                if ($validation_res["success"] == "-5") {
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
                $input_params = $validation_res['input_params'];
                $_log_params['params'] = $input_params;
                $this->wsresponse->pushDebugParams("input_params", $input_params, $input_params, $next_flow);
            } else {
                show_error('API debugger having some problem to detect next flow. Please try again.', 400);
            }
        }
        $this->_debug_curr = $curr_debuger;
        return $this->WSLogRunner($func_name, $input_params, $next_flow, $loop_name);
    }

    public function WSLogRunner($func_name = '', $input_params = array(), $curr_flow = '', $loop_name = '')
    {
        $exec_debuger = $this->_debug_curr[$curr_flow];
        if (empty($exec_debuger)) {
            show_error('API debugger having some problem to detect next flow. Please try again.', 400);
        }
        $_SESSION['__ci_exec_api_flow'] = $curr_flow;
        if ($exec_debuger['type'] == "startloop") {
            $_lp_tmp_arr = $_lp_tmp_dic = $_lp_org_arr = $_lp_loc_arr = array();
            $_lp_nam = $exec_debuger['loop'][0];
            if ($_lp_nam != '' && array_key_exists($_lp_nam, $input_params)) {
                $this->_debug_loop[] = $_lp_nam;
                $_lp_org_arr = $input_params[$_lp_nam];
                $_lp_loc_arr = &$input_params[$_lp_nam];
            } else {
                $this->_debug_loop[] = $curr_flow;
            }
            if ($exec_debuger['loop'][1] == "custom") {
                $_cus_ini = $exec_debuger['loop'][2];
                if (is_numeric($_cus_ini)) {
                    $_lp_ini = intval($_cus_ini);
                } elseif (is_array($input_params[$_cus_ini])) {
                    $_lp_ini = count($_cus_ini);
                } else {
                    $_lp_ini = intval($input_params[$_cus_ini]);
                }
                $_cus_end = $exec_debuger['loop'][3];
                if (is_numeric($_cus_end)) {
                    $_lp_end = intval($_cus_end);
                } elseif (is_array($input_params[$_cus_end])) {
                    $_lp_end = count($_cus_end);
                } else {
                    $_lp_end = intval($input_params[$_cus_end]);
                }
                $_lp_stp = $exec_debuger['loop'][4];
                $_lp_opr = $exec_debuger['loop'][5];
            } else {
                $_lp_ini = 0;
                if ($exec_debuger['loop'][1] == "number") {
                    $_lp_end = intval($exec_debuger['loop'][2]);
                } else {
                    $_lp_end = count($input_params[$exec_debuger['loop'][0]]);
                }
                $_lp_stp = 1;
                $_lp_opr = 'lt';
            }
            $_block_result = array("start_point" => $_lp_ini, "end_point" => $_lp_end, "step" => $_lp_stp, "loop" => $_lp_nam);
            $this->wsresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
            $_lp_tmp = (is_array($_lp_org_arr[0])) ? TRUE : FALSE;
            $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
            while ($_lp_cnd) {
                $_lp_inp = $input_params;
                unset($_lp_inp[$loop_name]);
                if ($_lp_tmp) {
                    if (is_array($_lp_org_arr[$_lp_ini])) {
                        $_lp_inp = $_lp_org_arr[$_lp_ini] + $input_params;
                    }
                } elseif ($_lp_nam != '') {
                    $_lp_inp[$_lp_nam] = $_lp_org_arr[$_lp_ini];
                    $_lp_org_arr[$i] = array();
                    $_lp_org_arr[$i][$_lp_nam] = $_lp_inp[$_lp_nam];
                }
                $_lp_inp['i'] = $_lp_ini;
                $_lp_inp['__dictionaries'] = $_lp_tmp_dic;
                $response = $this->WSLogRunner($func_name, $_lp_inp, $exec_debuger['next'], $loop_name);
                if (is_array($response['__dictionaries'])) {
                    $_lp_tmp_dic = $response['__dictionaries'];
                    unset($response['__dictionaries']);
                }
                if (is_array($response['__variables'])) {
                    $input_params = $this->wsresponse->grabLoopVariables($response['__variables'], $input_params);
                    unset($response['__variables']);
                }
                if ($_lp_tmp) {
                    $_lp_loc_arr[$_lp_ini] = $this->wsresponse->filterLoopParams($response, $_lp_org_arr[$_lp_ini], $_lp_inp);
                } else {
                    $_lp_tmp_arr[$_lp_ini] = $this->wsresponse->filterLoopParams($response, $_lp_org_arr[$_lp_ini], $_lp_inp);
                }
                if (isset($this->$func_name->break_continue)) {
                    if ($this->$func_name->break_continue === 1) {
                        $this->$func_name->break_continue = NULL;
                        break;
                    } elseif ($this->$func_name->break_continue === 2) {
                        $this->$func_name->break_continue = NULL;
                        $_lp_ini = $_lp_ini + ($_lp_stp);
                        $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
                        continue;
                    }
                }
                $_lp_ini = $_lp_ini + ($_lp_stp);
                $_lp_cnd = $this->checkCondition($_lp_opr, $_lp_ini, $_lp_end);
            }
            if ($_lp_nam != '') {
                $_lp_key = array_search($_lp_nam, $this->_debug_loop);
            } else {
                $_lp_key = array_search($curr_flow, $this->_debug_loop);
            }
            unset($this->_debug_loop[$_lp_key]);
            $this->_debug_loop = array_values($this->_debug_loop);
            if ($_lp_nam == '') {
                $input_params[$curr_flow] = $_lp_tmp_arr;
            } elseif (!is_array($_lp_org_arr[0])) {
                $input_params[$_lp_nam] = $_lp_tmp_arr;
            }
            if (is_array($_lp_tmp_dic)) {
                $input_params = array_merge($input_params, $_lp_tmp_dic);
            }
            $exec_debuger = $this->_debug_curr[$exec_debuger['end']];
        } elseif ($exec_debuger['type'] == "endloop") {
            $this->wsresponse->pushDebugParams($curr_flow, array(), $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
            return $input_params;
        } elseif (method_exists($this->$func_name, $curr_flow)) {
            $output_arr = $this->$func_name->$curr_flow($input_params);
            if (in_array($exec_debuger['type'], array("condition", "break", "continue"))) {
                $_block_result = $output_arr;
                if (in_array($exec_debuger['type'], array("break", "continue"))) {
                    if (isset($this->$func_name->break_continue)) {
                        if ($this->$func_name->break_continue === 1 || $this->$func_name->break_continue === 2) {
                            $this->wsresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $exec_debuger['next'], $loop_name, "", $this->_debug_loop);
                            return $input_params;
                        }
                    }
                }
            } else {
                if (in_array($exec_debuger['type'], array("query", "notifyemail", "pushnotify", "sms"))) {
                    $_block_result = $this->$func_name->block_result;
                } else {
                    $_block_result = $output_arr;
                }
                $input_params = $output_arr;
            }
        } else {
            show_error('API debugger having some problem to detect next flow. Please try again.', 400);
        }
        if ($exec_debuger['type'] == "finish") {
            $this->wsresponse->pushDebugParams($curr_flow, $_block_result, $input_params);
            return $input_params;
        } elseif ($exec_debuger['type'] == "condition") {
            if ($output_arr['success']) {
                $next_flow = $exec_debuger['next'][1];
            } else {
                $next_flow = $exec_debuger['next'][0];
            }
        } else {
            $next_flow = $exec_debuger['next'];
        }
        $this->wsresponse->pushDebugParams($curr_flow, $_block_result, $input_params, $next_flow, $loop_name, "", $this->_debug_loop);
        return $this->WSLogRunner($func_name, $input_params, $next_flow, $loop_name);
    }

    public function checkCondition($operator = '', $operand_1 = '', $operand_2 = '')
    {
        $operator = (in_array($operator, array("lt", "le", "gt", "ge"))) ? $operator : "lt";
        $flag = $this->general->compareDataValues($operator, $operand_1, $operand_2);
        return $flag;
    }

    public function WSRequestData($method = 'GET_POST', $id_arg = NULL)
    {
        switch ($method) {
            case 'GET':
            case 'GET_id':
                $get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
                $request_arr = $get_arr;
                break;
            case 'POST':
            case 'POST_id':
                $post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
                $request_arr = $post_arr;
                break;
            case 'PUT':
            case 'DELETE':
            case 'PUT_id':
            case 'DELETE_id':
                $put_params = $this->WSRequestBody();
                $request_arr = $put_params;
                break;
            case 'POST_GET':
            default :
                $get_arr = is_array($this->input->get(NULL, TRUE)) ? $this->input->get(NULL, TRUE) : array();
                $post_arr = is_array($this->input->post(NULL, TRUE)) ? $this->input->post(NULL, TRUE) : array();
                $request_arr = array_merge($get_arr, $post_arr);
                break;
        }
        if (in_array($method, array("GET_id", "POST_id", "PUT_id", "DELETE_id"))) {
            if ($id_arg != NULL && !isset($request_arr['id'])) {
                $request_arr['id'] = $id_arg;
            }
        }
        return $request_arr;
    }

    public function WSRequestBody()
    {
        $this->load->library('format');
        $req_format = $this->WSRequestFormat();
        $req_body = $this->input->raw_input_stream;
        if ($req_format && $req_body) {
            $req_body = $this->format->factory($req_body, $req_format)->to_array();
        } else {
            $req_body = $this->input->input_stream();
        }
        return $req_body;
    }

    public function WSRequestFormat()
    {
        $content_type = $this->input->server('CONTENT_TYPE');
        if (empty($content_type) === FALSE) {
            foreach ($this->_req_format as $key => $value) {
                $content_type = (strpos($content_type, ';') !== FALSE ? current(explode(';', $content_type)) : $content_type);
                if ($content_type === $value) {
                    return $key;
                }
            }
        }
        return NULL;
    }
}
