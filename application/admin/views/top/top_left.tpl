<%assign var="logo_file_url" value=$this->general->getCompanyLogoURL()%>
<%assign var="menu_assoc_arr" value=$this->systemsettings->getAdminAccessModulesList()%>
<%assign var="total_arr" value=$this->systemsettings->getMenuArray($menu_assoc_arr['menuCond'])%>
<%assign var="menu_arr" value=$total_arr['menu']%>
<%assign var="home_arr" value=$total_arr['home']%>
<%assign var="profile_arr" value=$total_arr['profile']%>
<%assign var="password_arr" value=$total_arr['password']%>
<%assign var="logout_arr" value=$total_arr['logout']%>
<%assign var="parent_arr" value=$menu_arr[0]%>

<div class="top-bg left-model-view <%$this->config->item('ADMIN_THEME_PATTERN_HEAD')%>" id="logo_template">
    <div class="logo container-fluid navbar">
        <a hijacked="yes" href="<%$home_arr['url']%>" class="brand">
            <%if $logo_file_url neq ''%>
                <img alt="<%$this->config->item('COMPANY_NAME')%>" class="admin-logo-top" src="<%$logo_file_url%>" title="<%$this->config->item('COMPANY_NAME')%>">
            <%else%>
                <div class='brand-logo-icon'></div>
            <%/if%>
        </a>
    </div>
    <div class="toprightarea">
        <div class="date-right">
            <%assign var="now_date_time" value=$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"%>
            <span><%$this->general->dateTimeSystemFormat($now_date_time)%></span>
        </div>
        <div class="btn-logout">
            <a hijacked="yes" href="javascript:;" class="gray-bg admin-link-logout"><span class="icon16 icomoon-icon-exit"></span> <%$logout_arr['label_lang']%></a>
        </div>
        <span class="loggedname gray-bg right">
            <span class="icon16 icomoon-icon-user-2"></span>
            <span id="logged_name" title="<%$this->session->userdata('vName')%>"><%$this->general->truncateChars($this->session->userdata("vName"), 21)%></span>
        </span>
         <%if $this->config->item('MULTI_LINGUAL_PROJECT') eq 'Yes'%>
            <%assign var='topDefLang' value=$this->config->item('DEFAULT_LANG')%>
            <%assign var='topPrimeLang' value=$this->config->item('PRIME_LANG')%>
            <%assign var='topOtherLang' value=$this->config->item('OTHER_LANG')%>
            <%assign var='top_lang_data' value=$this->config->item('LANG_INFO')%>
           <span class="lang-box gray-bg right">
                 <div class="lang-label"><%$this->lang->line('GENERIC_LANGUAGE')%> &nbsp;</div>
                 <div class="lang-drop">
                    <select name="topLangCombo" id="topLangCombo" class="chosen-select lang-combo">
                        <option value="<%$topPrimeLang%>" <%if $topDefLang eq $topPrimeLang%> selected= true <%/if%>>
                            <%$top_lang_data[$topPrimeLang]['vLangTitle']%>
                        </option>
                        <%if (is_array($topOtherLang)) && ($topOtherLang|@count gt 0)%>
                            <%section name=i loop=$topOtherLang %>
                            <option value="<%$topOtherLang[i]%>" <%if $topDefLang eq $topOtherLang[i]%> selected=true <%/if%>>
                                <%$top_lang_data[$topOtherLang[i]]['vLangTitle']%> 
                            </option>
                            <%/section%>
                        <%/if%>
                    </select>
                </div>
            </span>
        <%/if%>
    </div>
</div>
<div class="clear"></div>

<div class="collapseBtn leftbar" id="collapse_btn">
    <a class="left-menu-hide tipR" href="javascript://" title="<%$this->lang->line('GENERIC_HIDE_SIDEBAR')%>"><span class="icon14 minia-icon-list-3"></span></a>
</div>

<div id="sidebarbg" class="sidebarbg-main <%$this->config->item('ADMIN_THEME_PATTERN_LEFT')%>"></div>
<div id="sidebar" class="sidebar-main">
    <div class="sidenav">
        <%if $menu_arr|@is_array && $menu_arr|@count gt 0%>
            <div id="sidebar_widget" class="sidebar-widget">
                <h5 class="title"><span class="sidebar-navigation"><%$this->lang->line('GENERIC_NAVIGATION')%></span></h5>
            </div>
            <div class="clear"></div>
            <div id="left_mainnav" class="mainnav">
                <ul>
                    <%section name="i" loop=$parent_arr%>
                        <%assign var="child_arr" value=$menu_arr[$parent_arr[i]['id']]%>
                        <li id="parent_menu_<%$parent_arr[i]['id']%>" class="parent-menu-li">
                            <a class="menu-parent-anchor" href="#" title="<%$parent_arr[i]['label_lang']%>" >
                                <span class="menu-parent-anchor-span icon16 <%$parent_arr[i]['icon']%>"></span>
                                <%$parent_arr[i]['label_lang']%>
                            </a>
                            <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                <ul class="sub">
                                    <%section name="j" loop=$child_arr%>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor" aria-nav-code="<%$child_arr[j]['code']%>" href="<%$child_arr[j]['url']%>" target="<%$child_arr[j]['target']%>" title="<%$child_arr[j]['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$child_arr[j]['icon']%>"></span> 
                                                <%$child_arr[j]['label_lang']%>
                                            </a>
                                        </li>
                                    <%/section%>
                                    <%if $parent_arr[i]['code'] == 'home'%>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$profile_arr['icon']%>"></span>
                                                <%$profile_arr['label_lang']%>
                                            </a>
                                        </li>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" class="menu-child-anchor fancybox-popup" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>">
                                                <span class="menu-child-anchor-span icon14 <%$password_arr['icon']%>"></span>
                                                <%$password_arr['label_lang']%>
                                            </a>
                                        </li>
                                    <%/if%>
                                </ul>
                            <%elseif $parent_arr[i]['code'] == 'home'%>
                                <ul class="sub">
                                    <li class="child-menu-li">
                                        <a hijacked="yes" class="menu-child-anchor" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                            <span class="menu-child-anchor-span icon14 <%$profile_arr['icon']%>"></span>
                                            <%$profile_arr['label_lang']%>
                                        </a>
                                    </li>
                                    <li class="child-menu-li">
                                        <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="menu-child-anchor fancybox-popup">
                                            <span class="menu-child-anchor-span icon14 <%$password_arr['icon']%>"></span>
                                            <%$password_arr['label_lang']%>
                                        </a>
                                    </li>
                                </ul>
                            <%/if%>
                        </li>
                    <%/section%>
                </ul>
            </div>
        </div>
    <%/if%>         
</div>