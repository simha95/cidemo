<div class="copyright" id="bot_copyright">
    <%if $this->session->userdata('iAdminId') neq ''%>
        <div class="nvqc-show-hide-log">
            <%if $this->config->item('__CACHE_PREFERENCES') eq '1'%>
                <a href="javascript://"  title="Clear Cache" class="qc-show-hide-log bottom-log-icons">
                    <span class="icon20 icomoon-icon-recycle"></span>
                </a>
            <%/if%>
            <%if $this->config->item('NAVIGATION_LOG_REQ')|@strtolower eq 'y'%>
                <a href="javascript://"  title="Show Navigation Log" class="nv-show-hide-log bottom-log-icons">
                    <span class="icon20 icomoon-icon-cogs"></span>
                </a>
            <%/if%>
        </div>
        <div class="dbfc-show-hide-log">
            <%if $smarty.env.debug_action eq '1'%>
                <a href="javascript://"  title="Show DB Queries Log" class="db-show-hide-log bottom-log-icons">
                    <span class="icon20 icomoon-icon-fire-2"></span>
                </a>
            <%/if%>
            <a href="javascript://"  title="Show Full Screen" id="show_full_screen_bottom" class="show-full-screen-bottom bottom-log-icons">
                <span class="icon20 iconic-icon-fullscreen"></span>
            </a>
            <a href="javascript://"  style="display:none;" title="Cancel Full Screen" id="cancel_full_screen_bottom" class="cancel-full-screen-bottom bottom-log-icons">
                <span class="icon20 iconic-icon-fullscreen-exit"></span>
            </a>
        </div>  
    <%/if%>
    <%$this->general->getcopyrighttext()%>
</div>


