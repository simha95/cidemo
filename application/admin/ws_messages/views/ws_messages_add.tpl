<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('WS_MESSAGES_WS_MESSAGES')%>
                <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
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
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing ">
        <div id="ws_messages" class="frm-elem-block frm-stand-view">
            <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('WS_MESSAGES_WS_MESSAGES')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                <div class="form-row row-fluid" id="cc_sh_mwm_apiname">
                                    <label class="form-label span3">
                                        <%$this->lang->line('WS_MESSAGES_API_NAME')%> <em>*</em>
                                    </label> 
                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>  ">
                                        <%if $mode eq "Update"%>
                                            <input type="hidden" class="ignore-valid" name="mwm_apiname" id="mwm_apiname" value="<%$data['mwm_apiname']%>" />
                                            <strong><%if $data['mwm_apiname'] neq "" %><%$data['mwm_apiname']%><%else%>---<%/if%></strong>
                                        <%else%>
                                            <input type="text" placeholder="" value="<%$data['mwm_apiname']|@htmlentities%>" name="mwm_apiname" id="mwm_apiname" title="<%$this->lang->line('WS_MESSAGES_API_NAME')%>"  class='frm-size-medium'  />
                                        <%/if%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mwm_apinameErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mwm_message_code">
                                    <label class="form-label span3">
                                        <%$this->lang->line('WS_MESSAGES_CODE')%> <em>*</em>
                                    </label> 
                                    <div class="form-right-div  <%if $mode eq 'Update'%>frm-elements-div<%/if%>  ">
                                        <%if $mode eq "Update"%>
                                            <input type="hidden" class="ignore-valid" name="mwm_message_code" id="mwm_message_code" value="<%$data['mwm_message_code']%>" />
                                            <strong><%if $data['mwm_message_code'] neq "" %><%$data['mwm_message_code']%><%else%>---<%/if%></strong>
                                        <%else%>
                                            <input type="text" placeholder="" value="<%$data['mwm_message_code']|@htmlentities%>" name="mwm_message_code" id="mwm_message_code" title="<%$this->lang->line('WS_MESSAGES_CODE')%>"  class='frm-size-medium'  />
                                        <%/if%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mwm_message_codeErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mwml_message">
                                    <label class="form-label span3">
                                        <%$this->lang->line('WS_MESSAGES_MESSAGE')%> <em>*</em>
                                    </label> 
                                    <div class="form-right-div ">
                                        <textarea placeholder="" name="mwml_message" id="mwml_message" title="<%$this->lang->line('WS_MESSAGES_MESSAGE')%>"  class='elastic frm-size-medium'  aria-multi-lingual='parent'><%$lang_data[$prlang]['tMessage']|@htmlentities%></textarea>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mwml_messageErr'></label></div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear" id="lnsh_mwml_message_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0"><%$this->lang->line('WS_MESSAGES_MESSAGE')%> <em>*</em>  [<%$lang_info[$exlang]['vLangTitle']%>]</label> 
                                                <div class="form-right-div">
                                                    <textarea placeholder="" name="langmwml_message[<%$exlang%>]" id="lang_mwml_message_<%$exlang%>" title="<%$this->lang->line('WS_MESSAGES_MESSAGE')%>"  class='elastic frm-size-medium'  ><%$lang_data[$exlang]['tMessage']%></textarea>
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mwml_message", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mwm_type">
                                    <label class="form-label span3">
                                        <%$this->lang->line('WS_MESSAGES_TYPE')%> <em>*</em>
                                    </label> 
                                    <div class="form-right-div   ">
                                        <%assign var="opt_selected" value=$data['mwm_type']%>
                                        <%$this->dropdown->display("mwm_type","mwm_type","  title='<%$this->lang->line('WS_MESSAGES_TYPE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'WS_MESSAGES_TYPE')%>'  ", "|||", "", $opt_selected,"mwm_type")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mwm_typeErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mwm_status">
                                    <label class="form-label span3">
                                        <%$this->lang->line('WS_MESSAGES_STATUS')%> <em>*</em>
                                    </label> 
                                    <div class="form-right-div   ">
                                        <%assign var="opt_selected" value=$data['mwm_status']%>
                                        <%$this->dropdown->display("mwm_status","mwm_status","  title='<%$this->lang->line('WS_MESSAGES_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'WS_MESSAGES_STATUS')%>'  ", "|||", "", $opt_selected,"mwm_status")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mwm_statusErr'></label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="frm-bot-btn <%$rl_theme_arr['frm_stand_action_bar']%> <%$rl_theme_arr['frm_stand_action_btn']%>">
                        <%if $rl_theme_arr['frm_stand_ctrls_view'] eq 'No'%>
                            <%assign var='rm_ctrl_directions' value=true%>
                        <%/if%>
                        <!-- Form Redirection Control Unit -->
                        <%if $controls_allow eq false || $rm_ctrl_directions eq true%>
                            <input value="Stay" id="ctrl_flow_stay" name="ctrl_flow" type="hidden" />
                        <%else%>
                            <div class='action-dir-align'>
                                <%if $prev_link_allow eq true%>
                                    <input value="Prev" id="ctrl_flow_prev" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'Prev' %> checked=true <%/if%> />
                                    <label for="ctrl_flow_prev">&nbsp;</label>
                                    <label for="ctrl_flow_prev" class="inline-elem-margin"><%$this->lang->line('GENERIC_PREV_SHORT')%></label>&nbsp;&nbsp;
                                <%/if%>
                                <%if $next_link_allow eq true || $mode eq 'Add'%>
                                    <input value="Next" id="ctrl_flow_next" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'Next' %> checked=true <%/if%> />
                                    <label for="ctrl_flow_next">&nbsp;</label>
                                    <label for="ctrl_flow_next" class="inline-elem-margin"><%$this->lang->line('GENERIC_NEXT_SHORT')%></label>&nbsp;&nbsp;
                                <%/if%>
                                <input value="List" id="ctrl_flow_list" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq 'List' %> checked=true <%/if%> />
                                <label for="ctrl_flow_list">&nbsp;</label>
                                <label for="ctrl_flow_list" class="inline-elem-margin"><%$this->lang->line('GENERIC_LIST_SHORT')%></label>&nbsp;&nbsp;
                                <input value="Stay" id="ctrl_flow_stay" name="ctrl_flow" class="regular-radio" type="radio" <%if $ctrl_flow eq '' || $ctrl_flow eq 'Stay' %> checked=true <%/if%> />
                                <label for="ctrl_flow_stay">&nbsp;</label>
                                <label for="ctrl_flow_stay" class="inline-elem-margin"><%$this->lang->line('GENERIC_STAY_SHORT')%></label>
                            </div>
                        <%/if%>
                        <!-- Form Action Control Unit -->
                        <%if $controls_allow eq false%>
                            <div class="clear">&nbsp;</div>
                        <%/if%>
                        <div class="action-btn-align" id="action_btn_container">
                            <%if $mode eq 'Update'%>
                                <%if $update_allow eq true%>
                                    <input value="<%$this->lang->line('GENERIC_UPDATE')%>" name="ctrlupdate" type="submit" id="frmbtn_update" class="btn btn-info"/>&nbsp;&nbsp;
                                <%/if%>
                                <%if $delete_allow eq true%>
                                    <input value="<%$this->lang->line('GENERIC_DELETE')%>" name="ctrldelete" type="button" id="frmbtn_delete" class="btn btn-danger" onclick="return deleteAdminRecordData('<%$enc_id%>', '<%$mod_enc_url.index%>','<%$mod_enc_url.inline_edit_action%>', '<%$extra_qstr%>', '<%$extra_hstr%>');" />&nbsp;&nbsp;
                                <%/if%>
                            <%else%>
                                <input value="<%$this->lang->line('GENERIC_SAVE')%>" name="ctrladd" type="submit" id="frmbtn_add" class="btn btn-info" />&nbsp;&nbsp;
                            <%/if%>
                            <%if $discard_allow eq true%>
                                <input value="<%$this->lang->line('GENERIC_DISCARD')%>" name="ctrldiscard" type="button" id="frmbtn_discard" class="btn" onclick="return loadAdminModuleListing('<%$mod_enc_url.index%>', '<%$extra_hstr%>')" />
                            <%/if%>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
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
    <%if $auto_arr|@is_array && $auto_arr|@count gt 0%>
        <%foreach name=i from=$auto_arr item=v key=k%>
            if($("#<%$k%>").is("select")){
                $("#<%$k%>").ajaxChosen({
                    dataType: "json",
                    type: "POST",
                    url: el_form_settings.get_chosen_auto_complete_url+"&unique_name=<%$k%>&mode=<%$mod_enc_mode[$mode]%>&id=<%$enc_id%>"
                    },{
                    loadingImg: admin_image_url+"chosen-loading.gif"
                });
            }
        <%/foreach%>
    <%/if%>        
    el_form_settings['jajax_submit_func'] = '';
    el_form_settings['jajax_action_url'] = '<%$admin_url%><%$mod_enc_url["add_action"]%>?<%$extra_qstr%>';
    el_form_settings['buttons_arr'] = [];
    
        el_form_settings["prime_lang_code"] = '<%$prlang%>';
        el_form_settings["other_lang_JSON"] = '<%$exlang_arr|@json_encode%>';
        intializeLanguageAutoEntry(el_form_settings["prime_lang_code"], el_form_settings["other_lang_JSON"]);
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/admin/js_ws_messages.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.ws_messages.callEvents();
<%/javascript%>