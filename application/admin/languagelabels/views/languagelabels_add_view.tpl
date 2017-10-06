<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%> <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
            </div>
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->lang->line('LANGUAGELABELS_BACK_TO_LANGUAGE_LABELS_LISTING')%>">
                        <span class="icon16 minia-icon-arrow-left"></span>
                    </a>
                </div>
            <%/if%>
            <%if $next_link_allow eq true%>
                <div class="frm-next-rec">
                    <a title="<%$next_prev_records['next']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['next']['enc_id']%><%$extra_hstr%>" class='btn next-btn'><%$this->lang->line('GENERIC_NEXT_SHORT')%> <span class='icon12 icomoon-icon-arrow-right'></span></a>            
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
                    <a title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'> <span class='icon12 icomoon-icon-arrow-left'></span> <%$this->lang->line('GENERIC_PREV_SHORT')%></a>            
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
        <div id="languagelabels" class="frm-view-block frm-stand-view">
            <div class="main-content-block" id="main_content_block">
                <div style="width:98%;" class="frm-block-layout pad-calc-container">
                    <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                        <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%></h4></div>
                        <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                            <div class="form-row row-fluid" id="cc_sh_mllt_label">
                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABEL')%> <em>*</em> </label> 
                                <div class="form-right-div  frm-elements-div">
                                    <strong><%$data["mllt_label"]|@addslashes%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mllt_page">
                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_SELECT_PAGE')%> <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$this->general->displayKeyValueData($data["mllt_page"], $opt_arr["mllt_page"])%></strong>
                                </div>
                            </div>
                           <div class="form-row row-fluid" id="cc_sh_mllt_module">
                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_MODULE')%></label> 
                                <div class="form-right-div ">
                                    <strong><%$this->general->displayKeyValueData($data["mllt_module"], $opt_arr["mllt_module"])%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mllt_value">
                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_VALUE')%> <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$data["mllt_value"]|@addslashes%></strong>
                                </div>
                            </div>
                            <div class="form-row row-fluid" id="cc_sh_mllt_status">
                                <label class="form-label span3"><%$this->lang->line('LANGUAGELABELS_STATUS')%> <em>*</em> </label> 
                                <div class="form-right-div ">
                                    <strong><%$this->general->displayKeyValueData($data["mllt_status"], $opt_arr["mllt_status"])%></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<%javascript%>        
    var elements_uni_arr = {}, child_rules_arr = {}, pre_cond_code_arr = [];
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 