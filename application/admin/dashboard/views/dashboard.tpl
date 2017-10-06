<%if $this->input->is_ajax_request()%>
    <%$this->js->clean_js()%>
<%/if%>
<div class="headingfix">
    <div class="heading" id="top_heading_fix">
        <%assign var="dash_heading_label" value=$this->general->getDisplayLabel("Dashboard",$data['vPageName'],"label")%>
        <h3>
            <div class="screen-title">
                <%$this->lang->line($dash_heading_label)%> <%if $module_arr['rec_name'] neq ''%> :: <%$module_arr['rec_name']%> <%/if%>
            </div>
        </h3>
        <%if $backlink_allow eq true %>
        <div class="frm-back-to">
            <a href="<%$admin_url%>#<%$module_arr['mod_index_url']%><%$extra_hstr%>"class="backlisting-link" title="<%$this->general->parseLabelMessage('GENERIC_BACK_TO_MODULE_LISTING','#MODULE_HEADING#', $module_arr['module_heading_label'])%>">
                <span class="icon16 minia-icon-arrow-left"></span>
            </a>
        </div>
        <%/if%>
        <%if $top_detail_view["exists"] eq "1"%>
        <div class="frm-detail-view">
            <%if $top_detail_view["flag"] eq "1"%>
            <a href="javascript://" class="tipR active hide-top-detail-view" title="<%$this->lang->line('GENERIC_SHOW_VIEW')%>" id="hide_top_view"  onclick="return hideShowTopView(this);">
                <span><i id="top_show_view_content" class="minia-icon-list"></i></span>
            </a>
            <%else%>
            <a href="javascript://" class="tipR" title="<%$this->lang->line('GENERIC_HIDE_VIEW')%>" id="hide_top_view" onclick="return hideShowTopView(this);">
                <span><i id="top_show_view_content" class="minia-icon-list"></i></span>
            </a>
            <%/if%>
        </div>
        <%/if%>
    </div>
