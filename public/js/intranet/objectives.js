$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$("#act_date_start").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function(date){
        $("#act_date_end").datepicker('option', 'minDate', date);
    }
});

$("#act_date_end").datepicker({
    dateFormat: "dd/mm/yy",
});

$("#area-sel").on("change",function(ev){
    var area_id = $(this).val();
    if(area_id != "0"){
        $("#form-area-sel").submit();
    }
});

$("#item_save").on("click", function(ev){
    ev.preventDefault();
    // checks
    var new_role = $("#newRoleSwitch").prop('checked');
    var new_theme = $("#newThemeSwitch").prop('checked');
    var new_obj = $("#newObjSwitch").prop('checked');
    // selects
    var role_sel = $("#role_sel").val();
    var theme_sel = $("#theme_sel").val();
    var obj_sel = $("#obj_sel").val();
    // inputs
    var role_name = $("#role_name").val().trim();
    var theme_name = $("#theme_name").val().trim();
    var obj_name = $("#obj_name").val().trim();
    var act_name = $("#activity_desc").val().trim();
    var date_start = $("#act_date_start").val().trim();
    var date_end = $("#act_date_end").val().trim();
    
    

    var role_ok = true;
    var theme_ok = true;
    var obj_ok = true;
    var act_ok = (act_name != "");
    var start_ok = (date_start != "");
    var end_ok = (date_end != "");

    var errors = "";

    // validate role inputs
    if(new_role){
        role_ok = (role_name != "");
    }else{
        role_ok = (role_sel != "0");
    }

    if(new_theme){
        theme_ok = (theme_name != "");
    }else{
        theme_ok = (theme_sel != "0");
    }

    if(new_obj){
        obj_ok = (obj_name != "");
    }else{
        obj_ok = (obj_sel != "0");
    }

    if(!role_ok || !theme_ok || !obj_ok || !act_ok || !start_ok || !end_ok){
        if(!role_ok){
            errors += "<li>Ingrese un rol correctamente</li>";
        }
        if(!theme_ok){
            errors += "<li>Ingrese un tema correctamente</li>";
        }
        if(!obj_ok){
            errors += "<li>Ingrese un objetivo correctamente</li>";
        }
        if(!act_ok){
            errors += "<li>Ingrese una actividad correctamente</li>";
        }
        if(!start_ok){
            errors += "<li>Ingrese una fecha de inicio</li>";
        }
        if(!end_ok){
            errors += "<li>Ingrese una fecha final</li>";
        }
    }else{
        $("#role_form").submit();
    }
    $("#item_error ul").html(errors);
});

$(".toggle-comments").on("click",function(ev){
    ev.preventDefault();
    if($(".btn-comment").is(":hidden")){
        $(".btn-comment").show();
        $(this).find("span").html("Ocultar Comentarios");
    }else{
        $(".btn-comment").hide();
        $(this).find("span").html("Ver Comentarios");
    }
});

$(".btn-comment").on('click',function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var act = $(this).data("act");
    $.ajax({
        url: route,
        method: 'get',
        data: {
            activity: act
        },
        success: function(res){
            $("#commentModal").html(res);
            $("#commentModal").modal("show");
        }
    });
});

$(document).on("click","#comm_update",function(ev){
    ev.preventDefault();
    var route = $("#comments_form").attr('action');
    var modal_route = $(this).attr("src");
    $.ajax({
        url: route,
        method: 'POST',
        data: $("#comments_form").serialize(),
        success: function(res){
            if(res.status == "ok"){
                // reload the modal content
                $.ajax({
                    url: modal_route,
                    method: 'GET',
                    success: function(modal){
                        $("#commentModal").html(modal);
                    }
                });
            }else{
                alert(res.msg);
            }
        }
    });
})

