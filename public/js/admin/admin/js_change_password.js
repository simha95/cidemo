$(document).ready(function(){"yes"==$("#patternLock").val()?$("#vConfirmPassword").addClass("ignore-valid"):$("#vConfirmPassword").removeClass("ignore-valid");"yes"==$("#patternLock").val()&&($("#old_passwd_div").pattern({stop:function(c,a){var b=a.pattern;b.length&&$("#vOldPassword").val(b.join(""))}}),$("#vOldPassword").css("display","none"),$("#passwd_div").pattern({stop:function(c,a){var b=a.pattern;b.length&&$("#vPassword").val(b.join(""))}}),$("#vPassword").css("display","none"))});
function getValidateField(){$("#frmchangepassword").validate({ignore:".ignore-valid",rules:{vOldPassword:{required:!0},vPassword:{required:!0},vConfirmPassword:{required:!0,equalTo:"#vPassword"}},messages:{vOldPassword:{required:js_lang_label.GENERIC_PLEASE_ENTER_OLD_PASSWORD},vPassword:{required:js_lang_label.GENERIC_PLEASE_ENTER_NEW_PASSWORD},vConfirmPassword:{required:js_lang_label.GENERIC_PLEASE_REENTER_NEW_PASSWORD,equalTo:js_lang_label.GENERIC_PASSWORD_DOES_NOT_MATCH}},errorPlacement:function(c,
a){if("vOldPassword"==a.attr("name")){var b=a.attr("id");c.appendTo("#"+b+"Err")}"vPassword"==a.attr("name")&&(b=a.attr("id"),c.appendTo("#"+b+"Err"));"vConfirmPassword"==a.attr("name")&&(b=a.attr("id"),c.appendTo("#"+b+"Err"))},submitHandler:function(){var c={url:jajax_action_url,beforeSubmit:showAdminAjaxRequest,success:function(a,b,c,d){a=$.parseJSON(a);if("0"==a.success)return responseAjaxDataSubmission(a),!1;parent.responseAjaxDataSubmission(a);parent.$.fancybox.close()}};$("#frmchangepassword").ajaxSubmit(c)}})}
function closeWindow(){parent.$.fancybox.close()};