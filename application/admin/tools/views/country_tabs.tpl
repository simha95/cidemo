<ul class="nav nav-tabs">
    <li <%if $module_name eq "country"%> class="active" <%/if%>>
        <a title="<%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('COUNTRY_COUNTRY')%>" 
            <%if $module_name eq "country"%> 
                href="javascript://"
            <%else%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/country/add')%>|mode|<%$mod_enc_mode['Update']%>|id|<%$this->general->getAdminEncodeURL($parID)%>" 
            <%/if%>
            >
            <%$this->lang->line('GENERIC_EDIT')%> <%$this->lang->line('COUNTRY_COUNTRY')%>
        </a>
    </li>
    <li <%if $module_name eq "state"%> class="active" <%/if%>>
        <a title="<%$this->lang->line('COUNTRY_STATE')%> <%$this->lang->line('GENERIC_LIST')%>" 
            <%if $module_name eq "state"%> 
                href="javascript://"
            <%elseif $module_name eq "country"%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/state/index')%>|parMod|<%$this->general->getAdminEncodeURL('country')%>|parID|<%$this->general->getAdminEncodeURL($data['iCountryId'])%>"
            <%else%> 
                href="<%$admin_url%>#<%$this->general->getAdminEncodeURL('tools/state/index')%>|parMod|<%$this->general->getAdminEncodeURL('country')%>|parID|<%$this->general->getAdminEncodeURL($parID)%>" 
            <%/if%>
            >
            <%$this->lang->line('COUNTRY_STATE')%>
        </a>
    </li>
</ul>            