$(document).on("click",".comm-delete",function(ev){
    ev.preventDefault();
    var comm_id = $(this).attr('commid');
    var route = $(this).attr('href');
    $.ajax({
        url: route,
        method: 'POST',
        data: {
            _token: $("[name='_token']").val(),
            comment: comm_id,
        },
        success: function(res){
            if(res.status == "ok"){
                $(".comm-group[commid='"+comm_id+"']").remove();
            }else{
                alert(res.msg);
            }
        }
    });
});



$(".new_item_switch").on("change",function(ev){
    var object = $(this).data("object");
    var n = $(this).prop("checked");
    if(n){
        if(["obj","theme","role"].includes(object)){
            $("#obj_sel").hide();
            $("#obj_name").show();
            $("#newObjSwitch").prop("checked", true);
        }
        
        if(["theme","role"].includes(object)){
            $("#theme_sel").hide();
            $("#theme_name").show();
            $("#newThemeSwitch").prop("checked", true);
        }
        
        if(["role"].includes(object)){
            $("#role_sel").hide();
            $("#role_name").show();
            $("#newRoleSwitch").prop("checked", true);
        }
    }else{
        if(["obj","theme","role"].includes(object)){
            $("#role_sel").show();
            $("#role_name").hide();
            $("#newRoleSwitch").prop("checked", false);
        }
        if(["obj","theme"].includes(object)){
            $("#theme_sel").show();
            $("#theme_name").hide();
            $("#newThemeSwitch").prop("checked", false);
        }
        if(["obj"].includes(object)){
            $("#obj_sel").show();
            $("#obj_name").hide();
            $("#newObjSwitch").prop("checked", false);
        }
    }
});

$("#role_sel").on("change",function(){
    var role_id = $("#role_sel option:selected").val();
    var role = global_items.find(role=> role.id == role_id);

    var theme_options = "";
    var obj_options = "";

    var x = 0;
    role.themes.forEach(theme => {
        theme_options += "<option value='"+theme.id+"'>"+
                         ((theme.id < 10)?"0"+theme.id:theme.id)+": "+
                         theme.nombre+
                         "</option>";
        if(x == 0){
            theme.objectives.forEach(objective => {
                obj_options += "<option value='"+objective.id+"'>"+
                                ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                objective.nombre+
                                "</option>";
            });
        }
    });

    if(theme_options == ""){
        theme_options = "<option value='0'>-- No hay Temas disponibles --</option>";
    }
    if(obj_options == ""){
        obj_options = "<option value='0'>-- No hay Objetivos disponibles --</option>";
    }

    $("#theme_sel").html(theme_options);
    $("#obj_sel").html(obj_options);

});

$("#theme_sel").on("change",function(){
    var theme_id = $("#theme_sel option:selected").val();
    var role = global_items.find(role=> role.themes.find(theme=>theme.id == theme_id));

    var obj_options = "";

    var x = 0;
    role.themes.forEach(theme => {
        if(theme.id == theme_id){
            theme.objectives.forEach(objective => {
                obj_options += "<option value='"+objective.id+"'>"+
                                ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                objective.nombre+
                                "</option>";
            });
        }
    });

    if(obj_options == ""){
        obj_options = "<option value='0'>-- No hay Objetivos disponibles --</option>";
    }
    
    $("#obj_sel").html(obj_options);
});

// btn that opens policy show/upload modal
$(".btn-show-policy").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).data("id");
    var filename = $(this).data("filename");
    var fileid = $(this).data("fileid");

    // prepare update contents
    $("[name='p_file']").val(null);
    $("[name='p_edit']").val("false");
    $("[name='p_act_id']").val(id);
    $("#p_error").html("");

    // prepare download contents
    $("#p_filename").html(filename);
    $("#p_file_download").attr("file-id", fileid);
    $("#p_file_delete").attr("file-id", fileid);
    $(".file-downloadable").hide();
    
    if(fileid != ""){
        $(".file-downloadable").show();
    }

    // show modal
    $("#policyModal").modal("show");
});

