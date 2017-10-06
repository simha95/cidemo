<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<textarea id="txt_module_help" class="perm-elem-hide"></textarea>
<div id="txt_summary_grid_id" class="perm-elem-hide">
    <!-- Group Summary -->
    <div>
        <div id="txt_mgrp_head_list2">
            {0} - {1}
        </div>
    </div>
</div>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('WS_MESSAGES_WS_MESSAGES')%>
            </div>        
        </h3>
        <div class="header-right-drops">
        </div>
    </div>
</div>
<div id="ajax_content_div" class="ajax-content-div top-frm-spacing box gradient">
    <div id="ajax_qLoverlay"></div>
    <div id="ajax_qLbar"></div>
    <div id="scrollable_content" class="scrollable-content top-list-spacing">
        <div class="grid-data-container pad-calc-container">
            <div class="top-list-tab-layout" id="top_list_grid_layout">
            </div>
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
<input type="hidden" name="selAllRows" value="" id="selAllRows" />
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
    el_grid_settings['popup_add_size'] = ['75%', '75%'];
    el_grid_settings['popup_edit_size'] = ['75%', '75%'];
    el_grid_settings['hide_paging_btn'] = 'No';
    el_grid_settings['hide_refresh_btn'] = 'No';
    el_grid_settings['group_search'] = '';
    
    el_grid_settings['permit_add_btn'] = '<%$add_access%>';
    el_grid_settings['permit_del_btn'] = '<%$del_access%>';
    el_grid_settings['permit_edit_btn'] = '<%$view_access%>';
    el_grid_settings['permit_expo_btn'] = '<%$expo_access%>';
    
    el_grid_settings['default_sort'] = 'mwm_apiname';
    el_grid_settings['sort_order'] = 'asc';
    
    el_grid_settings['footer_row'] = 'No';
    el_grid_settings['grouping'] = 'Yes';
    el_grid_settings['group_attr'] = {
        "field": ["mwm_apiname"],
        "order": ["asc"],
        "text": [$.trim($("#txt_mgrp_head_list2").html())],
        "column": [false],
        "summary": [true]
    };
    
    el_grid_settings['inline_add'] = 'No';
    el_grid_settings['rec_position'] = 'Top';
    el_grid_settings['auto_width'] = 'Yes';
    el_grid_settings['subgrid'] = 'No';
    el_grid_settings['colgrid'] = 'No';
    el_grid_settings['rating_allow'] = 'No';
    el_grid_settings['listview'] = 'list';
    el_grid_settings['filters_arr'] = $.parseJSON('<%$default_filters|@json_encode%>');
    el_grid_settings['top_filter'] = [];
    el_grid_settings['buttons_arr'] = [];
    
    js_col_name_json = [{
        "name": "mwm_apiname",
        "label": "<%$this->lang->line('WS_MESSAGES_API_NAME')%>"
    },
    {
        "name": "mwm_message_code",
        "label": "<%$this->lang->line('WS_MESSAGES_CODE')%>"
    },
    {
        "name": "mwml_message",
        "label": "<%$this->lang->line('WS_MESSAGES_MESSAGE')%>"
    },
    {
        "name": "mwm_type",
        "label": "<%$this->lang->line('WS_MESSAGES_TYPE')%>"
    },
    {
        "name": "mwm_status",
        "label": "<%$this->lang->line('WS_MESSAGES_STATUS')%>"
    }];
    
    js_col_model_json = [{
        "name": "mwm_apiname",
        "index": "mwm_apiname",
        "label": "<%$list_config['mwm_apiname']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mwm_apiname']['width']%>",
        "search": <%if $list_config['mwm_apiname']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mwm_apiname']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mwm_apiname']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mwm_apiname']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mwm_apiname']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "alpha_numeric_without_spaces": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_API_NAME)
                },
                "alpha_numeric_without_spaces": {
                    "value": "\/^[0-9a-zA-Z]+$\/",
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ONLY_ENTER_LETTERS_AND_NUMBERS_WITHOUT_SPACE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_API_NAME)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "ws_messages",
                "aria-unique-name": "mwm_apiname",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mwm_apiname']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "ws_messages",
            "aria-unique-name": "mwm_apiname",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mwm_apiname']['default']%>",
        "setgroup": true,
        "filterSopt": "bw"
    },
    {
        "name": "mwm_message_code",
        "index": "mwm_message_code",
        "label": "<%$list_config['mwm_message_code']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mwm_message_code']['width']%>",
        "search": <%if $list_config['mwm_message_code']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mwm_message_code']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mwm_message_code']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mwm_message_code']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mwm_message_code']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "text",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                        "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_CODE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "ws_messages",
                "aria-unique-name": "mwm_message_code",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mwm_message_code']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "ws_messages",
            "aria-unique-name": "mwm_message_code",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mwm_message_code']['default']%>",
        "filterSopt": "bw",
        "formatter": formatAdminModuleEditLink,
        "unformat": unformatAdminModuleEditLink
    },
    {
        "name": "mwml_message",
        "index": "mwml_message",
        "label": "<%$list_config['mwml_message']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mwml_message']['width']%>",
        "search": <%if $list_config['mwml_message']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mwml_message']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mwml_message']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mwml_message']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mwml_message']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "left",
        "edittype": "textarea",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                        "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_MESSAGE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "ws_messages",
                "aria-unique-name": null,
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mwml_message']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "ws_messages",
            "aria-unique-name": null,
            "placeholder": null,
            "class": "inline-edit-row "
        },
        "ctrl_type": "textbox",
        "default_value": "<%$list_config['mwml_message']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mwm_type",
        "index": "mwm_type",
        "label": "<%$list_config['mwm_type']['label_lang']%>",
        "labelClass": "header-align-left",
        "resizable": true,
        "width": "<%$list_config['mwm_type']['width']%>",
        "search": <%if $list_config['mwm_type']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mwm_type']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mwm_type']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mwm_type']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mwm_type']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                        "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_TYPE)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "ws_messages",
                "aria-unique-name": "mwm_type",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts,
            "searchhidden": <%if $list_config['mwm_type']['search'] eq 'Yes' %>true<%else%>false<%/if%>
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "ws_messages",
            "aria-unique-name": "mwm_type",
            "placeholder": "",
            "class": "inline-edit-row "
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mwm_type']['default']%>",
        "filterSopt": "bw"
    },
    {
        "name": "mwm_status",
        "index": "mwm_status",
        "label": "<%$list_config['mwm_status']['label_lang']%>",
        "labelClass": "header-align-center",
        "resizable": true,
        "width": "<%$list_config['mwm_status']['width']%>",
        "search": <%if $list_config['mwm_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
        "sortable": <%if $list_config['mwm_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
        "hidden": <%if $list_config['mwm_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
        "addable": <%if $list_config['mwm_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "editable": <%if $list_config['mwm_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
        "align": "center",
        "edittype": "select",
        "editrules": {
            "required": true,
            "infoArr": {
                "required": {
                        "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.WS_MESSAGES_STATUS)
                }
            }
        },
        "searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "ws_messages",
                "aria-unique-name": "mwm_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "searchhidden": <%if $list_config['mwm_status']['search'] eq 'Yes' %>true<%else%>false<%/if%>,
            "dataUrl": <%if $count_arr["mwm_status"]["json"] eq "Yes" %>false<%else%>'<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mwm_status&mode=<%$mod_enc_mode["Search"]%>&rformat=html<%$extra_qstr%>'<%/if%>,
            "value": <%if $count_arr["mwm_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["mwm_status"]["data"]|@addslashes%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['mwm_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mwm_status"]["ajax"] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
        },
        "editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "ws_messages",
            "aria-unique-name": "mwm_status",
            "dataUrl": '<%$admin_url%><%$mod_enc_url["get_list_options"]%>?alias_name=mwm_status&mode=<%$mod_enc_mode["Update"]%>&rformat=html<%$extra_qstr%>',
            "dataInit": <%if $count_arr['mwm_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["mwm_status"] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT__C35FIELD_C35' ,'#FIELD#', 'WS_MESSAGES_STATUS')%>",
            "class": "inline-edit-row chosen-select"
        },
        "ctrl_type": "dropdown",
        "default_value": "<%$list_config['mwm_status']['default']%>",
        "filterSopt": "in",
        "stype": "select"
    }];
         
    initMainGridListing();
    createTooltipHeading();
    callSwitchToParent();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 
<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%> 