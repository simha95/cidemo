Project.modules.login = (function () {
    var objReturn = {}
    
    function init() {
        Common.initValidator();
        setRegisterValidate();
    }
    function setRegisterValidate() {
        $('#frmregister').validate({
            rules: {
                'User[vFirstName]': {
                    required: true
                },
                'User[vLastName]': {
                    required: true
                },
                'User[vUserName]': {
                    required: true,
                    remote: {
                        url: site_url + "user/user/check_user_email",
                        type: "post"
                    }
                },
                'User[vEmail]': {
                    required: true,
                    email: true,
                    remote: {
                        url: site_url + "user/user/check_user_email",
                        type: "post"
                    }
                },
                'User[vPassword]': {
                    required: true
                }
            },
            messages: {
                'User[vFirstName]': {
                    required: 'Please enter a firstname'
                },
                'User[vLastName]': {
                    required: 'Please enter a lastname'
                },
                'User[vUserName]': {
                    required: 'Please enter a username',
                    email: 'Please enter a valid username',
                    remote: 'Username already exists'
                },
                'User[vEmail]': {
                    required: 'Please enter a email',
                    email: 'Please enter valid email address',
                    remote: 'Email address already exists'
                },
                'User[vPassword]': {
                    required: 'Please enter a password'
                }
            }
        });
    }
    
    objReturn.init = init;
    return objReturn;
})();