// btn that opens adjacent document show/upload modal
$(".btn-show-adjacent").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).data("id");
    var filename = $(this).data("filename");
    var fileid = $(this).data("fileid");

    // prepare update contents
    $("[name='a_file']").val(null);
    $("[name='a_edit']").val("false");
    $("[name='a_act_id']").val(id);
    $("#a_error").html("");

    // prepare download/delete contents
    $("#a_filename").html(filename);
    $("#a_file_download").attr("file-id", fileid);
    $("#a_file_delete").attr("file-id", fileid);
    $(".file-downloadable").hide();
    if(fileid != ""){
        $(".file-downloadable").show();
    }

    // show modal
    $("#adjacentModal").modal("show");
});

$(document).on("change","[name='p_file']",function(ev){
    $("[name='p_edit']").val("true");
});

$(document).on("change","[name='a_file']",function(ev){
    $("[name='a_edit']").val("true");
});

$("#pol_save").on("click", function(ev){
    ev.preventDefault();
    var form = $("#policy-form");
    var data = new FormData(form[0]);
    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function(res){
            if(res.status == "ok"){
                var act_id = $("[name='p_act_id']").val();

                // change table btn
                var btn = $("a.btn-show-policy[data-id="+act_id+"]");
                var icon = btn.find("use");
                btn.data("filename",res.doc_name);
                btn.data("fileid",res.doc_id);
                if(btn.hasClass("btn-warning")){
                    btn.removeClass("btn-warning");
                    btn.addClass("btn-success");
                }
                var href = icon.attr("xlink:href");
                href = href.replace("cil-arrow-thick-from-bottom","cil-file");
                icon.attr("xlink:href", href); 

                // update file form
                $("[name='p_file']").val(null);
                $("[name='p_edit']").val("false");

                // update file download
                $("#p_filename").html(res.doc_name);
                $("#p_file_download").attr("file-id", res.doc_id);
                $("#p_file_delete").attr("file-id", res.doc_id);
                $(".file-downloadable").show();

            }else{
                $("#p_error").html(res.msg);
            }
        }
    });
});

$("#adj_save").on("click", function(ev){
    ev.preventDefault();
    var form = $("#adjacent-form");
    var data = new FormData(form[0]);
    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function(res){
            if(res.status == "ok"){
                var act_id = $("[name='a_act_id']").val();

                // change table btn
                var btn = $("a.btn-show-adjacent[data-id="+act_id+"]");
                var icon = btn.find("use");
                btn.data("filename",res.doc_name);
                btn.data("fileid",res.doc_id);
                if(btn.hasClass("btn-warning")){
                    btn.removeClass("btn-warning");
                    btn.addClass("btn-success");
                }
                var href = icon.attr("xlink:href");
                href = href.replace("cil-arrow-thick-from-bottom","cil-file");
                icon.attr("xlink:href", href); 

                // update file form
                $("[name='a_file']").val(null);
                $("[name='a_edit']").val("false");

                // update file download
                $("#a_filename").html(res.doc_name);
                $("#a_file_download").attr("file-id", res.doc_id);
                $("#a_file_delete").attr("file-id", res.doc_id);
                $(".file-downloadable").show();

            }else{
                $("#a_error").html(res.msg);
            }
        }
    });
});

$(".btn-file-download").click(function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    route = route+"?id="+id;
    window.location.href = route+"?id="+id;
});

