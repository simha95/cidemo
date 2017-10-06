<script type='text/javascript'>
    var google = '', site_url = "<%$this->config->item('site_url')%>", admin_url = "<%$this->config->item('admin_url')%>";
    var style_url = "<%$this->config->item('css_url')%>", admin_style_url = "<%$this->config->item('admin_style_url')%>";
    var admin_js_url = "<%$this->config->item('admin_js_url')%>", admin_image_url = "<%$this->config->item('admin_images_url')%>";
    var el_tpl_settings = {
        container_div: "content_slide",
        main_wrapper_id: "grid_wrapper",
        main_grid_id: "list2",
        main_pager_id: "pager2",
        page_iframe: "<%if $tplmode eq 'frame' %>true<%else%>false<%/if%>",
        close_iframe: "<%if $tplmode eq 'frame' %><%$this->general->closedFancyFrame()%><%else%>false<%/if%>",
        enc_usr_var: "<%$this->general->getMD5EncryptString('JavaScript')%>",
        is_app_cache_active: "<%$this->general->getAppCacheStatus()%>",
        is_enc_active: "<%$this->general->isAdminEncodeActive()%>",
        dashboard_auto_time: "<%$this->config->item('ADMIN_DASHBOARD_AUTO_UPDATE')%>",
        admin_theme: "<%$this->config->item('ADMIN_THEME_DISPLAY')%>",
        menu_poistion: "<%$this->config->item('NAVIGATION_BAR')%>",
        google_maps_key: "<%$this->config->item('GOOGLE_MAPS_API_KEY')%>",
        is_admin_theme_create: "<%$this->config->item('ADMIN_THEME_CREATE')%>",
        is_desk_notify_active: "<%if $this->config->item('ADMIN_DESKTOP_NOTIFICATIONS') eq 'Y'%>1<%else%>0<%/if%>",
        page_animation: "<%if $this->config->item('ANIMATION_REQUIRED') eq 'Y'%>1<%else%>0<%/if%>",
        multi_lingual_trans: "<%if $this->config->item('MULTI_LINGUAL_TRANSLATION') eq 'N'%>0<%else%>1<%/if%>",
        grid_multiple_sorting: "<%if $this->config->item('GRID_MULTIPLE_SORTING') eq 'N'%>0<%else%>1<%/if%>",
        grid_search_prefers: "<%if $this->config->item('GRID_SEARCH_PREFERENCES') eq 'N'%>0<%else%>1<%/if%>",
        grid_search_expires: "<%$this->config->item('GRID_SEARCH_EXPIRE_TIME')%>",
        js_libraries_url: "<%$this->config->item('js_lib_url')%>",
        editor_js_url: "<%$this->config->item('editor_js_url')%>",
        editor_css_url: "<%$this->config->item('editor_css_url')%>",
        grid_column_width: parseInt("<%$this->config->item('ADMIN_GRID_MIN_WIDTH')|@intval%>"),
        grid_rec_limit: parseInt("<%$this->config->item('REC_LIMIT')|@intval%>"),
        grid_top_menu: "<%$this->config->item('LISTING_TOP_MENU')%>",
        grid_bot_menu: "<%$this->config->item('LISTING_BOTTOM_MENU')%>",
        noimage_url: "<%$this->general->getNoImageURL()%>",
        framework_vars: '<%$this->config->item("FRAMEWORK_VARS")|@json_encode%>',
        admin_formats: '<%$this->general->getAdminTPLFormats()%>'
    };
    el_tpl_settings.is_desk_notify_active = (el_tpl_settings.is_desk_notify_active == "1") ? 1 : 0;
    el_tpl_settings.page_animation = (el_tpl_settings.page_animation == "1") ? 1 : 0;
    el_tpl_settings.multi_lingual_trans = (el_tpl_settings.multi_lingual_trans == "1") ? 1 : 0;
    el_tpl_settings.grid_multiple_sorting = (el_tpl_settings.grid_multiple_sorting == "1") ? 1 : 0;
    el_tpl_settings.grid_search_prefers = (el_tpl_settings.grid_search_prefers == "1") ? 1 : 0;
    var el_theme_settings = '<%$this->general->getClientThemeJSON()%>';
    <%if $tplmode eq 'cache'%>
    var cus_enc_url_json = '<%$this->general->getCustomEncryptURL()%>';
    var cus_enc_mode_json = '<%$this->general->getCustomEncryptMode()%>';
    <%else%>
    var js_lang_label = {};
    <%/if%>
</script>