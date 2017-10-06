<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<textarea id="txt_module_help" style="display:none;">
    
</textarea>
<div class="headingfix">
        <div class="heading" id="top_heading_fix">
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: <%$this->lang->line('NOTIFICATIONS_NOTIFICATIONS')%>
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
                
    el_grid_settings['hide_add_btn'] = '';
    el_grid_settings['hide_del_btn'] = '';
    el_grid_settings['hide_status_btn'] = '';
    el_grid_settings['hide_export_btn'] = '1';
    el_grid_settings['hide_columns_btn'] = 'No';
    
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
    
    el_grid_settings['default_sort'] = 'men_t_send_date_time';
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
	"name": "men_subject",
	"label": "<%$this->lang->line('NOTIFICATIONS_SUBJECT')%>"
    },
    {
	"name": "men_receiver",
	"label": "<%$this->lang->line('NOTIFICATIONS_RECEIVER')%>"
    },
    {
	"name": "men_t_send_date_time",
	"label": "<%$this->lang->line('NOTIFICATIONS_SENT_ON')%>"
    },
    {
	"name": "men_content",
	"label": "<%$this->lang->line('NOTIFICATIONS_CONTENT')%>"
    },
    {
	"name": "men_notification_type",
	"label": "<%$this->lang->line('NOTIFICATIONS_NOTIFICATION_TYPE')%>"
    },
    {
	"name": "ma_name",
	"label": "<%$this->lang->line('NOTIFICATIONS_RECEIVER_NAME')%>"
    },
    {
	"name": "men_status",
	"label": "<%$this->lang->line('NOTIFICATIONS_STATUS')%>"
    }];
    
    js_col_model_json = [{
	"name": "<%$list_config['men_subject']['name']%>",
	"index": "<%$list_config['men_subject']['name']%>",
	"label": "<%$list_config['men_subject']['label_lang']%>",
	"labelOrg": "<%$list_config['men_subject']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_subject']['width']%>",
	"search": <%if $list_config['men_subject']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_subject']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_subject']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_subject']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_subject']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "text",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.NOTIFICATIONS_SUBJECT)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_subject",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_subject",
            "placeholder": "",
            "class": "inline-edit-row "
	},
	"ctrl_type": "textbox",
	"default_value": "<%$list_config['men_subject']['default']%>",
	"filterSopt": "bw"
    },
    {
	"name": "<%$list_config['men_receiver']['name']%>",
	"index": "<%$list_config['men_receiver']['name']%>",
	"label": "<%$list_config['men_receiver']['label_lang']%>",
	"labelOrg": "<%$list_config['men_receiver']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_receiver']['width']%>",
	"search": <%if $list_config['men_receiver']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_receiver']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_receiver']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_receiver']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_receiver']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "text",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.NOTIFICATIONS_RECEIVER)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_receiver",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_receiver",
            "placeholder": "",
            "class": "inline-edit-row "
	},
	"ctrl_type": "textbox",
	"default_value": "<%$list_config['men_receiver']['default']%>",
	"filterSopt": "bw"
    },
    {
	"name": "<%$list_config['men_t_send_date_time']['name']%>",
	"index": "<%$list_config['men_t_send_date_time']['name']%>",
	"label": "<%$list_config['men_t_send_date_time']['label_lang']%>",
	"labelOrg": "<%$list_config['men_t_send_date_time']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_t_send_date_time']['width']%>",
	"search": <%if $list_config['men_t_send_date_time']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_t_send_date_time']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_t_send_date_time']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_t_send_date_time']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_t_send_date_time']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "text",
	"editrules": {
            "infoArr": []
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_t_send_date_time",
                "autocomplete": "off",
                "class": "search-inline-date",
                "aria-date-format": "YYYY-MM-DD HH:mm:ss",
                "aria-enable-time": "false"
            },
            "sopt": dateSearchOpts,
            "dataInit": initSearchGridDateTimePicker
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_t_send_date_time",
            "format": "yy-mm-dd|||HH:mm:ss|||showSecond@true",
            "aria-min": "",
            "aria-max": "",
            "placeholder": "",
            "class": "inline-edit-row inline-date-edit date-picker-icon dateTime"
	},
	"ctrl_type": "date_and_time",
	"default_value": "<%$list_config['men_t_send_date_time']['default']%>",
	"filterSopt": "bt"
    },
    {
	"name": "<%$list_config['men_content']['name']%>",
	"index": "<%$list_config['men_content']['name']%>",
	"label": "<%$list_config['men_content']['label_lang']%>",
	"labelOrg": "<%$list_config['men_content']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_content']['width']%>",
	"search": <%if $list_config['men_content']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_content']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_content']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_content']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_content']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "textarea",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.NOTIFICATIONS_CONTENT)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_content",
                "autocomplete": "off"
            },
            "sopt": strSearchOpts
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_content",
            "rows": "1",
            "placeholder": "",
            "dataInit": initEditGridElasticEvent,
            "class": "inline-edit-row inline-textarea-edit "
	},
	"ctrl_type": "textarea",
	"default_value": "<%$list_config['men_content']['default']%>",
	"filterSopt": "bw"
    },
    {
	"name": "<%$list_config['men_notification_type']['name']%>",
	"index": "<%$list_config['men_notification_type']['name']%>",
	"label": "<%$list_config['men_notification_type']['label_lang']%>",
	"labelOrg": "<%$list_config['men_notification_type']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_notification_type']['width']%>",
	"search": <%if $list_config['men_notification_type']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_notification_type']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_notification_type']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_notification_type']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_notification_type']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "center",
	"edittype": "select",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.NOTIFICATIONS_NOTIFICATION_TYPE)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_notification_type",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "dataUrl": <%if $count_arr["men_notification_type"]["json"] eq "Yes" %>false<%else%>"<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=men_notification_type&mode=<%$mod_enc_mode['Search']%>&rformat=html"<%/if%>,
            "value": <%if $count_arr["men_notification_type"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["men_notification_type"]["data"]%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['men_notification_type']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["men_notification_type"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_notification_type",
            "dataUrl": "<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=men_notification_type&mode=<%$mod_enc_mode['Update']%>&rformat=html",
            "dataInit": <%if $count_arr['men_notification_type']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["men_notification_type"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'NOTIFICATIONS_NOTIFICATION_TYPE')%>",
            "class": "inline-edit-row chosen-select"
	},
	"ctrl_type": "dropdown",
	"default_value": "<%$list_config['men_notification_type']['default']%>",
	"filterSopt": "in",
	"stype": "select"
    },
    {
	"name": "<%$list_config['ma_name']['name']%>",
	"index": "<%$list_config['ma_name']['name']%>",
	"label": "<%$list_config['ma_name']['label_lang']%>",
	"labelOrg": "<%$list_config['ma_name']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['ma_name']['width']%>",
	"search": <%if $list_config['ma_name']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['ma_name']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['ma_name']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['ma_name']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['ma_name']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "left",
	"edittype": "select",
	"editrules": {
            "infoArr": []
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_entity_id",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "dataUrl": <%if $count_arr["men_name"]["json"] eq "Yes" %>false<%else%>"<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=ma_name&mode=<%$mod_enc_mode['Search']%>&rformat=html"<%/if%>,
            "value": <%if $count_arr["men_name"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["men_name"]["data"]%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['ma_name']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_name"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_entity_id",
            "dataUrl": "<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=ma_name&mode=<%$mod_enc_mode['Update']%>&rformat=html",
            "dataInit": <%if $count_arr['ma_name']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["ma_name"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'NOTIFICATIONS_RECEIVER_NAME')%>",
            "class": "inline-edit-row chosen-select"
	},
	"ctrl_type": "dropdown",
	"default_value": "<%$list_config['ma_name']['default']%>",
	"filterSopt": "in",
	"stype": "select"
    },
    {
	"name": "<%$list_config['men_status']['name']%>",
	"index": "<%$list_config['men_status']['name']%>",
	"label": "<%$list_config['men_status']['label_lang']%>",
	"labelOrg": "<%$list_config['men_status']['label']%>",
        "labelClass": "header-align-left",
	"resizable": true,
	"width": "<%$list_config['men_status']['width']%>",
	"search": <%if $list_config['men_status']['search'] eq 'No' %>false<%else%>true<%/if%>,
	"sortable": <%if $list_config['men_status']['sortable'] eq 'No' %>false<%else%>true<%/if%>,
	"hidden": <%if $list_config['men_status']['hidden'] eq 'Yes' %>true<%else%>false<%/if%>,
	"addable": <%if $list_config['men_status']['addable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"editable": <%if $list_config['men_status']['editable'] eq 'Yes' %>true<%else%>false<%/if%>,
	"align": "center",
	"edittype": "select",
	"editrules": {
            "required": true,
            "infoArr": {
                "required": {
                    "message": ci_js_validation_message(js_lang_label.GENERIC_PLEASE_ENTER_A_VALUE_FOR_THE_FIELD_FIELD , "#FIELD#", js_lang_label.NOTIFICATIONS_STATUS)
                }
            }
	},
	"searchoptions": {
            "attr": {
                "aria-grid-id": el_tpl_settings.main_grid_id,
                "aria-module-name": "notifications",
                "aria-unique-name": "men_status",
                "autocomplete": "off",
                "data-placeholder": " ",
                "class": "search-chosen-select",
                "multiple": "multiple"
            },
            "sopt": intSearchOpts,
            "dataUrl": <%if $count_arr["men_status"]["json"] eq "Yes" %>false<%else%>"<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=men_status&mode=<%$mod_enc_mode['Search']%>&rformat=html"<%/if%>,
            "value": <%if $count_arr["men_status"]["json"] eq "Yes" %>$.parseJSON('<%$count_arr["men_status"]["data"]%>')<%else%>null<%/if%>,
            "dataInit": <%if $count_arr['men_status']['ajax'] eq 'Yes' %>initSearchGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["men_status"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "multiple": true
	},
	"editoptions": {
            "aria-grid-id": el_tpl_settings.main_grid_id,
            "aria-module-name": "notifications",
            "aria-unique-name": "men_status",
            "dataUrl": "<%$admin_url%><%$mod_enc_url['get_list_options']%>?alias_name=men_status&mode=<%$mod_enc_mode['Update']%>&rformat=html",
            "dataInit": <%if $count_arr['men_status']['ajax'] eq 'Yes' %>initEditGridAjaxChosenEvent<%else%>initGridChosenEvent<%/if%>,
            "ajaxCall": '<%if $count_arr["men_status"]['ajax'] eq "Yes" %>ajax-call<%/if%>',
            "data-placeholder": "<%$this->general->parseLabelMessage('GENERIC_PLEASE_SELECT_FIELD' ,'#FIELD#', 'NOTIFICATIONS_STATUS')%>",
            "class": "inline-edit-row chosen-select"
	},
	"ctrl_type": "dropdown",
	"default_value": "<%$list_config['men_status']['default']%>",
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