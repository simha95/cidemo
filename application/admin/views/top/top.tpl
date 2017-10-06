<%assign var="logo_file_url" value=$this->general->getCompanyLogoURL()%>
<%assign var="menu_assoc_arr" value=$this->systemsettings->getAdminAccessModulesList()%>
<%assign var="total_arr" value=$this->systemsettings->getMenuArray($menu_assoc_arr['menuCond'], "Top")%>
<%assign var="menu_arr" value=$total_arr['menu']%>
<%assign var="home_arr" value=$total_arr['home']%>
<%assign var="profile_arr" value=$total_arr['profile']%>
<%assign var="password_arr" value=$total_arr['password']%>
<%assign var="logout_arr" value=$total_arr['logout']%>
<div class="top-bg <%$this->config->item('ADMIN_THEME_PATTERN_HEAD')%>" id="logo_template">
    <div class="container-fluid navbar">
        <div class="top-model-view logo">
            <div class="logo-left">
                <a hijacked="yes" href="<%$home_arr['url']%>" class="brand">
                    <%if $logo_file_url neq ''%>
                        <img alt="<%$this->config->item('COMPANY_NAME')%>" class="admin-logo-top" src="<%$logo_file_url%>" title="<%$this->config->item('COMPANY_NAME')%>">            
                    <%else%>
                        <div class='brand-logo-icon'></div>
                    <%/if%>
                </a>
            </div>
            <div class="date-right">
                <div class="user-block">
                    <span class="loggedname">
                        <span class="icon16 icomoon-icon-user-2"></span>
                        <span id="logged_name" class='display' title="<%$this->session->userdata('vName')%>"><%$this->general->truncateChars($this->session->userdata("vName"), 21)%></span>
                    </span>
                </div>
                <div class="date-block">
                    <%assign var="now_date_time" value=$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"%>
                    <span><%$this->general->dateTimeSystemFormat($now_date_time)%></span>
                </div>
            </div>
        </div>
        <div class="top-navigation-bar">
            <%if $this->config->item('MULTI_LINGUAL_PROJECT') eq 'Yes'%>
                <%assign var='topDefLang' value=$this->config->item('DEFAULT_LANG')%>
                <%assign var='topPrimeLang' value=$this->config->item('PRIME_LANG')%>
                <%assign var='topOtherLang' value=$this->config->item('OTHER_LANG')%>
                <%assign var='top_lang_data' value=$this->config->item('LANG_INFO')%>
                <div class="lang-combo">
                    <span class="lang-box">
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
                    </span>
                </div>
            <%/if%>
            <div class="top-menu <%$this->config->item('ADMIN_THEME_PATTERN_LEFT')%>">
                <ul id="navTopMenu">
                    <%assign var="parent_arr" value=$menu_arr[0][1]%>
                    <%if $menu_arr|@is_array && $menu_arr|@count gt 0%>
                        <%section name="i" loop=$parent_arr%>
                            <%assign var="child_arr" value=$menu_arr[$parent_arr[i]['id']]%>
                            <li id="parent_menu_<%$parent_arr[i]['id']%>" class="top parent-menu-li">
                                <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                    <%assign var="hyper_link" value='javascript:;'%>
                                <%else%>
                                    <%assign var="hyper_link" value=$parent_arr[i]['url']%>
                                <%/if%>
                                <a hijacked="yes" class="top_link" href="<%$hyper_link%>" target="<%$parent_arr[i]['target']%>" title="<%$parent_arr[i]['label_lang']%>"> 
                                    <span class="down">
                                        <i class="icon15 <%$parent_arr[i]['icon']%>"></i>
                                        <%$parent_arr[i]['label_lang']%>
                                    </span>
                                    <i class="icon16 icomoon-icon-arrow-down-2"></i>
                                </a>
                                <%if $child_arr|@is_array && $child_arr|@count gt 0%>
                                    <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-1" >
                                        <%section name="j" loop=$child_arr[1]%>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$child_arr[1][j]['code']%>" href="<%$child_arr[1][j]['url']%>" target="<%$child_arr[1][j]['target']%>" title="<%$child_arr[1][j]['label_lang']%>">
                                                    <span class="down-child icon13 <%$child_arr[1][j]['icon']%>"></span> 
                                                    <%$child_arr[1][j]['label_lang']%>
                                                </a>
                                            </li>
                                        <%/section%>
                                        <%if $parent_arr[i]['code'] == 'home'%>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                    <span class="down-child icon13 <%$profile_arr['icon']%>"></span>
                                                    <%$profile_arr['label_lang']%>
                                                </a>
                                            </li>
                                            <li class="child-menu-li">
                                                <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup">
                                                    <span class="down-child icon13 <%$password_arr['icon']%>"></span>
                                                    <%$password_arr['label_lang']%>
                                                </a>
                                            </li>
                                        <%/if%>
                                    </ul>
                                    <%if $child_arr[2]|@is_array && $child_arr[2]|@count gt 0%>
                                        <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-2" >
                                            <%section name="k" loop=$child_arr[2]%>
                                                <li class="child-menu-li">
                                                    <a hijacked="yes" aria-nav-code="<%$child_arr[2][j]['code']%>" href="<%$child_arr[2][k]['url']%>" target="<%$child_arr[2][k]['target']%>" title="<%$child_arr[2][k]['label_lang']%>">
                                                        <span class="down-child icon13 <%$child_arr[2][k]['icon']%>"></span> 
                                                        <%$child_arr[2][k]['label_lang']%>
                                                    </a>
                                                </li>
                                            <%/section%>
                                        </ul>
                                        <%if $child_arr[3]|@is_array && $child_arr[3]|@count gt 0%>
                                            <ul class="sub top-menu-<%$parent_arr[i]['code']%> menu-style-list-3" >
                                                <%section name="l" loop=$child_arr[3]%>
                                                    <li class="child-menu-li">
                                                        <a hijacked="yes" aria-nav-code="<%$child_arr[3][j]['code']%>" href="<%$child_arr[3][l]['url']%>" target="<%$child_arr[3][l]['target']%>" title="<%$child_arr[3][l]['label_lang']%>">
                                                            <span class="down-child icon13 <%$child_arr[3][l]['icon']%>"></span> 
                                                            <%$child_arr[3][l]['label_lang']%>
                                                        </a>
                                                    </li>
                                                <%/section%>
                                            </ul>
                                        <%/if%>
                                    <%/if%>
                                <%elseif $parent_arr[i]['code'] == 'home'%>
                                    <ul class="sub top-menu-<%$parent_arr[i]['code']%>" >
                                        <li class="child-menu-li">
                                            <a hijacked="yes" aria-nav-code="<%$profile_arr['code']%>" href="<%$profile_arr['url']%>" title="<%$profile_arr['label_lang']%>">
                                                <span class="down-child icon13 <%$profile_arr['icon']%>"></span>
                                                <%$profile_arr['label_lang']%>
                                            </a>
                                        </li>
                                        <li class="child-menu-li">
                                            <a hijacked="yes" aria-nav-code="<%$password_arr['code']%>" href="<%$password_arr['url']%>" title="<%$password_arr['label_lang']%>" class="fancybox-popup">
                                                <span class="down-child icon13 <%$password_arr['icon']%>"></span>
                                                <%$password_arr['label_lang']%>
                                            </a>
                                        </li>
                                    </ul>
                                <%/if%>
                            </li>
                        <%/section%>
                    <%/if%>    
                    <li class="top">
                        <a hijacked="yes" href="javascript://" title="<%$logout_arr['label_lang']%>" class="top_link admin-link-logout"> 
                            <span class="no-children down-child"><i class="icon15 icomoon-icon-exit"></i><%$logout_arr['label_lang']%> </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>