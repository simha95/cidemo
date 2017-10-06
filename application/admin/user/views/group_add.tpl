<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-form-container">
    <%include file="group_add_strip.tpl"%>
    <div class="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="group" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <div id="group" class="frm-elem-block frm-stand-view">
                    <!-- Module Form Block -->
                    <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                        <!-- Form Hidden Fields Unit -->
                        <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                        <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                        <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                        <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                        <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>" />
                        <!-- Form Dispaly Fields Unit -->
                        <div class="main-content-block" id="main_content_block">
                            <div style="width:98%" class="frm-block-layout pad-calc-container">
                                <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                    <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('GROUP_GROUP')%></h4></div>
                                    <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                        <div class="form-row row-fluid" id="cc_sh_mgm_group_name">
                                            <label class="form-label span3">
                                                <%$this->lang->line('GROUP_GROUP_NAME')%>
                                            </label> 
                                            <div class="form-right-div   ">
                                                <input type="text" placeholder="" value="<%$data['mgm_group_name']|@htmlentities%>" name="mgm_group_name" id="mgm_group_name" title="<%$this->lang->line('GROUP_GROUP_NAME')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mgm_group_nameErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mgm_group_code">
                                            <label class="form-label span3">
                                                <%$this->lang->line('GROUP_GROUP_CODE')%>
                                            </label> 
                                            <div class="form-right-div   ">
                                                <input type="text" placeholder="" value="<%$data['mgm_group_code']|@htmlentities%>" name="mgm_group_code" id="mgm_group_code" title="<%$this->lang->line('GROUP_GROUP_CODE')%>"  class='frm-size-medium'  />
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mgm_group_codeErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mgm_grouping_attr">
                                            <label class="form-label span3">
                                                <%$this->lang->line('GROUP_LANDING_PAGE')%>
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['mgm_grouping_attr']%>
                                                <%$this->dropdown->display("mgm_grouping_attr","mgm_grouping_attr","  title='<%$this->lang->line('GROUP_LANDING_PAGE')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'GROUP_LANDING_PAGE')%>'  ", "|||", "", $opt_selected,"mgm_grouping_attr")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mgm_grouping_attrErr'></label></div>
                                        </div>
                                        <div class="form-row row-fluid" id="cc_sh_mgm_status">
                                            <label class="form-label span3">
                                                <%$this->lang->line('GROUP_STATUS')%>
                                            </label> 
                                            <div class="form-right-div   ">
                                                <%assign var="opt_selected" value=$data['mgm_status']%>
                                                <%$this->dropdown->display("mgm_status","mgm_status","  title='<%$this->lang->line('GROUP_STATUS')%>'  aria-chosen-valid='Yes'  class='chosen-select frm-size-medium'  data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'GROUP_STATUS')%>'  ", "|||", "", $opt_selected,"mgm_status")%>
                                            </div>
                                            <div class="error-msg-form "><label class='error' id='mgm_statusErr'></label></div>
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
                                    <input value="<%$ctrl_flow%>" id="ctrl_flow_stay" name="ctrl_flow" type="hidden" />
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
    </div>
</div>
<!-- Module Form Javascript -->
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
    el_form_settings['callbacks'] = [];
    
    google_map_json = $.parseJSON('<%$google_map_arr|@json_encode%>');
    child_rules_arr = {};
            
    <%if $auto_arr|@is_array && $auto_arr|@count gt 0%>
        setTimeout(function(){
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
        }, 500);
    <%/if%>        
    el_form_settings['jajax_submit_func'] = '';
    el_form_settings['jajax_submit_back'] = '';
    el_form_settings['jajax_action_url'] = '<%$admin_url%><%$mod_enc_url["add_action"]%>?<%$extra_qstr%>';
    el_form_settings['buttons_arr'] = [];
    
    callSwitchToSelf();
<%/javascript%>
<%$this->js->add_js('admin/group_add_js.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
<%javascript%>
    Project.modules.group.callEvents();
<%/javascript%>