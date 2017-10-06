/** systememails module script */
Project.modules.systememails = {
    init: function() {
        
        valid_more_elements = [];
        
        
    },
    validate: function (){
        
        $("#frmaddupdate").validate({
            onfocusout: false,
            ignore:".ignore-valid, .ignore-show-hide",
            
            errorPlacement : function(error, element) {
                switch(element.attr("name")){
                    
                    default:
                        printErrorMessage(element, valid_more_elements, error);
                        break;
                }
            },
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {                    
                    validator.errorList[0].element.focus();
                }
            },
            submitHandler: function (form) {
                getAdminFormValidate();
                return false;
            }
        });
        
    },
    callEvents: function() {
        this.validate();
        this.initEvents();
        this.CCEvents();
        callGoogleMapEvents();
    },
    callChilds: function(){
        
        callGoogleMapEvents();
    },
    initEvents: function(elem){
        
            $('#mse_email_message').elastic();
    },
    childEvents: function(elem, eleObj){
        
    },
    CCEvents: function(){
        
    }
}
Project.modules.systememails.init();
