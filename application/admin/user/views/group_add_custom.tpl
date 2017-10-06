<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>    
<%$this->js->add_js('admin/admin/js_admin_group.js')%>
<%if $is_admin_group == true%> 
    <%assign var='disabled' value='disabled=true'%>
<%else%>
    <%assign var='disabled' value=''%>
<%/if%>
<%assign var="mod_label_text" value=$this->general->getDisplayLabel("Generic",$mode,"label")%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line($mod_label_text)%> :: <%$this->lang->line('GENERIC_GROUP')%>
                <%if $mode eq 'Update' && $recName neq ''%> :: <%$recName%> <%/if%>
            </div>        
        </h3>
        <div class="header-right-btns">
            <%if $backlink_allow eq true%>
                <div class="frm-back-to">
                    <a hijacked="yes" href="<%$admin_url%>#<%$mod_enc_url['index']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->lang->line('GENERIC_BACK_TO_GROUP_LISTING')%>">
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
                    <a hijacked="yes" title="<%$next_prev_records['prev']['val']%>" href="<%$admin_url%>#<%$mod_enc_url['add']%>|mode|<%$mod_enc_mode['Update']%>|id|<%$next_prev_records['prev']['enc_id']%><%$extra_hstr%>" class='btn prev-btn'><span class='icon12 icomoon-icon-arrow-left'> <%$this->lang->line('GENERIC_PREV_SHORT')%> </span></a>
                </div>
            <%/if%> 
            <div class="clear"></div>
        </div>
        <span style="display:none;position:inherit;" id="ajax_lang_loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing group-form group-add-page" >
    <input type="hidden" id="projmod" name="projmod" value="group">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div class="top-frm-tab-layout" id="top_frm_tab_layout"></div>
    <div id="scrollable_content" class="scrollable-content top-block-spacing">
        <div id="group" class="frm-elem-block frm-stand-view">
            <form name="frmaddupdate" id="frmaddupdate" action="<%$admin_url%><%$mod_enc_url['add_action']%>?<%$extra_qstr%>" method="post"  enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<%$enc_id%>">
                <input type="hidden" id="ctrl_prev_id" name="ctrl_prev_id" value="<%$next_prev_records['prev']['id']%>">
                <input type="hidden" id="ctrl_next_id" name="ctrl_next_id" value="<%$next_prev_records['next']['id']%>">
                <input type="hidden" id="mode" name="mode" value="<%$mod_enc_mode[$mode]%>">
                <input type="hidden" id="extra_hstr" name="extra_hstr" value="<%$extra_hstr%>">
                <div class="main-content-block" id="main_content_block">
                    <div style="width:98%" class="frm-block-layout pad-calc-container">
                        <div class="box gradient <%$rl_theme_arr['frm_gener_content_row']%> <%$rl_theme_arr['frm_gener_border_view']%>">
                            <div class="title <%$rl_theme_arr['frm_gener_titles_bar']%>"><h4><%$this->lang->line('GENERIC_GROUP')%></h4></div>
                            <div class="content <%$rl_theme_arr['frm_gener_label_align']%>">
                                <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_group_name">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_NAME')%>  <em>*</em> </label> 
                                    <div class="form-right-div ">
                                        <input type="text" value="<%$data['mgm_group_name']%>" name="mgm_group_name" id="mgm_group_name" title="<%$this->lang->line('GENERIC_GROUP_NAME')%>"  class='frm-size-medium'  <%$disabled%>/> 
                                    </div>
                                    <div class="error-msg-form" >
                                        <label class='error' id='mgm_group_nameErr'></label>
                                    </div>
                                </div>
                                <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_group_code">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_CODE')%>  <em>*</em> </label> 
                                    <div class="form-right-div ">
                                        <input type="text" value="<%$data['mgm_group_code']%>" name="mgm_group_code" id="mgm_group_code" title="<%$this->lang->line('GENERIC_GROUP_CODE')%>"  class='frm-size-medium'  <%$disabled%>/> 
                                    </div>
                                    <div class="error-msg-form" >
                                        <label class='error' id='mgm_group_codeErr'></label>
                                    </div>
                                </div>
                                <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_grouping_attr">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_LANDING_PAGE')%></label> 
                                    <div class="form-right-div ">
                                        <%$this->dropdown->display("mgm_grouping_attr","mgm_grouping_attr","  title='<%$this->lang->line('GENERIC_GROUP_LANDING_PAGE')%>'   class='frm-size-medium  chosen-select'  data-placeholder='<%$this->lang->line('GENERIC_GROUP_PLEASE_SELECT_LANDING_PAGE')%>' <%$disabled%> ","|||","",$data['mgm_grouping_attr']['menuItem'],"mgm_grouping_attr")%> 
                                    </div>
                                </div>
                                <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_grouping_func" <%if $data['mgm_grouping_attr']['phpFunc']|trim eq ''%>style="display:none;"<%/if%>>
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_GROUP_PHP_FUNCTION')%></label> 
                                    <div class="form-right-div ">
                                        <input type="text" value="<%$data['mgm_grouping_attr']['phpFunc']%>" name="mgm_grouping_func" id="mgm_grouping_func" title="<%$this->lang->line('GENERIC_GROUP_PHP_FUNCTION')%>"  class='frm-size-medium'  readonly="true" <%$disabled%>/> 
                                    </div>
                                </div>
                                <div class="form-row row-fluid label-lt-fix" id="cc_sh_mgm_status">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_STATUS')%>  <em>*</em> </label> 
                                    <div class="form-right-div ">
                                        <%$this->dropdown->display("mgm_status","mgm_status","  title='<%$this->lang->line('GENERIC_STATUS')%>'   class='frm-size-tiny  chosen-select'  data-placeholder='Please Select Status' <%$disabled%> ","","",$data['mgm_status'],"mgm_status")%> 
                                    </div>
                                    <div class="error-msg-form" >
                                        <label class='error' id='mgm_statusErr'></label>
                                    </div>
                                </div>
                                <div class="form-row row-fluid label-lt-fix">
                                    <label class="form-label span3"><%$this->lang->line('GENERIC_SELECT_MODULES')%> <em>*</em> :</label> 
                                    <div class="form-right-div">
                                        <%if $is_admin_group eq true%>
                                            <%assign var="selected" value="disabled=true checked=true" %>
                                        <%else%> 
                                            <%assign var="selected" value="" %>
                                        <%/if%> 
                                        <%foreach from=$action_arr key=ackey item=acval%>
                                        <div class="margin-equilize">
                                            <input type="checkbox" class="regular-checkbox" name="all_<%$ackey%>" id="all_<%$ackey%>" <%$selected%>/>
                                            <label for="all_<%$ackey%>">&nbsp;</label>
                                        </div>
                                        <%/foreach%>
                                    </div>
                                </div>
                                <%assign var="homeData" value=array("HomeSitemap")%>
                                <%if $db_parent_menus|@is_array && $db_parent_menus|@count gt 0%>
                                    <%foreach from=$db_parent_menus key=key item=val%>    
                                        <%assign var="parentMenuId" value=$val['iAdminMenuId']%>
                                        <%assign var="parentCode" value=$val['vUniqueMenuCode']%>
                                        <%assign var="rightsData" value=$db_group_rights[$parentMenuId][0]%>
                                        <%if $is_admin_group eq true%>
                                            <%assign var="selected" value="disabled=true selected = 'selected'" %>
                                        <%else%> 
                                            <%assign var="selected" value="" %>
                                        <%/if%> 
                                        <div class="form-row row-fluid label-lt-fix">
                                            <label class="form-label span3">
                                                <%assign var="menu_label" value=$this->general->getDisplayLabel("Generic",$val['vMenuDisplay'],"label")%>
                                                <input class="left-label-checkbox regular-checkbox" type="checkbox" name="iAdminMenuId[<%$parentMenuId%>]" id="iAdminMenuId_<%$parentMenuId%>" value="<%$parentMenuId%>" <%$selected%> />
                                                <lable for="iAdminMenuId_<%$parentMenuId%>" class="right-label-inline">&nbsp;</lable><lable for="iAdminMenuId_<%$parentMenuId%>" class="right-label-inline"><%$this->lang->line($menu_label)%></lable>
                                            </label> 
                                            <div class="form-right-div">
                                                <%foreach from=$action_arr key=ackey item=acval%>
                                                    <%assign var="checked_attr" value=''%>
                                                    <%if $rightsData|@is_array && $rightsData|@count gt 0%>
                                                        <%if $ackey eq "eView"%>
                                                            <%if $rightsData['eView'] eq "Yes"%>
                                                                <%assign var="checked_attr" value='checked=true'%>
                                                            <%/if%>
                                                        <%elseif $ackey eq "eList"%>
                                                            <%if $rightsData['eList'] eq "Yes"%>
                                                                <%assign var="checked_attr" value='checked=true'%>
                                                            <%/if%>
                                                        <%elseif $ackey eq "eAdd"%>
                                                            <%if $rightsData['eAdd'] eq "Yes"%>
                                                                <%assign var="checked_attr" value='checked=true'%>
                                                            <%/if%>
                                                        <%elseif $ackey eq "eUpdate"%>
                                                            <%if $rightsData['eUpdate'] eq "Yes"%>
                                                                <%assign var="checked_attr" value='checked=true'%>
                                                            <%/if%>
                                                        <%elseif $ackey eq "eDelete"%>
                                                            <%if $rightsData['eDelete'] eq "Yes"%>
                                                                <%assign var="checked_attr" value='checked=true'%>
                                                            <%/if%>
                                                        <%/if%>
                                                    <%/if%>
                                                    <%if $is_admin_group eq true || $parentCode|@in_array:$homeData%>
                                                        <%assign var="checked_attr" value="checked=true"%>
                                                    <%/if%>
                                                    <%if $is_admin_group eq true%>
                                                        <%assign var="disabled_attr" value="disabled=true"%>
                                                    <%else%>   
                                                        <%assign var="disabled_attr" value=''%>
                                                    <%/if%>
                                                    <%assign var="view_type" value=$this->general->getDisplayLabel("Generic",$acval,"label")%>
                                                    <div class="margin-equilize">
                                                        <input type="checkbox" name="<%$ackey%>[<%$parentMenuId%>]" class="regular-checkbox" id="<%$ackey%>_<%$parentMenuId%>" value="Yes" <%$disabled_attr%> <%$checked_attr%> />
                                                        <label class="right-label-inline" for="<%$ackey%>_<%$parentMenuId%>">&nbsp;</label><label class="right-label-inline" for="<%$ackey%>_<%$parentMenuId%>"><%$this->lang->line($view_type)%></label>
                                                    </div>
                                                <%/foreach%>
                                            </div>
                                        </div>
                                        <%assign var="db_child_menus" value=$db_child_assoc_menus[$parentMenuId]%>
                                        <%if $db_child_menus|@is_array && $db_child_menus|@count gt 0%>
                                            <%foreach from=$db_child_menus key=chkey item=chval%> 
                                                <%assign var="childMenuId" value=$chval['iAdminMenuId']%>
                                                <%assign var="childCode" value=$chval['vUniqueMenuCode']%>
                                                <%assign var="rightsData" value=$db_group_rights[$childMenuId][0]%>
                                                <%if $is_admin_group eq true || $childCode|@in_array:$homeData%>
                                                    <%assign var="disabled_attr" value="disabled=true checked=true"%>
                                                <%else%> 
                                                    <%assign var="disabled_attr" value=""%>
                                                <%/if%>
                                                <div class="form-row row-fluid label-lt-fix">
                                                <label class="form-label span3" style="padding-left:2%;">
                                                    <%assign var="sub_menu_label" value=$this->general->getDisplayLabel("Generic",$chval['vMenuDisplay'],"label")%>
                                                    <input class="left-label-checkbox regular-checkbox" type="checkbox" name="iAdminMenuId[<%$childMenuId%>]" id="iAdminMenuId_<%$childMenuId%>" value="<%$childMenuId%>" <%$disabled_attr%> rel="parent_<%$parentMenuId%>" />
                                                    <label class="right-label-inline" for="iAdminMenuId_<%$childMenuId%>">&nbsp;</label><label for="iAdminMenuId_<%$childMenuId%>" class="right-label-inline"><%$this->lang->line($sub_menu_label)%></label>
                                                </label> 
                                                    <div class="form-right-div">
                                                        <%foreach from=$action_arr key=ackey item=acval%>
                                                            <%assign var="checked_attr" value=''%>
                                                            <%if $rightsData|@is_array && $rightsData|@count gt 0%>
                                                                <%if $ackey eq "eView"%>
                                                                    <%if $rightsData['eView'] == "Yes"%>
                                                                        <%assign var="checked_attr" value="checked=true"%>
                                                                    <%/if%>
                                                                <%elseif $ackey eq "eList"%>
                                                                    <%if $rightsData['eList'] == "Yes"%>
                                                                        <%assign var="checked_attr" value="checked=true"%>
                                                                    <%/if%>
                                                                <%elseif $ackey eq "eAdd"%>
                                                                    <%if $rightsData['eAdd'] == "Yes"%>
                                                                        <%assign var="checked_attr" value="checked=true"%>
                                                                    <%/if%>
                                                                <%elseif $ackey eq "eUpdate"%>
                                                                    <%if $rightsData['eUpdate'] == "Yes"%>
                                                                        <%assign var="checked_attr" value="checked=true"%>
                                                                    <%/if%>
                                                                <%elseif $ackey eq "eDelete"%>
                                                                    <%if $rightsData['eDelete'] == "Yes"%>
                                                                        <%assign var="checked_attr" value="checked=true"%>
                                                                    <%/if%>
                                                                <%/if%> 
                                                                <%if $is_admin_group eq true || $childCode|@in_array:$homeData%>
                                                                    <%assign var="checked_attr" value="checked=true"%>
                                                                    <%assign var="disabled_attr" value="disabled=true"%>
                                                                <%else%>   
                                                                    <%assign var="disabled_attr" value=''%>
                                                                <%/if%>
                                                            <%/if%>
                                                            <%assign var="child_view_type" value=$this->general->getDisplayLabel("Generic",$acval,"label")%>
                                                            <div class="margin-equilize">
                                                                <input type="checkbox" name="<%$ackey%>[<%$childMenuId%>]" class="regular-checkbox" id="<%$ackey%>_<%$childMenuId%>" value="Yes" <%$disabled_attr%> <%$checked_attr%> rel="parent_<%$parentMenuId%>" />
                                                                <label class="right-label-inline" for="<%$ackey%>_<%$childMenuId%>">&nbsp;</label><label class="right-label-inline" for="<%$ackey%>_<%$childMenuId%>"><%$this->lang->line($child_view_type)%></label>
                                                            </div>
                                                        <%/foreach%>
                                                    </div>
                                                </div>
                                            <%/foreach%>
                                        <%/if%>
                                    <%/foreach%>
                                <%/if%>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="frm-bot-btn <%$rl_theme_arr['frm_gener_action_bar']%> <%$rl_theme_arr['frm_gener_action_btn']%>">
                        <%if $is_admin_group neq true%>
                            <%if $rl_theme_arr['frm_gener_ctrls_view'] eq 'No'%>
                                <%assign var='rm_ctrl_directions' value=true%>
                            <%/if%>
                            <%include file="admin_form_direction.tpl"%>
                            <%include file="admin_form_control.tpl"%>
                        <%/if%>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
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
