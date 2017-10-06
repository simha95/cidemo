<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('WS_MESSAGES_WS_MESSAGES')%>
                <%if $mode eq 'Update' && $recName neq ''%>
                    :: <%$recName%> 
                <%/if%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_MODULE_LISTING','#MODULE_HEADING#','WS_MESSAGES_WS_MESSAGES')%>">
                        <span class="icon16 minia-icon-arrow-left"></span>
                    </a>
                </div>
            <%/if%>
            <%if $next_link_allow eq true%>
                <div class="frm-next-rec">
                    <a hijacked="yes" title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'>
                        <%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span>
                    </a>
                </div>
            <%/if%>
            <%if $switchto_allow eq true%>
                <div class="frm-switch-drop">
                    <%if $switch_combo|is_array && $switch_combo|@count gt 0%>
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' aria-switchto-self='<%$switch_cit.param%>' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")' ",'','',$enc_id)%>
                    <%/if%>
                </div>
            <%/if%>
            <%if $prev_link_allow eq true%>  
                <div class="frm-prev-rec">
                    <a hijacked="yes" title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'>
                        <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%>
                    </a>            
                </div>
            <%/if%>
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <input type="hidden" id="projmod" name="projmod" value="ws_messages" />
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout">
    </div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing ">
        <div id="ws_messages" class="frm-view-block frm-stand-view">
            <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
            <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
            <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
            <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
            <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
            <div class="main-content-block" id="main_content_block">
                <div style="width:98%;" class="frm-block-layout pad-calc-container">
                    <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                        <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('WS_MESSAGES_WS_MESSAGES')%></h4></div>
                        <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                            <div class="form-row row-fluid" id="cc_sh_mwm_apiname">
                                <label class="form-label span3">
                                    <%$this->lang->line('WS_MESSAGES_API_NAME')%>
                                </label> 
                                <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                    <strong><%$data['mwm_apiname']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mwm_message_code">
                                <label class="form-label span3">
                                    <%$this->lang->line('WS_MESSAGES_CODE')%>
                                </label> 
                                <div class="form-right-div frm-elements-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%> ">
                                    <strong><%$data['mwm_message_code']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mwml_message">
                                <label class="form-label span3">
                                    <%$this->lang->line('WS_MESSAGES_MESSAGE')%>
                                </label> 
                                <div class="form-right-div frm-elements-divs">
                                    <strong><%$data['mwml_message']|@addslashes%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mwm_type">
                                <label class="form-label span3">
                                    <%$this->lang->line('WS_MESSAGES_TYPE')%>
                                </label> 
                                <div class="form-right-div frm-elements-div  ">
                                    <strong><%$data['mwm_type']%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mwm_status">
                                <label class="form-label span3">
                                    <%$this->lang->line('WS_MESSAGES_STATUS')%>
                                </label> 
                                <div class="form-right-div frm-elements-div  ">
                                    <strong><%$this->general->displayKeyValueData($data['mwm_status'], $opt_arr['mwm_status'])%></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>        
<%javascript%>        
            
    var el_form_settings = {}, elements_uni_arr = {}, child_rules_arr = {}, google_map_json = {}, pre_cond_code_arr = [];
    el_form_settings['module_name'] = '<%$module_name%>'; 
    el_form_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_form_settings['extra_qstr'] = '<%$extra_qstr%>';
    el_form_settings['upload_form_file_url'] = admin_url+"<%$mod_enc_url['upload_form_file']%>?<%$extra_qstr%>";
    el_form_settings['get_chosen_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_chosen_auto_complete']%>?<%$extra_qstr%>";
    el_form_settings['token_auto_complete_url'] = admin_url+"<%$mod_enc_url['get_token_auto_complete']%>?<%$extra_qstr%>";
    el_form_settings['tab_wise_block_url'] = admin_url+"<%$mod_enc_url['get_tab_wise_block']%>?<%$extra_qstr%>";
    el_form_settings['parent_source_options_url'] = "<%$mod_enc_url['parent_source_options']%>?<%$extra_qstr%>";
    el_form_settings['jself_switchto_url'] =  admin_url+'<%$switch_cit["url"]%>';
    
    google_map_json = $.parseJSON('<%$google_map_arr|@json_encode%>');
    child_rules_arr = {};
    callSwitchToSelf();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%>