</div>
<div class="<%$page_code%>">
    <div id="ajax_content_div" class="ajax-content-div <%if $is_board_module eq true || $is_tabs_allow eq true%>top-frm-tab-spacing<%else%>top-frm-spacing<%/if%>">
        <div id="ajax_qLoverlay"></div>
        <div id="ajax_qLbar"></div>
        <div id="scrollable_content" class="scrollable-content">
           <%if $is_board_module eq true %>
               <%if $top_detail_view["exists"] eq "1" %>
                   <%$top_detail_view["html"]%>
               <%/if%>
               <div id="ds_list_outertab" class="module-navigation-tabs">
                   <%include file=$module_arr['module_tab_file']%>
               </div>
           <%elseif $is_tabs_allow eq true %>
               <div id="ds_list_outertab" class="module-navigation-tabs">
                   <%include file=$tab_file%>
               </div>
           <%/if%>
           <div class="dash-board pad-calc-container">
               <div class="widget-position-text" id="widget_position_text"><a href="javascript://" class="widget-position-save" id="widget_position_save"><%$this->lang->line('GENERIC_SAVE')%></a> <%$this->lang->line('GENERIC_WIDGET_POISTIONS_HERE')%></div>
               <form name="frmdashboard" id="frmdashboard" method="post" action="" onSubmit="return false"> 
                   <input type="hidden" name="iDashBoardPageId" id="iDashBoardPageId" value="<%$data['iDashBoardPageId']%>"/>
                   <input type="hidden" name="iDashBoardTabId" id="iDashBoardTabId" value="<%$data['iTabId']%>"/>
                   <div class="dash-board-container gridster" id="dash_board_container">
                       <ul class="dash-board-list" id="dash_board_list">
                       <%section name=i loop=$block_data_arr%>
                           <%assign var="board_arr" value=$block_data_arr[i]%>
                           <%assign var="board_id" value=$board_arr['iDashBoardId']%>
                           <%assign var="dash_heading_label" value=$this->general->getDisplayLabel("Dashboard",$board_arr['vBoardName'],"label")%>
                           <%if $board_arr['eChartType'] eq 'Pivot'%>
                               <%assign var="pivot_class" value="pivot-list-view"%>
                           <%elseif $board_arr['eChartType'] eq 'Grid List'%>
                               <%assign var="pivot_class" value="pivot-grid-view"%>
                           <%elseif $board_arr['eChartType'] eq 'Detail View'%>
                               <%assign var="pivot_class" value="pivot-detail-view"%>
                           <%/if%>
                           <li class="dash-board-item" id="board_item_<%$board_id%>" <%$board_arr['attr']%>>
                               <div class="dash-board-block box" id="board_block_<%$board_id%>" >
                                   <input type="hidden" name='iDashBoardId[]' id="iDashBoardId_<%$board_id%>" value="<%$board_id%>"/>
                                   <input type="hidden" name="vBoardCode[]" id="vBoardCode_<%$board_id%>" value="<%$board_arr['vBoardCode']%>"/>
                                   <div class="title dash-board-header">
                                       <h4 class='dash-board-mover'>
                                           <span class="icon16 <%if $board_arr['vBoardIcon'] neq ''%><%$board_arr['vBoardIcon']%><%else%>icomoon-icon-bars<%/if%> board-prefix-icon"></span>
                                           <%$this->lang->line($dash_heading_label)%>
                                           <span class="board-loader-icon" id="board_loader_icon_<%$board_id%>"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></span>
                                           <div class="board-icons">
                                               <div class="icons-display">
                                                   <%if $board_arr['eChartType'] eq 'Pivot'%>
                                                       <div class="wrapper-dropdown" id="wrapper_dropdown_<%$board_id%>">
                                                           <a href="javascript://" class="tip" title="<%$this->lang->line('GENERIC_PIVOT_TABLE')%>" onclick="toggleBoardContent('<%$board_id%>', 'pivot')"><span class="icon15 icomoon-icon-grid-view"></span></a>
                                                           <a class="dropdown-toggle tip toggle-extra-options" aria-id="<%$board_id%>" title="<%$this->lang->line('GENERIC_CHARTS')%>" data-toggle="dropdown" href="javascript://"><span class="icon15 icomoon-icon-bars-2"></span></a>
                                                           <ul class="dropdown" id="options_dropdown_<%$board_id%>">
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_BAR_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'bar')"><span class="drop-menu-icons icon16 icomoon-icon-bars-2"></span> <%$this->lang->line('GENERIC_BAR_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_HORIZONTAL_BAR_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'horizbar')"><span class="drop-menu-icons icon16 icomoon-icon-paragraph-left"></span> <%$this->lang->line('GENERIC_HORIZONTAL_BAR_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_PIE_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'pie')"><span class="drop-menu-icons icon16 iconic-icon-chart"></span> <%$this->lang->line('GENERIC_PIE_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_DONUT_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'donut')"><span class="drop-menu-icons icon16 icomoon-icon-loading-3"></span> <%$this->lang->line('GENERIC_DONUT_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_AREA_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'area')"><span class="drop-menu-icons icon16 icomoon-icon-picture"></span> <%$this->lang->line('GENERIC_AREA_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_LINE_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'line')"><span class="drop-menu-icons icon16 icomoon-icon-graph"></span> <%$this->lang->line('GENERIC_LINE_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_STACKED_BAR_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'stackbar')"><span class="drop-menu-icons icon16 icomoon-icon-bars"></span> <%$this->lang->line('GENERIC_STACKED_BAR_CHART')%></a></li>
                                                               <li><a href="javascript://" title="<%$this->lang->line('GENERIC_STACKED_HORIZONTAL_BAR_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'stackhorizbar')"><span class="drop-menu-icons icon16 icomoon-icon-paragraph-right"></span> <%$this->lang->line('GENERIC_STACKED_HORIZONTAL_BAR_CHART')%></a></li>
                                                               <%*<li class="full-width"><a href="javascript://" title="<%$this->lang->line('GENERIC_AUTO_UPDATING_CHART')%>" onclick="toggleBoardContent('<%$board_id%>', 'autoupdating')"><span class="drop-menu-icons icon16 brocco-icon-stats"></span> <%$this->lang->line('GENERIC_AUTO_UPDATING_CHART')%></a></li>*%>
                                                           </ul>
                                                       </div>
                                                   <%*
                                                   <%else%>
                                                       <a href="javascript://" class="tip" title="<%$this->lang->line('GENERIC_LISTING')%>" onclick="toggleBoardContent('<%$board_id%>', 'listing')"><span class="icon15 icomoon-icon-list-view-2"></span></a>
                                                    *%>
                                                   <%/if%>
                                               </div>
                                               <div class="board-extra-icons">
                                                   <span class="ds-backlink-icon" id="dbacklink_<%$board_id%>" style="display: none;">
                                                       <a href="javascript://" aria-chart-id="<%$board_id%>" class="chart-back-link" title="<%$this->lang->line('GENERIC_GO_BACK')%>"><span class="icon20 typ-icon-back"></span></a>
                                                   </span>
                                                   <span class="ds-pie-combo" id="dpiecombo_<%$board_id%>" style="display: none;"></span>
                                                   <span class="ds-aggr-combo" id="daggrcombo_<%$board_id%>" style="display: none;"></span>
                                                   <span class="ds-filter-icon" id="dfilter_<%$board_id%>" style="display: none;">
                                                       <a href="javascript://" aria-chart-id="<%$board_id%>" class="chart-filter-icon" title="<%$this->lang->line('GENERIC_GRID_FILTER')%>"><span class="icon14 brocco-icon-filter"></span></a>
                                                       <input type="hidden" value="" name="chart_filter_box_<%$board_id%>" id="chart_filter_box_<%$board_id%>" />
                                                   </span>
                                                   <span class="ds-search-icon" id="dsearch_<%$board_id%>" style="display: none;">
                                                       <a href="javascript://" aria-chart-id="<%$board_id%>" class="chart-search-icon" title="<%$this->lang->line('GENERIC_GRID_SEARCH')%>"><span class="icon14 brocco-icon-search"></span></a>
                                                   </span>
                                                   <span class="ds-refresh-icon" id="drefresh_<%$board_id%>" style="display: none;">
                                                       <a href="javascript://" aria-chart-id="<%$board_id%>" class="chart-refresh-icon" title="<%$this->lang->line('GENERIC_GRID_SHOW_ALL')%>"><span class="icon15 minia-icon-refresh"></span></a>
                                                   </span>
                                               </div>
                                           </div>
                                       </h4>
                                       <a href="javascript://" class="minimize" style="display: none;"><%$this->lang->line('GENERIC_MINIMIZE')%></a>
                                   </div>
                                   <div class="content noPad content-block <%$pivot_class%>" id="content_block_<%$board_id%>">
                                       <%if $board_arr['eChartType'] eq 'Pivot' || $board_arr['eChartType'] eq 'Grid List'%>
                                           <div id="dbgrid2_<%$board_id%>">
                                               <div id="dbpager2_<%$board_id%>"></div>
                                               <table id="dblist2_<%$board_id%>"></table>
                                           </div>
                                       <%elseif $board_arr['eChartType'] eq 'Detail View'%>
                                           <%$db_view_data[$board_id]%>
                                       <%/if%>
                                   </div>
                                   <div class="content chart-main content-chart" id="content_chart_<%$board_id%>">
                                       <div id="chart_preview_<%$board_id%>" style="height: 225px;width:100%;">
                                           <div align='center' class="dashbaord-loader"><i class="fa fa-refresh fa-spin-light fa-2x fa-fw"></i></div>
                                       </div>
                                   </div>
                               </div>
                           </li>
                       <%/section%>
                       </ul>
                   </div>
                   <div class="clear">&nbsp;</div>
               </form>
               <div class="clear"></div>
           </div>
        </div>
    </div>
