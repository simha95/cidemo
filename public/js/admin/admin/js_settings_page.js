Project.modules.settings={init:function(){this.initEvents()},initEvents:function(){$("textarea").length&&$("textarea").elastic();editor_events_arr&&""!=editor_events_arr&&(tinyMCE.baseURL=el_tpl_settings.editor_js_url,$(editor_events_arr).each(function(a){$("#"+editor_events_arr[a]).tinymce({script_url:el_tpl_settings.editor_js_url+"tinymce.min.js",content_css:el_tpl_settings.editor_css_url+"style.css",plugins:tinymce_editor_plugins_premium,toolbar:tinymce_editor_tollbar_premium,templates:tinymce_editor_templates,
theme:"modern",resize:"both",skin:"light",image_advtab:!0,relative_urls:!1,remove_script_host:!1,external_filemanager_path:site_url+"filemanager/",filemanager_title:"Filemanager",external_plugins:{filemanager:el_tpl_settings.js_libraries_url+"filemanager/plugin.min.js"},setup:function(d){d.on("change",function(a){tinyMCE.triggerSave()});d.on("blur",function(b){b=d.getContent({format:"text"});Project.modules.settings.settingMultilingualEditorContent(b,$("#"+editor_events_arr[a]))});d.on("click",function(a){tinyMCE.get(d.id).focus()})}})}));
upload_events_arr&&""!=upload_events_arr&&$(upload_events_arr).each(function(a){1==upload_events_arr[a].file_exist?displaySettingOntheFlyImage(upload_events_arr[a].name,{fileURL:upload_events_arr[a].file_url,fileType:upload_events_arr[a].file_type,width:upload_events_arr[a].fwidth,height:upload_events_arr[a].fheight}):$("#preview_"+upload_events_arr[a].name).html(js_lang_label.GENERIC_UPLOAD_FILE);$("#upload_drop_zone_"+upload_events_arr[a].name).width($("#uploadify_"+upload_events_arr[a].name).width()+
18);$("#uploadify_"+upload_events_arr[a].name).fileupload({url:$upload_form_file,name:upload_events_arr[a].name,temp:"temp_"+upload_events_arr[a].name,paramName:"Filedata",maxFileSize:upload_events_arr[a].file_size,acceptFileTypes:upload_events_arr[a].file_ext,dropZone:$("#upload_drop_zone_"+upload_events_arr[a].name+", #upload_drop_zone_"+upload_events_arr[a].name+" + .upload-src-zone"),formData:{vSettingName:upload_events_arr[a].name,actionType:"upload",type:"uploadify"},add:function(a,b){var c=
[],e=$(this).fileupload("option","name"),f=$(this).fileupload("option","temp"),k=$(this).fileupload("option","formData"),n=$(this).fileupload("option","maxFileSize"),g=$(this).fileupload("option","acceptFileTypes"),h=b.originalFiles[0].name,l=b.originalFiles[0].size;if("*"!=g){var m=h?h.substr(h.lastIndexOf(".")):"",g=new RegExp("(.|/)("+g+")$","i");m&&!g.test(m)&&c.push(js_lang_label.ACTION_FILE_TYPE_IS_NOT_ACCEPTABLE)}l&&l>1E3*n&&c.push(js_lang_label.ACTION_FILE_SIZE_IS_TOO_LARGE);0<c.length?Project.setMessage(c.join("\n"),
0):($("#practive_"+e).css("width","0%"),$("#progress_"+e).show(),k.oldFile=$("#"+f).val(),$(this).fileupload("option","formData",k),$("#preview_"+e).html(h),b.submit())},done:function(a,b){if(b&&b.result){var c=$(this).fileupload("option","name"),e=$(this).fileupload("option","temp"),f=$.parseJSON(b.result);"0"==f.success?Project.setMessage(f.message,0):($("#"+c).val(f.uploadfile),$("#"+e).val(f.oldfile),displaySettingOntheFlyImage(c,f),setTimeout(function(){$("#progress_"+c).hide()},1E3))}},fail:function(a,
b){$.each(b.messages,function(a,b){Project.setMessage(b,0)})},progressall:function(a,b){var c=$(this).fileupload("option","name"),e=parseInt(b.loaded/b.total*100,10);$("#practive_"+c).css("width",e+"%")}})})},getAdminSettingsValidate:function(){if($("#frmsettingslist").valid()){var a={beforeSubmit:showAdminAjaxRequest,success:function(a,b,c,e){a=$.parseJSON(a);responseAjaxDataSubmission(a);if("0"==a.success)return!1;Project.modules.settings.loadAdminSettingsPage(a.type)}};$("#frmsettingslist").ajaxSubmit(a)}return!1},
loadAdminSettingsPage:function(a,d){},settingMultilingualEditorContent:function(a,d){var b=prime_lang_code,c=$.parseJSON(other_lang_JSON);if(!b||!c)return!1;if(!el_tpl_settings.multi_lingual_trans)return Project.setMessage(js_lang_label.GENERIC_LANGUAGE_TRANSLATION_IS_TURNED_OFF,2,200),!1;var e=d.attr("id");""!=$.trim(a)&&(showhide_inline_loading(d,"show"),$.ajax({url:admin_url+cus_enc_url_json.general_language_conversion,type:"POST",data:{text:a,src:b,"dest[]":c},success:function(a){showhide_inline_loading(d,
"hide");if(a=$.parseJSON(a))for(var b in a)$("#lang_"+e+b).val(a[b])}}))}};