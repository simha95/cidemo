$(window).resize(function(){980<$(window).width()&&($(".shortcuts.hided").removeClass("hided").attr("style",""),$(".sidenav.hided").removeClass("hided").attr("style",""));$(window).width()});$(window).load(function(){var a=$(window).height();$("#sidebar.scrolled").css("height",a-63+"px")});
$(document).ready(function(){(function(){var a=document,b=a.documentElement,e=a.createElement("style");""===b.style.MozTransform&&(a=function(){e.parentNode&&e.parentNode.removeChild(e)},e.textContent="body{visibility:hidden}",addEventListener("load",a,!1),setTimeout(a,3E3))})();$("a[href='#']").click(function(a){a.preventDefault()});isFancyBoxActive()||$(function(){"No"==el_tpl_settings.is_app_cache_active&&(navigLeftMenuEvents(),"1"==el_tpl_settings.is_admin_theme_create&&createThemeSettings())});
$(function(){for(var a=[],b=0,e=a.length;b<e;b++)if(getLocalStore(el_tpl_settings.enc_usr_var+"_mp")==a[b]){var g=$("#collapse_btn.leftbar");g.children("a").attr("title",js_lang_label.GENERIC_SHOW_SIDEBAR);g.addClass("shadow hide");g.css({top:"20px",left:"200px"});$("#sidebarbg").css("margin-left","-299px");$("#sidebar").css("margin-left","-299px");$("#content").length&&$("#content").css("margin-left","0");$("#content-two").length&&$("#content-two").css("margin-left","0")}});$(document).on("click",
"#collapse_btn",function(){var a=$(this);el_theme_settings.menu_semicollapse||el_general_settings.mobile_platform?semiCollapseLeftMenu(a):fullCollapseLeftMenu(a);adjustLeftMenuScrollBar()});var a=$("div.box");$("div.box").not("div.box.closed");var b=$("div.box.closed");b.find("div.content").hide();b.find(".title>.minimize").removeClass("minimize").addClass("maximize");a.find(".title>a").on("click",function(a){a.preventDefault();a=$(this);a.hasClass("minimize")?(a.removeClass("minimize").addClass("maximize"),
a.parent("div").addClass("min"),cont=a.parent("div").next("div.content"),cont.slideUp(500,"easeOutExpo")):a.hasClass("maximize")&&(a.removeClass("maximize").addClass("minimize"),a.parent("div").removeClass("min"),cont=a.parent("div").next("div.content"),cont.slideDown(500,"easeInExpo"))});a.on("hover",function(){$(this).find(".title>a").show(50)},function(){$(this).find(".title>a").hide()});a.on("hover",function(){$(this).addClass("hover")},function(){$(this).removeClass("hover")});$("input[placeholder], textarea[placeholder]").placeholder();
$().UItoTop({easingType:"easeOutQuart"});$(".tip").qtip({content:!1,position:{my:"bottom center",at:"top center",viewport:$(window)},style:{classes:"ui-tooltip-tipsy"}});$(".tipR").qtip({content:!1,position:{my:"left center",at:"right center",viewport:$(window)},style:{classes:"ui-tooltip-tipsy"}});$(".tipB").qtip({content:!1,position:{my:"top center",at:"bottom center",viewport:$(window)},style:{classes:"ui-tooltip-tipsy"}});$(".tipL").qtip({content:!1,position:{my:"right center",at:"left center",
viewport:$(window)},style:{classes:"ui-tooltip-tipsy"}});setTimeout('$("html").removeClass("loadstate")',500);$(document).on("touchmove",function(){hideTouchMoveMenu()});$(document).on("click",".semi-left-menu-items .menu-child-anchor",function(){$(".semi-item-show").addClass("semi-item-none");$(".semi-item-none").removeClass("semi-item-show");$(".semi-item-none").removeClass("expand")});$(document).on("touchend",".semi-left-menu-items .menu-child-anchor",function(){window.location.href=$(this).attr("href");
hideTouchMoveMenu()});$("#navTopMenu .parent-menu-li").on("hover",function(){var a=$(this).find("ul.sub").length,a=179*a-a+1;if($(window).width()-$(this).offset().left<a){var b=[],e=0,a=$(this).width()-179;var g=a-176;var p=g-176;b.push(a);b.push(g);b.push(p);$(this).find("ul.sub").each(function(){$(this).addClass("change-left-width-"+e).css("margin-left",b[e]);e++})}})});
function hideTouchMoveMenu(){$(".semi-item-show").length&&($(".semi-item-show").addClass("semi-item-none"),$(".semi-item-none").removeClass("semi-item-show"),$(".semi-item-none").removeClass("expand"))}
function navigLeftMenuEvents(){var a=getLocalStore(el_tpl_settings.enc_usr_var+"_mc"),b;if($(".top-menu").length){mainNavUl=$(".top-menu ul");mainNav=$(".top-menu>ul>li");mainNav.find("ul").siblings().addClass("hasUl");mainNavLink=mainNav.find("a").not(".sub a");mainNavLinkAll=mainNav.find("a");mainNavSubLink=mainNav.find(".sub a").not(".sub li .sub a");mainNavCurrent=mainNav.find("a.current");mainNavActive=mainNav.find("a.active");mainNavCurrent.removeClass("current");mainNavActive.removeClass("active");
if(a&&"javascript://"!=a){var c=!1;mainNavLinkAll.each(function(a){if($(this).attr("href")==document.location.href)return $(this).addClass("current"),ulElem=$(this).closest("ul"),ulElem.hasClass("sub")&&(aElem=ulElem.prev("a.hasUl").addClass("active")),setLocalStore(el_tpl_settings.enc_usr_var+"_mp",$(this).attr("href")),c=!0,!1});c||mainNavLinkAll.each(function(d){b=$(this).attr("href");if(isMenuURLMatched(b,a))return $(this).addClass("current"),ulElem=$(this).closest("ul"),ulElem.hasClass("sub")&&
(aElem=ulElem.prev("a.hasUl").addClass("active")),setLocalStore(el_tpl_settings.enc_usr_var+"_mp",$(this).attr("href")),c=!0,!1})}else mainNavUl.find("[rel='home']").parents("ul.sub").show();mainNavLinkAll.click(function(a){a=$(this);mainNavUl.find("li a.hasUl").removeClass("active");$("#navTopMenu").find(".top .sub li a").removeClass("current");a.hasClass("hasUl")?a.addClass("active"):($(this).closest(".parent-menu-li").find("a.hasUl").addClass("active"),$(this).addClass("current"));setLocalStore(el_tpl_settings.enc_usr_var+
"_mc",a.attr("href"))})}else(el_theme_settings.menu_semicollapse||el_general_settings.mobile_platform)&&hideLeftPanelSemiMenu(),mainNavUl=$("#left_mainnav ul"),mainNav=$("#left_mainnav>ul>li"),mainNav.find("ul").siblings().addClass("hasUl").append('<span class="hasDrop icon16 icomoon-icon-arrow-right-2"></span>'),mainNavLink=mainNav.find("a").not(".sub a"),mainNavLinkAll=mainNav.find("a"),mainNavSubLink=mainNav.find(".sub a").not(".sub li .sub a"),mainNavCurrent=mainNav.find("a.current"),mainNavActive=
mainNav.find("a.active"),mainNavCurrent.removeClass("current"),mainNavActive.removeClass("active"),a&&"javascript://"!=a?(c=!1,mainNavLinkAll.each(function(a){if($(this).attr("href")==document.location.href)return $(this).addClass("current"),$(this).parents("ul.sub").prev(".hasUl").find("span.hasDrop").removeClass("icomoon-icon-arrow-right-2"),$(this).parents("ul.sub").prev(".hasUl").find("span.hasDrop").addClass("icomoon-icon-arrow-down-2"),ulElem=$(this).closest("ul"),ulElem.hasClass("sub")&&(aElem=
ulElem.prev("a.hasUl").addClass("drop").addClass("active"),ulElem.addClass("expand")),setLocalStore(el_tpl_settings.enc_usr_var+"_mp",$(this).attr("href")),c=!0,!1}),c||mainNavLinkAll.each(function(d){b=$(this).attr("href");if(isMenuURLMatched(b,a))return $(this).addClass("current"),$(this).parents("ul.sub").prev(".hasUl").find("span.hasDrop").removeClass("icomoon-icon-arrow-right-2"),$(this).parents("ul.sub").prev(".hasUl").find("span.hasDrop").addClass("icomoon-icon-arrow-down-2"),ulElem=$(this).closest("ul"),
ulElem.hasClass("sub")&&(aElem=ulElem.prev("a.hasUl").addClass("drop").addClass("active"),ulElem.addClass("expand")),setLocalStore(el_tpl_settings.enc_usr_var+"_mp",$(this).attr("href")),c=!0,!1})):mainNavUl.find("[rel='home']").parents("ul.sub").show(),mainNavLink.off("click"),mainNavLink.click(function(a){var b=$(this);b.hasClass("hasUl")?(a.preventDefault(),b.hasClass("drop")?($(this).find("span.hasDrop").removeClass("icomoon-icon-arrow-down-2"),$(this).find("span.hasDrop").addClass("icomoon-icon-arrow-right-2"),
$(this).siblings("ul.sub").slideUp(500,"swing").siblings().removeClass("drop")):(mainNavUl.find("span.hasDrop").removeClass("icomoon-icon-arrow-down-2"),mainNavUl.find("span.hasDrop").addClass("icomoon-icon-arrow-right-2"),mainNavUl.find("ul.sub").slideUp(500,"swing").siblings().removeClass("drop"),$(this).find("span.hasDrop").removeClass("icomoon-icon-arrow-right-2"),$(this).find("span.hasDrop").addClass("icomoon-icon-arrow-down-2"),$(this).siblings("ul.sub").slideDown(500,"swing").siblings().addClass("drop")),
setTimeout(function(){adjustLeftMenuScrollBar()},501)):(setLocalStore(el_tpl_settings.enc_usr_var+"_mc",b.attr("href")),hideLeftScrollBar())}),mainNavSubLink.off("click"),mainNavSubLink.click(function(a){var b=$(this);b.hasClass("hasUl")?(a.preventDefault(),b.hasClass("drop")?$(this).siblings("ul.sub").slideUp(500).siblings().removeClass("drop"):$(this).siblings("ul.sub").slideDown(250).siblings().addClass("drop")):(mainNavUl.find("ul.sub a").removeClass("current"),$(this).addClass("current"),mainNavUl.find("li a.hasUl").removeClass("drop").removeClass("active"),
$(this).closest(".parent-menu-li").find("a.hasUl").addClass("drop").addClass("active"),setLocalStore(el_tpl_settings.enc_usr_var+"_mc",b.attr("href")))});$("a.nav-active-link").off("click");$(document).on("click","a.nav-active-link",function(a){a=$(this);var b=a.attr("aria-nav-code");if($(".top-menu").length){var c=$(".top-menu>ul");b=$(c).find("[aria-nav-code='"+b+"']");var d=b.closest(".parent-menu-li").find(".hasUl");$(d).hasClass("active")||$(d).trigger("click")}else c=$("#left_mainnav>ul"),b=
$(c).find("[aria-nav-code='"+b+"']"),d=b.closest(".parent-menu-li").find(".menu-parent-anchor"),$(d).hasClass("drop")||$(d).trigger("click");c.find("ul.sub a").removeClass("current");b.addClass("current");setLocalStore(el_tpl_settings.enc_usr_var+"_mc",a.attr("href"))});$("a.left-menu-hide").off("click");$(document).on("click","a.left-menu-hide",function(){$(this);$(this).mouseout();$("#collapse_btn").hasClass("hide")?setLocalStore(el_tpl_settings.enc_usr_var+"_sm","0"):setLocalStore(el_tpl_settings.enc_usr_var+
"_sm","1")});("1"==getLocalStore(el_tpl_settings.enc_usr_var+"_sm")||el_general_settings.mobile_platform)&&$("#collapse_btn").click();el_tpl_settings.page_animation||($("#content").css({"transition-duration":"0ms","-webkit-transition-duration":"0ms"}),$("#content_slide").css({"transition-duration":"0ms","-webkit-transition-duration":"0ms"}));adjustLeftMenuScrollBar()}
function isMenuURLMatched(a,b){if(a==b)return!0;var c=[];var d=a.split("#");var e=b.split("#");if(!d[1]||!e[1]||"index"!=c[2])return!1;var g=d[1].split("/");c=e[1].split("/");if(d[0]==e[0]&&g[0]==c[0]&&g[1]==c[1])return!0}function removeThemeSettings(){$("#switchBtn").remove();$("#switcher").remove()}
function createThemeSettings(){(function(){supr_switcher={create:function(){removeThemeSettings();var a=el_theme_settings.themes_list,b=el_theme_settings.themes_default,c=el_theme_settings.themes_custom,d=el_theme_settings.theme_settings,e={},g="",p="",t="",q="",u="",h="",k="",l="";if(d.theme){switch(d.theme){case "metronic":q=d.color;break;case "cit":q=d.color;break;default:h=d.pattern_0,k=d.pattern_1,l=d.pattern_2}u=d.custom}if(b&&$.isPlainObject(b))for(var f in b){d="";var m=1;for(var r in b[f]){var n=
"";0==m%5&&b[f].length!=m&&(n="<br/>");d+='<li class="theme-color-li"><a href="javascript://" style="background:'+b[f][r].color+'" aria-color="'+b[f][r].file+'" class="color-default '+(q==b[f][r].file?"active":"")+'"></a></li>'+n+"\n";m++}e[f]=d}for(f in a)g+='<option value="'+f+'">'+a[f]+"</option>";if(c&&c.length){m=1;for(f in c)n="",0==m%5&&c.length!=m&&(n="<br/>"),t+='<li class="custom-color-li"><a href="javascript://" style="background:'+c[f].color+'" aria-color="'+c[f].file+'" class="color-default '+
(u==c[f].file?"active":"")+'"></a></li>'+n+"\n",m++;p='<div id="_theme_custom_patterns" class="custom-theme-patterns">\n        <h4>Theme Customize<i class="icon minia-icon-close-2" id="_custom_color_remove"></i></h4>\n        <div class="custom-theme-colors">\n            <ul>\n                '+t+'\n                <li><button class="btn btn-success btn-switch" id="_custom_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n    </div>'}$("body").append('<a href="javascript://" id="switchBtn" class="switch-btn"><span class="icon24 icomoon-icon-cogs"></span></a>');
$("body").append('<div id="switcher" class="switcher-block">\n    <h4>'+js_lang_label.GENERIC_MENU_POSITION+'</h4>\n    <div class="menu-position">\n        <ul>\n            <li><input type="radio" name="_theme_menu_postion" id="_theme_menu_left" value="Left" class="regular-radio"/><label for="_theme_menu_left">&nbsp;</label><label for="_theme_menu_left">'+js_lang_label.GENERIC_LEFT+' </label>&nbsp;</li>\n            <li><input type="radio" name="_theme_menu_postion" id="_theme_menu_top" value="Top" class="regular-radio"/><label for="_theme_menu_top">&nbsp;</label><label for="_theme_menu_top">'+
js_lang_label.GENERIC_TOP+' </label></li>\n            <li><button class="btn btn-success btn-switch" id="_theme_menu_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n        </ul>\n    </div>\n    <h4>'+js_lang_label.GENERIC_CHANGE_THEME+' </h4>\n    <div class="theme-position">\n        <ul>\n            <li>\n                <select name="_theme_change_select" id="_theme_change_select">\n                    '+g+'\n                </select>\n                &nbsp;\n            </li>\n            <li><button class="btn btn-success btn-switch" id="_theme_change_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n        </ul>\n    </div>\n    <div id="_theme_supr_patterns" class="supr-theme-patterns">\n        <h4>'+
js_lang_label.GENERIC_HEADER_PATTERNS+'</h4>\n        <div class="header-patterns">\n            <ul>\n                <li><a href="javascript://" class="hpat0 hpat-default '+(""==h||"default"==h?"active":"")+'"></a></li>\n                <li class="hpat_bedge_grunge"><a href="javascript://" class="hpat1 hpat-default '+("bedge_grunge"==h?"active":"")+'"></a></li>\n                <li class="hpat_grid"><a href="javascript://" class="hpat2 hpat-default '+("grid"==h?"active":"")+'"></a></li>\n                <li class="hpat_nasty_fabric"><a href="javascript://" class="hpat3 hpat-default '+
("nasty_fabric"==h?"active":"")+'"></a></li>\n                <li class="hpat_natural_paper"><a href="javascript://" class="hpat4 hpat-default '+("natural_paper"==h?"active":"")+'"></a></li>\n                <li><button class="btn btn-success btn-switch" id="_theme_header_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n        <h4>'+js_lang_label.GENERIC_SIDEBAR_PATTERNS+'</h4>\n        <div class="sidebar-patterns">\n            <ul>\n                <li><a href="javascript://" class="spat0 spat-default '+
(""==k||"default"==k?"active":"")+'"></a></li>\n                <li class="hpat_az_subtle"><a href="javascript://" class="spat1 spat-default '+("az_subtle"==k?"active":"")+'"></a></li>\n                <li class="hpat_billie_holiday"><a href="javascript://" class="spat2 spat-default '+("billie_holiday"==k?"active":"")+'"></a></li>\n                <li class="hpat_grey"><a href="javascript://" class="spat3 spat-default '+("grey"==k?"active":"")+'"></a></li>\n                <li class="hpat_noise_lines"><a href="javascript://" class="spat4 spat-default '+
("noise_lines"==k?"active":"")+'"></a></li>\n                <li><button class="btn btn-success btn-switch" id="_theme_sidebar_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n        <h4>'+js_lang_label.GENERIC_BODY_PATTERNS+'</h4>\n        <div class="body-patterns">\n            <ul>\n                <li><a href="javascript://" class="bpat0 bpat-default '+(""==l||"default"==l?"active":"")+'"></a></li>\n                <li class="hpat_cream_dust"><a href="javascript://" class="bpat1 bpat-default '+
("cream_dust"==l?"active":"")+'"></a></li>\n                <li class="hpat_dust"><a href="javascript://" class="bpat2 bpat-default '+("dust"==l?"active":"")+'"></a></li>\n                <li class="hpat_grey"><a href="javascript://" class="bpat3 bpat-default '+("grey"==l?"active":"")+'"></a></li>\n                <li class="hpat_subtle_dots"><a href="javascript://" class="bpat4 bpat-default '+("subtle_dots"==l?"active":"")+'"></a></li>\n                <li><button class="btn btn-success btn-switch" id="_theme_body_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n    </div>\n    <div id="_theme_metronic_patterns" class="metronic-theme-patterns">\n        <h4>'+
js_lang_label.GENERIC_THEME_COLOR+'</h4>\n        <div class="metronic-theme-colors">\n            <ul>\n                '+e.metronic+'\n                <li><button class="btn btn-success btn-switch" id="_metronic_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n    </div>\n    <div id="_theme_cit_patterns" class="cit-theme-patterns">\n        <h4>Theme Color</h4>\n        <div class="cit-theme-colors">\n            <ul>\n                '+
e.cit+'\n                <li><button class="btn btn-success btn-switch" id="_cit_color_save" title="Save"><span class="icon16 icomoon-icon-checkmark-2 white"></span></button></li>\n            </ul>\n        </div>\n    </div>\n    '+p+"\n</div>")},toggle:function(){"metronic"==el_tpl_settings.admin_theme?($("#_theme_supr_patterns").hide(),$("#_theme_metronic_patterns").show(),$("#_theme_cit_patterns").hide()):"cit"==el_tpl_settings.admin_theme?($("#_theme_supr_patterns").hide(),$("#_theme_metronic_patterns").hide(),
$("#_theme_cit_patterns").show()):($("#_theme_supr_patterns").show(),$("#_theme_metronic_patterns").hide(),$("#_theme_cit_patterns").hide())},apply:function(a,b){var c=admin_url+""+cus_enc_url_json.general_preferences_change;confirm(js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_APPLY_THESE_CHANGES)&&$.ajax({url:c,type:"POST",data:{type:a,value:b},success:function(a){a=$.parseJSON(a);var b=1;"0"==a.success&&(b=0);Project.setMessage(a.message,b);"1"==a.success&&confirm(js_lang_label.GENERIC_ARE_YOU_SURE_WANT_TO_RELOAD)&&
document.location.reload()}})},init:function(){supr_switcher.create();"Top"==el_tpl_settings.menu_poistion?$("#_theme_menu_top").attr("checked",!0):$("#_theme_menu_left").attr("checked",!0);$("#_theme_change_select").val(el_tpl_settings.admin_theme);supr_switcher.toggle();$("#_theme_change_select").change(function(){$(this).val()==el_tpl_settings.admin_theme?supr_switcher.toggle():($("#_theme_supr_patterns").hide(),$("#_theme_metronic_patterns").hide(),$("#_theme_cit_patterns").hide())});$("#switcher .supr-theme-patterns a").on("click",
function(){$(this).hasClass("hpat-default")?($("#switcher .supr-theme-patterns a.hpat-default").removeClass("active"),$(this).addClass("active"),$(".top-bg").removeClass("bedge_grunge").removeClass("grid").removeClass("nasty_fabric").removeClass("natural_paper"),$("#_theme_header_save").attr("aria-pattern","default"),$(this).hasClass("hpat1")?($(".top-bg").addClass("bedge_grunge"),$("#_theme_header_save").attr("aria-pattern","bedge_grunge")):$(this).hasClass("hpat2")?($(".top-bg").addClass("grid"),
$("#_theme_header_save").attr("aria-pattern","grid")):$(this).hasClass("hpat3")?($(".top-bg").addClass("nasty_fabric"),$("#_theme_header_save").attr("aria-pattern","nasty_fabric")):$(this).hasClass("hpat4")&&($(".top-bg").addClass("natural_paper"),$("#_theme_header_save").attr("aria-pattern","natural_paper"))):$(this).hasClass("spat-default")?($("#switcher .supr-theme-patterns a.spat-default").removeClass("active"),$(this).addClass("active"),$("#sidebarbg").removeClass("az_subtle").removeClass("billie_holiday").removeClass("grey").removeClass("noise_lines"),
$("#_theme_sidebar_save").attr("aria-pattern","default"),$(this).hasClass("spat1")?($("#sidebarbg").addClass("az_subtle"),$("#_theme_sidebar_save").attr("aria-pattern","az_subtle")):$(this).hasClass("spat2")?($("#sidebarbg").addClass("billie_holiday"),$("#_theme_sidebar_save").attr("aria-pattern","billie_holiday")):$(this).hasClass("spat3")?($("#sidebarbg").addClass("grey"),$("#_theme_sidebar_save").attr("aria-pattern","grey")):$(this).hasClass("spat4")&&($("#sidebarbg").addClass("noise_lines"),$("#_theme_sidebar_save").attr("aria-pattern",
"noise_lines"))):$(this).hasClass("bpat-default")&&($("#switcher .supr-theme-patterns a.bpat-default").removeClass("active"),$(this).addClass("active"),$("#content").removeClass("cream_dust").removeClass("dust").removeClass("grey").removeClass("subtle_dots"),$("#content_slide").removeClass("cream_dust").removeClass("dust").removeClass("grey").removeClass("subtle_dots"),$("#_theme_body_save").attr("aria-pattern","default"),$(this).hasClass("bpat1")?($("#content").addClass("cream_dust"),$("#content_slide").addClass("cream_dust"),
$("#_theme_body_save").attr("aria-pattern","cream_dust")):$(this).hasClass("bpat2")?($("#content").addClass("dust"),$("#content_slide").addClass("dust"),$("#_theme_body_save").attr("aria-pattern","dust")):$(this).hasClass("bpat3")?($("#content").addClass("grey"),$("#content_slide").addClass("grey"),$("#_theme_body_save").attr("aria-pattern","grey")):$(this).hasClass("bpat4")&&($("#content").addClass("subtle_dots"),$("#content_slide").addClass("subtle_dots"),$("#_theme_body_save").attr("aria-pattern",
"subtle_dots")))});$("#switcher .metronic-theme-patterns a").on("click",function(){$("#switcher .metronic-theme-patterns a").removeClass("active");$(this).addClass("active");$("[aria-theme-style='metronic']").remove();if($(this).attr("aria-color")){var a=$(this).attr("aria-color"),b=$("<link rel='stylesheet' aria-theme-style='metronic' type='text/css' href='"+style_url+"theme/metronic/theme_"+a+".css'>");$("body").append(b);$("#_metronic_color_save").attr("aria-theme-color",a)}});$("#switcher .cit-theme-patterns a").on("click",
function(){$("#switcher .cit-theme-patterns a").removeClass("active");$(this).addClass("active");$("[aria-theme-style='cit']").remove();if($(this).attr("aria-color")){var a=$(this).attr("aria-color"),b=$("<link rel='stylesheet' aria-theme-style='cit' type='text/css' href='"+style_url+"theme/cit/theme_"+a+".css'>");$("body").append(b);$("#_cit_color_save").attr("aria-theme-color",a)}});$("#switcher .custom-theme-patterns a").on("click",function(){$("#switcher .custom-theme-patterns a").removeClass("active");
$(this).addClass("active");$("[aria-theme-style='custom']").remove();if($(this).attr("aria-color")){var a=$(this).attr("aria-color"),b=$("<link rel='stylesheet' aria-theme-style='custom' type='text/css' href='"+style_url+"theme/"+a+".css'>");$("body").append(b);$("#_custom_color_save").attr("aria-custom-color",a)}});$("#switchBtn").on("click",function(){$(this).hasClass("toggle")?($(this).removeClass("toggle").css("right","-1px"),$("#switcher").css("display","none")):$(this).animate({right:"210"},
200,function(){$("#switcher").css("display","block");$(this).addClass("toggle")})});$("#_theme_menu_save").on("click",function(){supr_switcher.apply("menu",$("input[type='radio'][name='_theme_menu_postion']:checked").val())});$("#_theme_change_save").on("click",function(){supr_switcher.apply("theme",$("#_theme_change_select").val())});$("#_theme_header_save").on("click",function(){supr_switcher.apply("header",$(this).attr("aria-pattern"))});$("#_theme_sidebar_save").on("click",function(){supr_switcher.apply("sidebar",
$(this).attr("aria-pattern"))});$("#_theme_body_save").on("click",function(){supr_switcher.apply("body",$(this).attr("aria-pattern"))});$("#_metronic_color_save").on("click",function(){supr_switcher.apply("color",$(this).attr("aria-theme-color"))});$("#_cit_color_save").on("click",function(){supr_switcher.apply("color",$(this).attr("aria-theme-color"))});$("#_custom_color_save").on("click",function(){supr_switcher.apply("custom",$(this).attr("aria-custom-color"))});$("#_custom_color_remove").on("click",
function(){supr_switcher.apply("custom","")})}};supr_switcher.init()})()}
function fullCollapseLeftMenu(a){var b=animateSidePanel(!1);a.hasClass("hide")?(a.css("z-index","1000"),$("#sidebarbg").animate({marginLeft:"0px"},500),$("#sidebar").animate({left:"0",marginLeft:"0px"},500),$("#main_content_div").animate({marginLeft:"210px"},250),$("#collapse_btn.leftbar").animate({left:b.mlef,top:b.mtop},500).removeClass("shadow"),$("#left_mainnav").find(".menu-parent-anchor.active").addClass("drop"),a.removeClass("hide"),a.children("a").attr("title",js_lang_label.GENERIC_HIDE_SIDEBAR),
initializeMenuCollpaseEvents(),setTimeout(function(){resizeGridWidth();resizeDSGridWidth();adjustLeftMenuScrollBar();initNiceScrollBar()},501)):(b=animateSidePanel(!0),a.css("z-index","1000"),$("#sidebarbg").animate({marginLeft:"-299px"},500),$("#sidebar").animate({marginLeft:"-299px"},500),$("#main_content_div").animate({marginLeft:"0px"},250),$("#collapse_btn.leftbar").animate({left:b.mlef,top:b.mtop},500).addClass("shadow"),a.addClass("hide"),a.children("a").attr("title",js_lang_label.GENERIC_SHOW_SIDEBAR),
initializeMenuCollpaseEvents(),hideLeftScrollBar(),setTimeout(function(){resizeGridWidth();resizeDSGridWidth()},501))}
function semiCollapseLeftMenu(a){if(a.hasClass("hide")){var b=animateSidePanel(!1);$("#left_mainnav").removeClass("semi-collapse-border");$("#left_mainnav ul sub").removeClass("semi-item-show");$("#left_mainnav ul li").removeClass("semi-left-menu-items");$("#sidebar").removeClass("semi-collapse-menu");$("#sidebar_widget").find(".sidebar-navigation").css({visibility:"visible"});a.css("z-index","1000");$("#sidebar").animate({left:"0",marginLeft:"0px"},500);$("#sidebarbg").animate({marginLeft:"0px"},
500);$("#sidebar_widget").animate({width:"100%"},150);$("#left_mainnav").animate({width:"210px"},500);$("#main_content_div").animate({marginLeft:"210px"},500);$("#collapse_btn.leftbar").animate({left:b.mlef,top:b.mtop},500).removeClass("shadow");$("#left_mainnav ul a").siblings().removeClass("semi-item-none");$("#left_mainnav ul li").removeClass("semi-left-menu-items");$("#left_mainnav").find(".menu-parent-anchor.active").addClass("drop");a.removeClass("hide");a.children("a").attr("title",js_lang_label.GENERIC_HIDE_SIDEBAR);
initializeMenuCollpaseEvents();setTimeout(function(){resizeGridWidth();resizeDSGridWidth();adjustLeftMenuScrollBar();initNiceScrollBar()},501);$(document).off("mouseover",".semi-left-menu-items")}else b=animateSidePanel(!0),$("#left_mainnav").addClass("semi-collapse-border"),$("#left_mainnav ul li").addClass("semi-left-menu-items"),$("#left_mainnav ul a").siblings().addClass("semi-item-none"),$("#sidebar").addClass("semi-collapse-menu"),$("#sidebar_widget").find(".sidebar-navigation").css({visibility:"hidden"}),
a.css("z-index","1000"),$("#sidebarbg").animate({marginLeft:"-299px"},500),$("#sidebar_widget").animate({width:"50px"},500),$("#left_mainnav").animate({width:"50px"},500),$("#main_content_div").animate({marginLeft:"50px"},500),$("#collapse_btn.leftbar").animate({left:b.mlef,top:b.mtop},500).addClass("shadow"),a.addClass("hide"),a.children("a").attr("title",js_lang_label.GENERIC_SHOW_SIDEBAR),initializeMenuCollpaseEvents(),getLeftPanelSemiMenu(),setTimeout(function(){resizeGridWidth();resizeDSGridWidth();
adjustLeftMenuScrollBar()},501)}
function getLeftPanelSemiMenu(){$("#collapse_btn.leftbar").hasClass("hide")&&($(document).off("mouseover",".semi-left-menu-items"),$(document).on("mouseover",".semi-left-menu-items",function(){if($(this).find("a").hasClass("hasUl")){var a=$(this).offset().top-$(window).scrollTop();$(this).find("a").siblings().addClass("expand").addClass("semi-item-show").removeClass("semi-item-none");var b=$(this).find("a").siblings().outerHeight(),b=a+b+26;var c=a-60;$(this).find("a").siblings().css({top:c+"px"});
b>$(window).height()&&$("#left_mainnav").hasClass("semi-collapse-border")?(a=$(window).height()-a-27,$(this).find("a").siblings().css({height:a+"px"}),scrollSubMenuContent()):($(this).find("a").siblings().css({height:"auto"}),$(".parent-menu-li>.sub").getNiceScroll().remove());$("#left_mainnav").find("a.hasUl").removeClass("semi-menu-active");$(this).find("a.hasUl").addClass("semi-menu-active")}}),hideLeftPanelSemiMenu())}
function hideLeftPanelSemiMenu(){$(document).off("mouseout",".semi-left-menu-items");$(document).on("mouseout",".semi-left-menu-items",function(){$(".semi-left-menu-items").find("a").siblings().removeClass("expand").addClass("semi-item-none").removeClass("semi-item-show");$(".semi-left-menu-items").find("a.hasUl").removeClass("semi-menu-active")})}
function animateSidePanel(a){var b={};switch(el_tpl_settings.admin_theme){case "cit":1==a?(b.mtop=68,b.mlef=10):(b.mtop=68,b.mlef=175);break;default:1==a?(b.mtop=66,b.mlef=10):(b.mtop=66,b.mlef=175)}return b}function getLocalStore(a,b){if(!localStorage)return"";if(1==b){var c=localStorage.getItem(a);try{c=checkStorageTimeStamp($.parseJSON(c),a)}catch(d){}}else c=localStorage.getItem(a);return c}
function setLocalStore(a,b,c){if(!localStorage)return!1;if(1==c){c=localStorage.getItem(a);try{c=$.parseJSON(c)}catch(e){}try{var d=$.parseJSON(b);$.isPlainObject(c)&&c.__timestamp?(d.__timestamp=c.__timestamp,b=JSON.stringify(d)):b=addStorageTimeStamp($.parseJSON(b))}catch(e){b=d}}localStorage.setItem(a,b);return!0}function isLocalStorageAllow(){return el_tpl_settings.grid_search_prefers?!0:!1}
function addStorageTimeStamp(a){if(!$.isPlainObject(a))return a;a.__timestamp=(new Date).getTime();return a=JSON.stringify(a)}function checkStorageTimeStamp(a,b){if(!$.isPlainObject(a))return a;if(a.__timestamp){var c=moment((new Date).getTime()).diff(a.__timestamp),c=moment.duration(c).asMinutes(),d=parseInt(el_tpl_settings.grid_search_expires),d=$.isNumeric(d)?d:1440;c>d&&(a={},localStorage.removeItem(b))}return a=JSON.stringify(a)}
function clearLocalStoreCache(){if(!localStorage)return!1;for(var a="_sh _cw _cp _cs _sg_cw _sg_cp _sg_cs _ng_cw _ng_cp _ng_cs".split(" "),b=Object.keys(localStorage),c=0;c<b.length;c++){var d=b[c];-1==$.inArray(d.slice(-3),a)&&-1==$.inArray(d.slice(-6),a)||localStorage.removeItem(d)}return!0}function scrollSubMenuContent(){$(".parent-menu-li>.sub.semi-item-show").niceScroll({cursoropacitymax:.7,cursorborderradius:8,cursorwidth:"2px",cursorcolor:"#636669"})}
function adjustLeftMenuScrollBar(){$("#left_mainnav").hasClass("semi-collapse-border")?hideLeftScrollBar():($(".parent-menu-li>.sub").getNiceScroll().remove(),$(".parent-menu-li>.sub").css({height:"auto"}),initLeftScrollBar())}function jqueryUIdialogBox(a,b,c){c=$.extend({},{title:"Alert",autoOpen:!0,bgiframe:!0,modal:!0,open:function(a){applyUIButtonCSS()}},c);$(a).html(b).dialog(c)}
function applyUIButtonCSS(){$(".ui-dialog-buttonset").find(":button").each(function(){$(this).addClass("fm-button ui-state-default ui-corner-all ui-dialog-button-hover");var a=$(this).attr("bt_type");if(a){switch(a){case "ok":a="ui-icon-check";break;case "cancel":a="ui-icon-cancel";break;case "delete":a="ui-icon-scissors";break;case "backup":a="ui-icon-disk";break;case "download":a="ui-icon-arrowthickstop-1-s";break;case "continue":a="ui-icon-arrowthickstop-1-e";break;default:a=""}$(this).addClass("fm-button-icon-left");
$(this).append('<span class="ui-button-icon-primary ui-icon '+a+'"></span>')}})};