$(".btn-file-delete").click(function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    var type = $(this).attr("file-type");
    $.ajax({
        url: route,
        data: {
            id: id,
            _token: $("[name=_token]").val(),
        },
        method: "POST",
        success: function(res){
            if(res.status == "ok"){
                var act_id = null;
                
                // change table btn
                var btn = null;
                if(type == "pol"){
                    act_id = $("[name='p_act_id']").val();
                    btn = $("a.btn-show-policy[data-id="+act_id+"]");
                }else{
                    act_id = $("[name='a_act_id']").val();
                    btn = $("a.btn-show-adjacent[data-id="+act_id+"]");
                }
                var icon = btn.find("use");
                btn.data("filename", "");
                btn.data("fileid", "");
                if(btn.hasClass("btn-success")){
                    btn.removeClass("btn-success");
                    btn.addClass("btn-warning");
                }
                var href = icon.attr("xlink:href");
                href = href.replace("cil-file","cil-arrow-thick-from-bottom");
                icon.attr("xlink:href", href); 

                // update file download
                if(type == "pol"){
                    $("#p_filename").html("");
                    $("#p_file_download").attr("file-id", null);
                    $("#p_file_delete").attr("file-id", null);
                }else{
                    $("#a_filename").html("");
                    $("#a_file_download").attr("file-id", null);
                    $("#a_file_delete").attr("file-id", null);
                }
                $(".file-downloadable").hide();

            }else{
                $("#a_error").html(res.msg);
            }
        }
    });
});

$(".btn-edit").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).data("act");
    var route = $(this).data("route");
    $("#editActivityModal").html("");
    $.ajax({
        url: route,
        data:{
            id: id,
        },
        method: "GET",
        success: function(res){
            $("#editActivityModal").html(res);

            $("#act_upd_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(date){
                    $("#act_upd_date_end").datepicker('option', 'minDate', date);
                }
            });
            
            $("#act_upd_date_end").datepicker({
                dateFormat: "dd/mm/yy",
                beforeShow: function(inp){
                    var date = $("#act_upd_date_start").datepicker('getDate');
                    $("#act_upd_date_end").datepicker('option', 'minDate', date);
                }
            });

            $("#editActivityModal").modal("show");
        }
    });
});

$(".btn-delete").on('click',function(ev){
    var type = "activity";
    var id = $(this).data('act');
    var obj = $(this).data('obj');
    var route = $(this).data('route');
    var name = $(this).parent().parent().find("td.t-act-name").text();

    $("#delete-item-confirm").attr('d-id',id);
    $("#delete-item-confirm").attr('d-route',route);
    $("#delete-item-confirm").attr('d-type',type);
    $("#delete-item-confirm").attr('d-obj',obj);
    $("#d-item-name").html("<strong>Actividad:</strong> "+name);

    $("#delItemModal").modal("show");
});

$('#delete-item-confirm').on('click', function(ev){
    ev.preventDefault();
    var id = $(this).attr('d-id');
    var route = $(this).attr('d-route');
    var type = $(this).attr('d-type');
    var obj = $(this).attr('d-obj');
    $.ajax({
        url: route,
        method: 'POST',
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(res){
            if(res.status == "ok"){
                getAllActivities();

                if(type == "activity"){
                    var row = $("tr[act-id='"+id+"']");
                    row.remove();

                    var obj_code = $(".t-obj-code[obj-id='"+obj+"']");
                    if(obj_code.length > 0){
                        obj_code.attr("rowspan",obj_code.length);
                        obj_code.first().show();
                    }
                    
                    var obj_name = $(".t-obj-name[obj-id='"+obj+"']");
                    if(obj_name.length > 0){
                        obj_name.attr("rowspan",obj_name.length);
                        obj_name.first().show();
                    }
                }else if(type == "theme"){
                    $(".theme-card[theme-id='"+id+"']").remove();
                    if($(".theme-card").length > 0){
                        var r_theme = $(".theme-card").last();
                        if(r_theme.hasClass("mb-3")){
                            r_theme.removeClass("mb-3");
                        }
                    }
                }else if(type == "role"){
                    $(".role-card[role-id='"+id+"']").remove();
                }

                $("#delItemModal").modal("hide");

            }else{
                alert(res.msg);
            }
        }
    });
});

$(".btn-theme-settings").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).attr("themeid");
    var route = $(this).attr("href");
    $("#editThemeModal").html("");
    $.ajax({
        url: route,
        data:{
            id: id,
        },
        method: "GET",
        success: function(res){
            $("#editThemeModal").html(res);
            $("#editThemeModal").modal("show");
        }
    });
});

