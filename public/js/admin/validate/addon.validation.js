$(function() {
    $.validator.addMethod("alpha_with_spaces", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/.test(value);
    }, "Please enter valid characters data.(space allowed)");
    $.validator.addMethod("alpha_without_spaces", function(value, element) {
        return this.optional(element) || /^[a-zA-Z]+$/.test(value);
    }, "Please enter valid characters only.");
    $.validator.addMethod("alpha_numeric_with_spaces", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z ]+$/.test(value);
    }, "Please enter alpha numeric characters (space allowed).");
    $.validator.addMethod("alpha_numeric_without_spaces", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z]+$/.test(value);
    }, "Please enter alpha numeric characters only.");
    $.validator.addMethod("alpha_num_without_special_chars", function(value, element) {
        return this.optional(element) || /^[0-9a-zA-Z _-]+$/.test(value);
    }, "Please enter alpha numeric characters without special character.");
    $.validator.addMethod("alpha_without_special_chars", function(value, element) {
        return this.optional(element) || /^[a-zA-Z _-]+$/.test(value);
    }, "Please enter valid characters without special characters.");
    
    $.validator.addMethod("phone_number", function(value, element) {
        return this.optional(element) || /^([(]{1}[0-9]{3}[)]{1}[.| |-]{0,1}|^[0-9]{3}[.|-| ]?)?[0-9]{3}(.|-| )?[0-9]{4}$/.test(value);
    }, "Please enter valid phone numer.");
    $.validator.addMethod("zip_code", function(value, element) {
        return this.optional(element) || /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/.test(value);
    }, "Please enter valid zip code.");
    $.validator.addMethod("ip_address", function(value, element) {
        return this.optional(element) || /^(1?d{1,2}|2([0-4]d|5[0-5]))(.(1?d{1,2}|2([0-4]d|5[0-5]))){3}$/.test(value);
    }, "Please enter valid ip address.");
    $.validator.addMethod("credit_card", function(value, element) {
        return this.optional(element) || /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35d{3})d{11})$/.test(value);
    }, "Please enter valid credit card number.");
    $.validator.addMethod("unsigned_number", function(value, element) {
        return this.optional(element) || /^\+?[0-9]+$/.test(value);
    }, "Please enter valid unsingned numbers.");
});