<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%>
                <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->lang->line('LANGUAGELABELS_BACK_TO_LANGUAGE_LABELS_LISTING')%>">
                        <span class="icon16 minia-icon-arrow-left"></span>
                    </a>
                </div>
            <%/if%>
            <%if $next_link_allow eq true%>
                <div class="frm-next-rec">
                    <a hijacked="yes" title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'><%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span></a>
                </div>
            <%/if%>
            <%if $switchto_allow eq true%>
                <div class="frm-switch-drop">
                    <%if $switch_combo|is_array && $switch_combo|@count gt 0%>
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")' ",'','',$enc_id)%>
                    <%/if%>
                </div>
            <%/if%>
            <%if $prev_link_allow eq true%>  
                <div class="frm-prev-rec">
                    <a hijacked="yes" title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'> <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%></a>            
                </div>
            <%/if%>
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing" >
    <input type="hidden" id="projmod" name="projmod" value="languagelabels">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="languagelabels" class="frm-elem-block frm-stand-view"> 
            <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<%$enc_id%>">
                <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>">
                <input type="hidden" id="ctrl_flow" name="ctrl_flow" value="<%$ctrl_flow%>">
                <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>">
                <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>">
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>">
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class = "frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                <div class="form-row row-fluid" id="cc_sh_mllt_label">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABEL')%> <em>*</em> </label> 
                                    <div class="form-right-div  frm-elements-div ">
                                        <%if $mode eq "Update"%>
                                            <strong><%if $data['mllt_label'] neq "" %><%$data['mllt_label']%><%else%>---<%/if%></strong>
                                        <%else%>
                                            <input type="text" placeholder="" value="<%$data['mllt_label']|@htmlentities%>" name="mllt_label" id="mllt_label" title="<%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABEL')%>"  class='frm-size-medium apply-text-upper_case'  />
                                        <%/if%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mllt_labelErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mllt_page">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_SELECT_PAGE')%> <em>*</em> </label> 
                                    <div class="form-right-div  ">
                                        <%assign var="opt_selected" value=$data['mllt_page']%>
                                        <%$this->dropdown->display("mllt_page","mllt_page","  title='<%$this->lang->line('LANGUAGELABELS_SELECT_PAGE')%>'   class='chosen-select frm-size-medium' aria-chosen-valid='Yes' data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'LANGUAGELABELS_SELECT_PAGE')%>'  ", "", "", $opt_selected,"mllt_page")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mllt_pageErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mllt_module">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_MODULE')%> <em>*</em> </label> 
                                    <div class="form-right-div  ">
                                        <%assign var="opt_selected" value=$data['mllt_module']%>
                                        <%$this->dropdown->display("mllt_module","mllt_module","  title='<%$this->lang->line('LANGUAGELABELS_MODULE')%>'   class='chosen-select frm-size-medium' aria-chosen-valid='Yes' data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'LANGUAGELABELS_MODULE')%>'  ", "|||", "", $opt_selected,"mllt_module")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mllt_moduleErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mllt_value">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_VALUE')%> <em>*</em> </label> 
                                    <div class="form-right-div  ">
                                        <textarea placeholder="" name="mllt_value" id="mllt_value" title="<%$this->lang->line('LANGUAGELABELS_VALUE')%>"  class='elastic frm-size-medium'  aria-multi-lingual='parent'  ><%$lang_data[$prlang]['vTitle']|@htmlentities%></textarea>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mllt_valueErr'></label></div>
                                    <%if $exlang_arr|@is_array && $exlang_arr|@count gt 0%>
                                        <%section name=ml loop=$exlang_arr%>
                                            <%assign var="exlang" value=$exlang_arr[ml]%>
                                            <div class="clear" id="lnsh_mllt_value_<%$exlang%>" style="<%if $exlang neq $dflang%>display:none;<%/if%>">
                                                <label class="form-label span3" style="margin-left:0"><%$this->lang->line('LANGUAGELABELS_VALUE')%> <em>*</em>  [<%$lang_info[$exlang]['vLangTitle']%>]</label> 
                                                <div class="form-right-div">
                                                    <textarea placeholder="" name="langmllt_value[<%$exlang%>]" id="lang_mllt_value_<%$exlang%>" title="<%$this->lang->line('LANGUAGELABELS_VALUE')%>"  class='elastic frm-size-medium'  ><%$lang_data[$exlang]['vTitle']%></textarea>
                                                </div>
                                            </div>
                                        <%/section%>
                                        <div class="lang-flag-css">
                                            <%$this->general->getAdminLangFlagHTML("mllt_value", $exlang_arr, $lang_info)%>
                                        </div>
                                    <%/if%>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mllt_status">
                                    <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_STATUS')%> <em>*</em> </label> 
                                    <div class="form-right-div  ">
                                        <%assign var="opt_selected" value=$data['mllt_status']%>
                                        <%$this->dropdown->display("mllt_status","mllt_status","  title='<%$this->lang->line('LANGUAGELABELS_STATUS')%>'   class='chosen-select frm-size-medium'  aria-chosen-valid='Yes' data-placeholder='<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'LANGUAGELABELS_STATUS')%>'  ", "|||", "", $opt_selected,"mllt_status")%>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mllt_statusErr'></label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                        <%if $rl_theme_arr['frm_gener_ctrls_view'] eq 'No'%>
                            <%assign var='rm_ctrl_directions' value=true%>
                        <%/if%>
                        <%include file="admin_form_direction.tpl"%>
                        <%include file="admin_form_control.tpl"%>
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
    
    el_form_settings['jajax_submit_func'] = '';
    el_form_settings['jajax_action_url'] = '<%$admin_url%><%$mod_enc_url["add_action"]%>?<%$extra_qstr%>';
    el_form_settings['buttons_arr'] = [];
    
    el_form_settings['prime_lang_code'] = '<%$prlang%>';
    el_form_settings['other_lang_JSON'] = '<%$exlang_arr|@json_encode%>';
    
    callSwitchToSelf();
    intializeLanguageAutoEntry(el_form_settings["prime_lang_code"], el_form_settings["other_lang_JSON"]);
<%/javascript%>

<%$this->js->add_js('admin/admin/js_languagelabels.js')%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 

<%javascript%>
    Project.modules.languagelabels.callEvents();
<%/javascript%>