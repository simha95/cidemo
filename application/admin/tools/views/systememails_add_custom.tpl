<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('GENERIC_SYSTEM_EMAILS')%>
                <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_SYSTEM_EMAILS_LISTING','#MODULE_HEADING#','SYSTEMEMAILS_SYSTEM_EMAILS')%>">
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
                        <%$this->dropdown->display('vSwitchPage',"vSwitchPage","style='width:100%;' class='chosen-select' onchange='return loadAdminModuleAddSwitchPage(\"<%$mod_enc_url.add%>\",this.value, \"<%$extra_hstr%>\")'",'','',$enc_id)%>
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
    <input type="hidden" id="projmod" name="projmod" value="system_emails">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="system_emails" class="frm-elem-block frm-stand-view">
            <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<%$enc_id%>">
                <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>">
                <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>">
                <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>">
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>">
                <%if $mode eq 'Update'%>
                    <input type="hidden" name="mse_status" id="mse_status" value="<%$data['mse_status']%>"  class='ignore-valid' />
                <%else%>
                    <input type="hidden" name="mse_status" id="mse_status" value="Active"  class='ignore-valid' />
                <%/if%>
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_SYSTEM_EMAILS')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                <div class="form-row row-fluid" id="cc_sh_mse_email_title">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_TITLE')%> <em>*</em></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_email_title']|@htmlentities%>" name="mse_email_title" id="mse_email_title" title="<%$this->lang->line('GENERIC_EMAIL_TITLE')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_email_titleErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_email_code">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_CODE')%> <em>*</em></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_email_code']|@htmlentities%>" name="mse_email_code" id="mse_email_code" title="<%$this->lang->line('GENERIC_EMAIL_CODE')%>"  class='frm-size-medium'  />
                                        <a class="tipR" style="text-decoration:none;" href="javascript://" title="Email Code should not contain spaces <br>Exapmle: DEMO_EMAIL_CODE">
                                            <span class="icomoon-icon-help"></span>
                                        </a>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_email_codeErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_email_subject">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_EMAIL_SUBJECT')%> <em>*</em></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_email_subject']|@htmlentities%>" name="mse_email_subject" id="mse_email_subject" title="<%$this->lang->line('GENERIC_EMAIL_SUBJECT')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_email_subjectErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_from_name">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_NAME')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_from_name']|@htmlentities%>" name="mse_from_name" id="mse_from_name" title="<%$this->lang->line('GENERIC_FROM_NAME')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_from_nameErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_from_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_FROM_EMAIL')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_from_email']|@htmlentities%>" name="mse_from_email" id="mse_from_email" title="<%$this->lang->line('GENERIC_FROM_EMAIL')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_from_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_bcc_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_BCC')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_bcc_email']|@htmlentities%>" name="mse_bcc_email" id="mse_bcc_email" title="<%$this->lang->line('GENERIC_ADD_BCC')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_bcc_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_cc_email">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_ADD_CC')%></label> 
                                    <div class="form-right-div  ">
                                        <input type="text" placeholder="" value="<%$data['mse_cc_email']|@htmlentities%>" name="mse_cc_email" id="mse_cc_email" title="<%$this->lang->line('GENERIC_ADD_CC')%>"  class='frm-size-medium'  />
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_cc_emailErr'></label></div>
                                </div>
                                <div class="form-row row-fluid">
                                    <div class="box form-child-table">
                                        <div class="title">
                                            <h4>
                                                <span class="icon12 icomoon-icon-equalizer-2"></span>
                                                <span><%$this->lang->line('GENERIC_VARIABLES')%></span>
                                                <span id="ajax_loader_childModle" style="display:none;margin-left:32%"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                                                <div class="box-addmore right">
                                                    <a onclick="Project.modules.systememails.getSystemEmailVariableTable()" href="javascript://" class="btn btn-success">
                                                        <span class="icon14 icomoon-icon-plus-2"></span>
                                                        <%$this->lang->line('GENERIC_ADD_NEW')%>
                                                    </a>
                                                </div>
                                            </h4>
                                            <a style="display: none;" class="minimize" href="javascript://"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                        </div>
                                        <div class="content noPad system-email-vars">
                                            <table id="tbl_child_module" class="responsive table table-bordered">
                                                <thead>    
                                                    <tr>
                                                        <th width='3%'>#</th>
                                                        <th width='40%'><%$this->lang->line('GENERIC_VARIABLES')%> <em>*</em> 
                                                            <a class="tipR" style="text-decoration:none;" href="javascript://" title="Example : #COMPANY_NAME#">
                                                                <span class="icomoon-icon-help"></span>
                                                            </a>
                                                        </th>
                                                        <th width='40%'><%$this->lang->line('GENERIC_DESCRIPTION')%></th>
                                                        <th width='17%'><div align="center"><%$this->lang->line('GENERIC_ACTIONS')%></div></th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <table width="100%" border="0" cellpadding='0' cellspacing="0">
                                                <tr>
                                                    <td id='mails_fields_list'>
                                                        <%if $mode eq 'Update'%>
                                                            <%assign var="var_count" value=$db_email_vars|@count%>
                                                        <%else%>
                                                            <%assign var="var_count" value=1%>
                                                        <%/if%>
                                                        <%section name="k" loop=$var_count%>
                                                            <%assign var="i" value=$smarty.section.k.index%>
                                                            <table width='100%' cellspacing='0' cellpadding='0' border="0" class="responsive table table-bordered field-sortable">
                                                                <tr id="tr_child_row_<%$i%>">
                                                                    <td class="row-num-child" width='3%'><%$smarty.section.k.iteration%></td>
                                                                    <td width='40%'>
                                                                        <div class="">
                                                                            <input type="hidden" name="iEmailVariableId[]" value="<%$db_email_vars[$i]['iEmailVariableId']%>">
                                                                            <input type="text" class="frm-size-large valid-variable" title="<%$this->lang->line('GENERIC_VARIABLES')%>" id="system_email_variable_<%$i%>" name="system_email_variable[]" value="<%$db_email_vars[$i]['vVarName']%>">
                                                                        </div>
                                                                        <div>
                                                                            <label id="system_email_variable_<%$i%>Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td width='40%'>
                                                                        <div class="">
                                                                            <input type="text" class="frm-size-large" title="<%$this->lang->line('GENERIC_DESCRIPTION')%>" id="system_email_description_<%$i%>" name="system_email_description[]" value="<%$db_email_vars[$i]['vVarDesc']%>">
                                                                        </div>
                                                                        <div>
                                                                            <label id="system_email_description_<%$i%>Err" class="error"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td align="center" width='17%'>
                                                                        <div class="controls center">
                                                                            <a href="javascript://" title="Sort Fields" class="field-handle">
                                                                                <span class="icon13 icomoon-icon-move"></span>
                                                                            </a>
                                                                            &nbsp;
                                                                            <a class="tipR" href="javascript://" onclick="Project.modules.systememails.deleteSystemEmailVariableRow('<%$i%>')" title="<%$this->lang->line('GENERIC_DELETE')%>">
                                                                                <span class="icon12 icomoon-icon-remove"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        <%/section%>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row row-fluid" id="cc_sh_mse_email_message">
                                    <div class="form-row row-fluid">
                                        <textarea name="mse_email_message" id="mse_email_message" title="<%$this->lang->line('GENERIC_EMAIL_MESSAGE')%>"  style='width:100%;min-height:300px;'  class='frm-size-medium elastic'  ><%$data['mse_email_message']%></textarea>
                                    </div>
                                    <div class="error-msg-form "><label class='error' id='mse_email_messageErr'></label></div>
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
<style>
    body{overflow:visible!important}
</style>
<%javascript%>    
    var elements_uni_arr = {}, child_rules_arr = {}, pre_cond_code_arr = [];
    var inc_no = dis_no = '<%$var_count + 1%>';
<%/javascript%>
<%$this->css->add_css('forms/tinymce.mention.css')%>
<%$this->js->add_js('admin/forms/tinymce/tinymce.min.js', 'admin/admin/js_systememails.js')%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 
