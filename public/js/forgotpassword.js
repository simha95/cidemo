Project.modules.forgotpassword = (function () {
    var objReturn = {};
    
    function init() {
        Common.initValidator();
        setForgotPasswordValidate();
    }
    function setForgotPasswordValidate() {
        $('#forgotpassword-form-normal').validate({
            rules: {
                'User[vUserName]': {
                    required: true
                }
            },
            messages: {
                'User[vUserName]': {
                    required: 'Please enter a Username/Email'
                }
            }
        });
    }
    
    objReturn.init = init;
    return objReturn;
})();