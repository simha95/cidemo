<%$this->js->add_js("forgotpassword.js")%>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Forgot Password</div>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
            <form method="post" action="<%$site_url%>user/user/forgotpassword_action" id="forgotpassword-form-normal" class="form-horizontal">
                <div class="form-group">
                    <label for="User_vUserName" class="col-sm-2 control-label">User Name / Email <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="User_vUserName" name="User[vUserName]" maxlength="50" size="60">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button name="submit" type="submit" class="btn btn-success" id="forgotpassword">Submit</button>
                        <a href="<%$site_url%>login.html" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
