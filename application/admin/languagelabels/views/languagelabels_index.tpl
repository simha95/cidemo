<textarea id="txt_module_help" style="display:none;">
    <p>This module is used to create the labels for all listing and add/update forms</p>
</textarea>
<div class="headingfix">
        <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('LANGUAGELABELS_LANGUAGE_LABELS')%>
            </div>        
        </h3>
        <div class="header-right-drops"></div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div id="scrollable_content" class="scrollable-content top-list-spacing">
        <div class="grid-data-container pad-calc-container">
            <div class="top-list-tab-layout" id="top_list_grid_layout"></div>
            <table class="grid-table-view " width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="grid_data_col" class="<%$rl_theme_arr['grid_search_toolbar']%>">
                        <div id="pager2"></div>
                        <table id="list2"></table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="selAllRows" value="" id="selAllRows">
<%javascript%>
    $.jgrid.no_legacy_api = true; $.jgrid.useJSON = true;
    var el_grid_settings = {}, js_col_model_json = {}, js_col_name_json = {}; 
        
    el_grid_settings['module_name'] = '<%$module_name%>';
    el_grid_settings['extra_hstr'] = '<%$extra_hstr%>';
    el_grid_settings['extra_qstr'] = '<%$extra_qstr%>';
    el_grid_settings['enc_location'] = '<%$enc_loc_module%>';
    el_grid_settings['par_module '] = '<%$this->general->getAdminEncodeURL($parMod)%>';
    el_grid_settings['par_data'] = '<%$this->general->getAdminEncodeURL($parID)%>';
    el_grid_settings['par_field'] = '<%$parField%>';
    el_grid_settings['par_type'] = 'parent';
    el_grid_settings['add_page_url'] = '<%$mod_enc_url["add"]%>'; 
    el_grid_settings['edit_page_url'] =  admin_url+'<%$mod_enc_url["inline_edit_action"]%>?<%$extra_qstr%>';
    el_grid_settings['listing_url'] = admin_url+'<%$mod_enc_url["listing"]%>?<%$extra_qstr%>';
    el_grid_settings['search_refresh_url'] = admin_url+'<%$mod_enc_url["get_left_search_content"]%>?<%$extra_qstr%>';
    el_grid_settings['search_autocomp_url'] = admin_url+'<%$mod_enc_url["get_search_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['ajax_data_url'] = admin_url+'<%$mod_enc_url["get_chosen_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['auto_complete_url'] = admin_url+'<%$mod_enc_url["get_token_auto_complete"]%>?<%$extra_qstr%>';
    el_grid_settings['export_url'] =  admin_url+'<%$mod_enc_url["export"]%>?<%$extra_qstr%>';
    el_grid_settings['subgrid_listing_url'] =  admin_url+'<%$mod_enc_url["get_subgrid_block"]%>?<%$extra_qstr%>';
    el_grid_settings['jparent_switchto_url'] = admin_url+'<%$parent_switch_cit["url"]%>?<%$extra_qstr%>';
    
    el_grid_settings['admin_rec_arr'] = {};
    el_grid_settings['status_arr'] = $.parseJSON('<%$status_array|@json_encode%>');
    el_grid_settings['status_lang_arr'] = $.parseJSON('<%$status_label|@json_encode%>');
                
    el_grid_settings['hide_add_btn'] = '1';
    el_grid_settings['hide_del_btn'] = '';
    el_grid_settings['hide_status_btn'] = '1';
    el_grid_settings['hide_export_btn'] = '';
    el_grid_settings['hide_columns_btn'] = 'Yes';
    
    el_grid_settings['hide_advance_search'] = 'No';
    el_grid_settings['hide_search_tool'] = 'No';
    el_grid_settings['hide_multi_select'] = 'No';
    el_grid_settings['popup_add_form'] = 'No';
    el_grid_settings['popup_edit_form'] = 'No';
    el_grid_settings['popup_add_size'] = ['', ''];
    el_grid_settings['popup_edit_size'] = ['', ''];
    el_grid_settings['hide_paging_btn'] = 'No';
    el_grid_settings['hide_refresh_btn'] = 'No';
    el_grid_settings['group_search'] = '';
    
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_edit_btn'] = '<%$view_access%>';
    el_grid_settings['permit_expo_btn'] = '<%$expo_access%>';
    
    el_grid_settings['default_sort'] = '<%$list_config['mllt_label']['name']%>';
    el_grid_settings['sort_order'] = 'asc';
    
    el_grid_settings['footer_row'] = 'No';
    el_grid_settings['grouping'] = 'No';
    el_grid_settings['group_attr'] = {};
    
    el_grid_settings['inline_add'] = 'No';
    el_grid_settings['rec_position'] = 'Top';
    el_grid_settings['auto_width'] = 'Yes';
    el_grid_settings['subgrid'] = 'No';
    el_grid_settings['colgrid'] = 'No';
    el_grid_settings['rating_allow'] = 'No';
    el_grid_settings['filters_arr'] = $.parseJSON('<%$default_filters|@json_encode%>');
    el_grid_settings['top_filter'] = [];
    el_grid_settings['buttons_arr'] = [];
    
    js_col_name_json = [{
	"name": "mllt_label",
	"label": "<%$this->lang->line('LANGUAGELABELS_LABEL')%>"
    },
    {
	"name": "mllt_value",
	"label": "<%$this->lang->line('LANGUAGELABELS_VALUE')%>"
    },
    {
	"name": "mllt_module",
	"label": "<%$this->lang->line('LANGUAGELABELS_MODULE')%>"
    },
    {
	"name": "mllt_status",
	"label": "<%$this->lang->line('LANGUAGELABELS_STATUS')%>"
    }];
    
    js_col_model_json = [{
        "name": "<%$list_config['mllt_label']['name']%>",
        "index": "<%$list_config['mllt_label']['name']%>",
        "label": "<%$list_config['mllt_label']['label_lang']%>",
        "labelOrg": "<%$list_config['mllt_label']['label']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mllt_label']['width']%>",
        "search": <%if $list_config['mllt_label']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mllt_label']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mllt_label']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mllt_label']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mllt_label']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "alpha_numeric_without_spaces": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.LANGUAGELABELS_LANGUAGE_LABEL)
                },
                "alpha_numeric_without_spaces": {
                    "value": "\/^[0-9a-zA-Z]+$\/",
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.LANGUAGELABELS_LANGUAGE_LABEL)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "languagelabels",
                "aria-unique-name": "mllt_label",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "languagelabels",
            "aria-unique-name": "mllt_label",
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mllt_label']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
	"name": "<%$list_config['mllt_value']['name']%>",
	"index": "<%$list_config['mllt_value']['name']%>",
	"label": "<%$list_config['mllt_value']['label_lang']%>",
	"labelOrg": "<%$list_config['mllt_value']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['mllt_value']['width']%>",
	"search": <%if $list_config['mllt_value']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['mllt_value']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['mllt_value']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['mllt_value']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['mllt_value']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "textarea",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                        "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.LANGUAGELABELS_VALUE)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "languagelabels",
                "aria-unique-name": "mllt_value",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "languagelabels",
            "aria-unique-name": "mllt_value",
            "rows": "1",
            "placeholder": "",
            "dataInit": initEditGridElasticEvent,
            "class": "inline-edit-row inline-textarea-edit "
	},
	"ctrl_type": "textarea",
	"default_value": "<%$list_config['mllt_value']['default']%>",
	"filterSopt": "bw"
    },
    {
	"name": "<%$list_config['mllt_module']['name']%>",
	"index": "<%$list_config['mllt_module']['name']%>",
	"label": "<%$list_config['mllt_module']['label_lang']%>",
	"labelOrg": "<%$list_config['mllt_module']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['mllt_module']['width']%>",
	"search": <%if $list_config['mllt_module']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['mllt_module']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['mllt_module']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['mllt_module']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['mllt_module']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "select",
	"editrules": {
            "infoArr": []
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "languagelabels",
                "aria-unique-name": "mllt_module",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "dataUrl": <%if $count_arr["mllt_module"]["json"] eq "Yes" %>false<%else%>"<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=mllt_module&mode=<%$mod_enc_mode['Search']%>&rformat=html"<%/if%>,
            "value": <%if $count_arr["mllt_module"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mllt_module"]["data"]%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mllt_module']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mllt_module"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "languagelabels",
            "aria-unique-name": "mllt_module",
            "dataUrl": "<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=mllt_module&mode=<%$mod_enc_mode['Update']%>&rformat=html",
            "dataInit": <%if $count_arr['mllt_module']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mllt_module"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'LANGUAGELABELS_MODULE')%>",
            "class": "inline-edit-row chosen-select"
	},
	"ctrl_type": "dropdown",
	"default_value": "<%$list_config['mllt_module']['default']%>",
	"filterSopt": "in",
	"stype": "select"
    },
    {
	"name": "<%$list_config['mllt_status']['name']%>",
	"index": "<%$list_config['mllt_status']['name']%>",
	"label": "<%$list_config['mllt_status']['label_lang']%>",
	"labelOrg": "<%$list_config['mllt_status']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['mllt_status']['width']%>",
	"search": <%if $list_config['mllt_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['mllt_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['mllt_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['mllt_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['mllt_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "center",
	"edittype": "select",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.LANGUAGELABELS_STATUS)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "languagelabels",
                "aria-unique-name": "mllt_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "dataUrl": <%if $count_arr["mllt_status"]["json"] eq "Yes" %>false<%else%>"<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=mllt_status&mode=<%$mod_enc_mode['Search']%>&rformat=html"<%/if%>,
            "value": <%if $count_arr["mllt_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mllt_status"]["data"]%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mllt_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mllt_status"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "languagelabels",
            "aria-unique-name": "mllt_status",
            "dataUrl": "<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=mllt_status&mode=<%$mod_enc_mode['Update']%>&rformat=html",
            "dataInit": <%if $count_arr['mllt_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mllt_status"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'LANGUAGELABELS_STATUS')%>",
            "class": "inline-edit-row chosen-select"
	},
	"ctrl_type": "dropdown",
	"default_value": "<%$list_config['mllt_status']['default']%>",
	"filterSopt": "in",
	"stype": "select"
    }];
    
    initMainGridListing();
    createTooltipHeading();
    callSwitchToParent();
<%/javascript%>
