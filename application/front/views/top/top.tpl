<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<%base_url()%>" title="<%$this->config->item('COMPANY_NAME')%>"><%$this->config->item('COMPANY_NAME')%></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <%if $this->session->userdata('iUserId') eq ''%>
                <li <%if $this->uri->segment(1) == 'login.html'%>class="active"<%/if%>><a title="Login" href="<%$site_url%>login.html">Login</a></li>
                <li <%if $this->uri->segment(1) == 'signup.html'%>class="active"<%/if%>><a title="Registration" href="<%$site_url%>signup.html">Registration</a></li>
                <%else%>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"><%$this->session->userdata('vFirstName')%> <%$this->session->userdata('vLastName')%> &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a title="Profile" role="menuitem" href="<%$site_url%>profile.html"">Profile</a></li>
                        <li class="divider"></li>
                        <li role="presentation"><a title="Logout" role="menuitem" href="<%$site_url%>logout.html">Logout</a></li>
                    </ul>
                </li>                
                <%/if%>
            </ul>            
        </div>
    </div>
</nav>