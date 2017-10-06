var Project = (function () {
    var objReturn = {}, widgets = {}, modules = {}, plugins = {}, timer = 7500;

    function init() {
        initWidgets();
        initModules();
        if ($('.listing-error').html() != '') {
            $('.listing-error').show();
            $(".listing-error").delay(10000).slideUp('slow');
        }
        $("textarea").css('resize', 'none');
        $("checkbox").css('border', 'none');
        $(document).on('click', '#cit-captcha-refresh', function () {
            refreshCaptcha();
        });
        checkmsg();
    }
    function initWidgets() {
        for (var widget in Project.widgets) {
            if (typeof (Project.widgets[widget].init) == 'function') {
                Project.widgets[widget].init();
            }
        }
    }
    function initModules() {
        for (var module in Project.modules) {
            if (typeof _module_vars == "object") {
                Project.modules[module].variables = _module_vars;
            }
            if (typeof (Project.modules[module].init) == 'function') {
                Project.modules[module].init();
            }
        }
        if (Project.modules.processAjax && Project.modules.processAjax.init && typeof Project.modules.processAjax.init == "function") {
            Project.modules.processAjax.init();
        }
    }
    function setMessage(msgText, msgClass, timeOut) {
        var cnt_class, close_class;
        if (msgClass == 0) {
            cnt_class = "alert-danger";
            close_class = 'error';
        } else if (msgClass == 1) {
            cnt_class = "alert-success";
            close_class = 'success';
        } else if (msgClass == 2) {
            cnt_class = "alert-info";
            close_class = 'success';
        } else {
            cnt_class = "";
            close_class = 'success';
        }
        $("#closebtn_errorbox").removeClass("success").removeClass("error").addClass(close_class);
        $('#err_msg_cnt').html(msgText).removeClass("alert-success").removeClass("alert-error").removeClass("alert-info").addClass(cnt_class);
        if ($.isNumeric(timeOut) && timeOut > 0) {
            setTimeout(function () {
                $('#var_msg_cnt').fadeIn('slow');
                setTimeout(function () {
                    closeMessage();
                }, timer);
            }, timeOut);
        } else {
            $('#var_msg_cnt').fadeIn('slow');
            setTimeout(function () {
                closeMessage();
            }, timer);
        }
    }
    function closeMessage() {
        $('#var_msg_cnt').fadeOut('slow');
    }
    function checkmsg() {
        if ($('#err_msg_cnt').length > 0 && $.trim($('#err_msg_cnt').text()) != '') {
            $('#var_msg_cnt').fadeIn('slow');
            setTimeout(function () {
                closeMessage();
            }, timer);
        }
    }
    function showUILoader(target, options) {
        var message, text, defaultCSS = {}, overlayCSS = {};
        options = (options) ? options : {};
        text = ($.trim(options.message)) ? options.message : "";
        if (options.style == "black") {
            if (options.spinner) {
                message = '<i class="fa fa-circle-o-notch bg-loader fa-spin fa-2x fa-fw"></i> ' + text
            } else {
                message = text
            }
            defaultCSS = {
                color: '#fff',
                border: 'none',
                backgroundColor: '#717171',
                padding: "8px 5px 8px 5px",
                'border-radius': '5px',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px'
            };
            overlayCSS = {
                backgroundColor: '#fff',
            }
        } else {
            if (options.spinner) {
                message = '<i class="fa fa-circle-o-notch bg-loader fa-spin fa-2x fa-fw margin-bottom"></i> ' + text;
            } else {
                message = text
            }
            defaultCSS = {
                color: '#000',
                border: '1px solid #aaaaaa',
                backgroundColor: '#fff',
                padding: "8px 5px 8px 5px",
                'border-radius': '5px',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px'
            };
        }
        delete options.style;
        delete options.spinner;

        options.message = message;
        if (options.css) {
            options.css = $.extend({}, defaultCSS, options.css);
        } else {
            options.css = defaultCSS;
        }
        if (options.overlayCSS) {
            options.overlayCSS = $.extend({}, overlayCSS, options.overlayCSS);
        } else {
            options.overlayCSS = overlayCSS;
        }
        if ($(target).length == 0) {
            target = 'body';
        }
        $(target).block(options);
    }
    function hideUILoader(target) {
        if ($(target).length == 0) {
            target = 'body';
        }
        $(target).unblock();
    }
    function showUIMessage(title, message, status, options) {
        options = (options) ? options : {};
        if (status == 0) {
            toastr.error(message, title, options);
        } else if (status == 1) {
            toastr.success(message, title, options);
        } else if (status == 2) {
            toastr.warning(message, title, options);
        } else {
            toastr.info(message, title, options);
        }
    }
    function refreshCaptcha() {
        $('#cit-captcha-icon').addClass("fa-spin");
        $.ajax({
            url: site_url + "captcha.html",
            type: 'POST',
            data: {},
            success: function (response) {
                $('#cit-captcha-image').replaceWith(response);
            },
            complete: function () {
                $('#cit-captcha-icon').removeClass("fa-spin");
            }
        });
    }
    function setObjectKeyData(result, key, data) {
        if (!result) {
            result = {};
        }
        if (!$.isPlainObject(result)) {
            result = {};
        }
        if (!(key in result)) {
            result[key] = {};
        }
        result[key] = data;
        return result;
    }

    objReturn.widgets = widgets;
    objReturn.modules = modules;
    objReturn.plugins = plugins;
    objReturn.timer = timer;
    objReturn.init = init;
    objReturn.initWidgets = initWidgets;
    objReturn.initModules = initModules;
    objReturn.setMessage = setMessage;
    objReturn.closeMessage = closeMessage;
    objReturn.checkmsg = checkmsg;
    objReturn.showUILoader = showUILoader;
    objReturn.hideUILoader = hideUILoader;
    objReturn.showUIMessage = showUIMessage;
    objReturn.refreshCaptcha = refreshCaptcha;
    objReturn.setObjectKeyData = setObjectKeyData;
    return objReturn;
})();
var matched, browser;
jQuery.uaMatch = function (ua) {
    ua = ua.toLowerCase();
    var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
            /(webkit)[ \/]([\w.]+)/.exec(ua) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
            /(msie) ([\w.]+)/.exec(ua) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
            [];
    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};
matched = jQuery.uaMatch(navigator.userAgent);
browser = {};
if (matched.browser) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}
// Chrome is Webkit, but Webkit is also Safari.
if (browser.chrome) {
    browser.webkit = true;
} else if (browser.webkit) {
    browser.safari = true;
}
jQuery.browser = browser;