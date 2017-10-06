<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="module-view-container">
    <%include file="admin_add_strip.tpl"%>
    <div class="<%$module_name%>">
        <div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
            <input type="hidden" id="projmod" name="projmod" value="admin" />
            <!-- Page Loader -->
            <div id="ajax_qLoverlay"></div>
            <div id="ajax_qLbar"></div>
            <!-- Module Tabs & Top Detail View -->
            <div class="top-frm-tab-layout" id="top_frm_tab_layout">
            </div>
            <!-- Middle Content -->
            <div id="scrollable_content" class="scrollable-content top-block-spacing ">
                <!-- Module View Block -->
                <div id="admin" class="frm-view-block frm-stand-view">
                    <!-- Form Hidden Fields Unit -->
                    <input type="hidden" id="id" name="id" value="<%$enc_id%>" />
                    <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>" />
                    <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>" />
                    <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>" />
                    <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>" />
                    <input type="hidden" name="ma_last_access" id="ma_last_access" value="<%$data['ma_last_access']%>"  class='ignore-valid '  aria-date-format='<%$this->general->getAdminJSFormats('date_and_time', 'dateFormat')%>'  aria-time-format='<%$this->general->getAdminJSFormats('date_and_time', 'timeFormat')%>'  aria-format-type='datetime' />
                    <!-- Form Display Fields Unit -->
                    <div class="main-content-block" id="main_content_block">
                        <div style="width:98%;" class="frm-block-layout pad-calc-container">
                            <div class="box gradient <%$rl_theme_arr['frm_stand_content_row']%> <%$rl_theme_arr['frm_stand_border_view']%>">
                                <div class="title <%$rl_theme_arr['frm_stand_titles_bar']%>"><h4><%$this->lang->line('ADMIN_ADMIN')%></h4></div>
                                <div class="content <%$rl_theme_arr['frm_stand_label_align']%>">
                                    <div class="form-row row-fluid" id="cc_sh_ma_name">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_NAME')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['ma_name']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_email">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_EMAIL')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['ma_email']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_user_name">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_USER_NAME')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$data['ma_user_name']%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_password">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_PASSWORD')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            *****
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_phonenumber">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_PHONENUMBER')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$this->general->getPhoneMaskedView($this->general->getAdminPHPFormats('phone'),$data['ma_phonenumber'])%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_group_id">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_GROUP')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$this->general->displayKeyValueData($data['ma_group_id'], $opt_arr['ma_group_id'])%></strong>
                                        </div>
                                    </div>
                                    <div class="form-row row-fluid" id="cc_sh_ma_status">
                                        <label class="form-label span3">
                                            <%$this->lang->line('ADMIN_STATUS')%>
                                        </label> 
                                        <div class="form-right-div frm-elements-div  ">
                                            <strong><%$this->general->displayKeyValueData($data['ma_status'], $opt_arr['ma_status'])%></strong>
                                        </div>
                                    </div>
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
    el_form_settings['callbacks'] = [];

    callSwitchToSelf();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
