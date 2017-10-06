Project.modules.login = (function () {
    var objReturn = {};
    
    function init() {
        Common.initValidator();
        setLoginValidate();
    }
    function setLoginValidate() {
        $('#login-form-normal').validate({
            rules: {
                'User[vUserName]': {
                    required: true,
                },
                'User[vPassword]': {
                    required: true
                }
            },
            messages: {
                'User[vUserName]': {
                    required: 'Please enter a Username/Email'
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