</div>
<style>
    body {overflow:visible!important}
</style>
<%javascript%>
    $.jgrid.no_legacy_api = true, $.jgrid.useJSON = true;
    var DB_data_list_JSON = {}, DB_pivot_data_JSON = {};
    var DB_block_config_JSON = $.parseJSON('<%$block_config_json%>');
    <%if $db_pivot_data_json%>
        DB_pivot_data_JSON = <%$db_pivot_data_json%>;
    <%/if%>
    <%if $db_list_data_json%>
        DB_data_list_JSON = <%$db_list_data_json%>;
    <%/if%>
    initDashBoardSettings();
    for (var i in DB_pivot_data_JSON) {
        if (!DB_pivot_data_JSON[i]['dbID']) {
            continue;
        }
        callDashBoardPivotListing(DB_pivot_data_JSON[i]);
    }
    for (var i in DB_data_list_JSON) {
        if (!DB_data_list_JSON[i]['dbID']) {
            continue;
        }
        callDashBoardGridListing(DB_data_list_JSON[i]);
    }
    initDashBoardFilters();
<%/javascript%>

<%if $this->input->is_ajax_request()%>
    <%$this->css->css_src()%>
<%/if%>
<%if $this->input->is_ajax_request()%>
    <%$this->js->js_src()%>
<%/if%> 