$(".btn-role-settings").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).attr("roleid");
    var route = $(this).attr("href");
    $("#editRoleModal").html("");
    $.ajax({
        url: route,
        data:{
            id: id,
        },
        method: "GET",
        success: function(res){
            $("#editRoleModal").html(res);
            $("#editRoleModal").modal("show");
        }
    });
});

$(document).on("click","#item_update",function(ev){
    ev.preventDefault();
    $("#edit_activity_form").submit();
});

$(document).on("click","#theme_update",function(ev){
    ev.preventDefault();
    $("#edit_theme_form").submit();
});

$(document).on("click","#theme_delete",function(ev){
    ev.preventDefault();
    var type = "theme";
    var obj = "";
    var id = $(this).attr('theme-id');
    var route = $(this).attr('route');
    var name = $(this).attr('theme-name');

    $("#delete-item-confirm").attr('d-id',id);
    $("#delete-item-confirm").attr('d-route',route);
    $("#delete-item-confirm").attr('d-type',type);
    $("#delete-item-confirm").attr('d-obj',obj);
    $("#d-item-name").html("<strong>Tema:</strong> "+name);

    $("#editThemeModal").modal("hide");
    $("#delItemModal").modal("show");
});

$(document).on("click","#role_update",function(ev){
    ev.preventDefault();
    $("#edit_role_form").submit();
});

$(document).on("click","#role_delete",function(ev){
    ev.preventDefault();
    var type = "role";
    var obj = "";
    var id = $(this).attr('role-id');
    var route = $(this).attr('route');
    var name = $(this).attr('role-name');

    $("#delete-item-confirm").attr('d-id',id);
    $("#delete-item-confirm").attr('d-route',route);
    $("#delete-item-confirm").attr('d-type',type);
    $("#delete-item-confirm").attr('d-obj',obj);
    $("#d-item-name").html("<strong>Rol:</strong> "+name);

    $("#editRoleModal").modal("hide");
    $("#delItemModal").modal("show");
});

var global_items = [];

function setupNewItemModal(){
    if(global_items.length > 0){
        var role_options = "";
        var theme_options = "";
        var obj_options = "";

        var i = 0;
        global_items.forEach(role => {
            role_options += "<option value='"+role.id+"'>"+
                            ((role.id < 10)?"0"+role.id:role.id)+": "+
                            role.nombre+
                            "</option>";
            if(i == 0){
                var x = 0;
                role.themes.forEach(theme => {
                    theme_options += "<option value='"+theme.id+"'>"+
                                     ((theme.id < 10)?"0"+theme.id:theme.id)+": "+
                                     theme.nombre+
                                     "</option>";
                    if(x == 0){
                        theme.objectives.forEach(objective => {
                            obj_options += "<option value='"+objective.id+"'>"+
                                            ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                            objective.nombre+
                                            "</option>";
                        });
                    }
                });
            }
            i++;
        });

        if(role_options == ""){
            role_options = "<option value='0'>-- No hay Roles disponibles --</option>";
        }
        if(theme_options == ""){
            theme_options = "<option value='0'>-- No hay Temas disponibles --</option>";
        }
        if(obj_options == ""){
            obj_options = "<option value='0'>-- No hay Objetivos disponibles --</option>";
        }
        $("#role_sel").html(role_options);
        $("#theme_sel").html(theme_options);
        $("#obj_sel").html(obj_options);
    }else{
        // switch all options to create NEW ITEM
        $("#newRoleSwitch").prop("checked", true);
        $("#newThemeSwitch").prop("checked", true);
        $("#newObjSwitch").prop("checked", true);
        $("#role_sel").hide();
        $("#theme_sel").hide();
        $("#obj_sel").hide();
        $("#role_name").show();
        $("#theme_name").show();
        $("#obj_name").show();
    }
}