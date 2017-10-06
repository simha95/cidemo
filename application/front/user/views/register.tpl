<%$this->js->add_js("register.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><%$heading%></div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <form method="post" action="<%if $type eq 'register'%><%base_url('user/register_action')%><%else%><%base_url('user/profile')%><%/if%>" id="frm<%$type%>" class="form-horizontal">
                <div class="col-md-12">
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="User_vFirstName">First Name <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="User_vFirstName" name="User[vFirstName]" maxlength="50" size="60" value="<%$user['firstname']%>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="User_vLastName">Last Name <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="User_vLastName" name="User[vLastName]" maxlength="50" size="60" value="<%$user['lastname']%>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="User_vEmail">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="User_vEmail" name="User[vEmail]" maxlength="50" size="60" value="<%$user['email']%>" <%if $type neq 'register'%>readonly=true<%/if%> />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="User_vUserName">User Name <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="User_vUserName" name="User[vUserName]" maxlength="50" size="60" value="<%$user['username']%>" <%if $type neq 'register'%>readonly=true<%/if%> />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="User_vPassword">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="User_vPassword" autocomplete="off" name="User[vPassword]" maxlength="255" size="60" value="<%$user['password']%>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <%if $type eq 'register'%>
                                <button name="submit" type="submit" class="btn btn-success" id="login">Register</button>&nbsp;
                                <a href="<%$site_url%>" class="btn btn-danger">Cancel</a>
                            <%else%>
                                <input type="hidden" name="userId" id="userId" value="<%$user['id']%>"/>
                                <input name="update" type="submit" class="btn btn-success" value="Update"/>
                                <a href="<%$site_url%>" class="btn btn-danger">Back</a>
                            <%/if%>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>