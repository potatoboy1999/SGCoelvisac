$(document).ready(function() {
    // loadAllPilars
    $(".pilar-body").each(function(){
        var pilarId = $(this).attr("pilar");
        if(pilarId != undefined){
            loadMatrix(pilarId, "general", false);
        }
    });
    loadFormObjectives();
});

function loadMatrix(pilarId, view, withLoading) {
    $.ajax({
        url: matrixUrl,
        data: {
            pilar_id: pilarId,
            view: view
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                addSpinner(".pilar-body.pilar-"+pilarId);
            }
        },
        success: function(res){
            $(".pilar-body.pilar-"+pilarId).html(res);
        },
        error: function (err) {
            
        }
    });
}

function loadFormObjectives(){
    $.ajax({
        url: newFormUrl,
        method: "GET",
        beforeSend: function(){
            addSpinner("#form-objectives");
        },
        success: function(res){
            $("#form-objectives").html(res);
        },
        error: function(err){
            console.error("error loading new form");
        }
    });
}

function loadEditObj(obj){
    $.ajax({
        url: editFormUrl,
        data: {
            id: obj,
        },
        method: "GET",
        beforeSend: function(){
            addSpinner("#form-edit-objectives");
        },
        success: function(res){
            $("#form-edit-objectives").html(res);
        },
        error: function(err){
            console.error("error loading new form");
        }
    });
}

function loadKpiRedirect() {
    $.ajax({
        url: redirectKpiUrl,
        method: 'GET',
        beforeSend: function(){
            addSpinner("#form-kpi");
        },
        success: function(res){
            $("#form-kpi").html(res);
        },
        error: function(err){
            console.error("error loading kpi redirect form");
        }
    });
}

function loadComments(obj_id, withLoading){
    $.ajax({
        url: listComment,
        data: {
            obj_id: obj_id
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                addSpinner("#comments_list");
            }
        },
        success: function(res){
            $("#comments_list").html(res);
        },
        error: function (err) {
            
        }
    });
}

function addSpinner(target){
    $(target).html(
        '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
    );
}

$(document).on('click','.switch-view',function(ev){
    var view = $(this).attr("view");
    var pilar = $(this).attr("pilar");

    switch (view) {
        case 'general':
            view = 'complete';
            break;
        case 'complete':
            view = 'general';
            break;
        default:
            view = 'general';
            break;
    }
    $(this).attr("view", view);

    loadMatrix(pilar, view, true);
});

$(document).on('show.coreui.dropdown','.dropdown', function() {
    var ddTrack = $(this).attr('ddTrack');
    $('body').append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position: 'absolute',
        left: $(this).offset().left,
        top: $(this).offset().top ,
        display: "block"
    }).detach());
});
$(document).on('hidden.coreui.dropdown', '.dropdown',function () {
    var ddTrack = $(this).attr('ddTrack');
    $(".dropdown[ddTrack='"+ddTrack+"']").append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position:false, left:false, top:false, display: "none"
    }).detach());
});

$(document).on('change','#pilar_select', function(ev){
    var pilar_id = $(this).val();
    var pilar = obj_form_data['pilars'].find((pilar)=>pilar.id == pilar_id);
    var dimensions = pilar.dimensions;
    var html = "";
    for (let i = 0; i < dimensions.length; i++) {
        const dimension = dimensions[i];
        html += '<option value="'+dimension.id+'">'+dimension.nombre+'</option>';
    }
    $("#dimension_select").html(html);
});

$(document).on('change','#sponsor_select', function(ev){
    var area_id = $(this).val();
    var area = obj_form_data['areas'].find((area)=>area.id == area_id);
    var roles = area.roles;
    var users = area.users;
    var roleHtml = "";
    roleHtml += '<option value="">-- No aplica --</option>';
    for (let i = 0; i < roles.length; i++) {
        const role = roles[i];
        roleHtml += '<option value="'+role.id+'">'+role.nombres+'</option>';
    }
    $("#rol_select").html(roleHtml);

    var userHtml = "";
    for (let i = 0; i < users.length; i++) {
        const user = users[i];
        userHtml += '<li>'+
                    '<input class="form-check-input" id="user'+user.id+'" name="users[]" value="'+user.id+'" type="checkbox" data-object="role"> '+
                    '<label class="form-check-label" for="user'+user.id+'">'+user.nombre+'</label>'+
                '</li>';
    }
    $("#users_list").html(userHtml);
});

$(document).on('change','#sponsor_edit_select', function(ev){
    var area_id = $(this).val();
    var area = obj_form_data['areas'].find((area)=>area.id == area_id);
    var roles = area.roles;
    var users = area.users;
    var roleHtml = "";
    roleHtml += '<option value="">-- No aplica --</option>';
    for (let i = 0; i < roles.length; i++) {
        const role = roles[i];
        roleHtml += '<option value="'+role.id+'">'+role.nombres+'</option>';
    }
    $("#rol_edit_select").html(roleHtml);

    var userHtml = "";
    for (let i = 0; i < users.length; i++) {
        const user = users[i];
        userHtml += '<li>'+
                    '<input class="form-check-input" id="user'+user.id+'" name="users[]" value="'+user.id+'" type="checkbox" data-object="role"> '+
                    '<label class="form-check-label" for="user'+user.id+'">'+user.nombre+'</label>'+
                '</li>';
    }
    $("#users_edit_list").html(userHtml);
});

$(document).on('click', '.btn-new-obj', function(ev){
    $(".modal-section").hide();
    $("#form-objectives").show();
});

$(document).on('click', '.btn-new-kpi', function(ev){
    loadKpiRedirect();
});

$(document).on('click','.edit-obj',function(ev){
    ev.preventDefault();
    var obj = $(this).attr("obj");
    $(".modal-section").hide();
    $("#form-edit-objectives").show();
    loadEditObj(obj);
});

$(document).on('submit',"#form-newObjective", function(ev){
    ev.preventDefault();
    var data = $("#form-newObjective").serialize();
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-newObjective input[name='nombre']").val('');
            $("#users_list .form-check-input").prop('checked',false);
            // show loading
            $(".modal-section").hide();
            $("#form-new-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                $("#redirect-kpi").attr("obj", res.obj);
                $("#objectiveModal").modal("hide");
                $("#redirectKpiModal").modal("show");
            }else{
                console.error("error saving objective");
                console.error("msg:",res.msg);
            }
        },
        error: function(err){
            console.error("error saving objective");
        }
    });
});

$(document).on('submit',"#form-editObjective", function(ev){
    ev.preventDefault();
    var data = $("#form-editObjective").serialize();
    var pilar = $(this).attr("pilar");
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-editObjective input[name='nombre']").val('');
            $("#users_edit_list .form-check-input").prop('checked',false);
            // show loading
            $(".modal-section").hide();
            $("#form-edit-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                $("#objectiveEditModal").modal("hide");
                var view = $(".switch-view[pilar='"+pilar+"']").attr('view');
                loadMatrix(pilar, view, true);
            }else{
                console.error("error saving objective");
                console.error("msg:",res.msg);
            }
        },
        error: function(err){
            console.error("error saving objective");
        }
    });
});

$(document).on('click','#redirect-kpi',function(ev){
    ev.preventDefault();
    var href = $(this).attr('href');
    var obj = $(this).attr('obj');
    location.href = href+"?obj="+obj;
})

$(document).on('click','#kpi_redirect_btn',function(ev){
    ev.preventDefault();
    var href = $(this).attr('href');
    var obj = $("#new_kpi_strat").val();
    location.href = href+"?obj="+obj;
})
$(document).on('click','.dlt-kpi',function(ev){
    var kpi = $(this).attr('kpi');
    var kpi_name = $(".kpi-"+kpi+" .kpi-name").html();
    $("#f-form-delete input[name='kpi_id']").val(kpi);
    $("#kpi_dlt_name").html(kpi_name);

    $(".modal-section").hide();
    $("#form-delete").show();
});
$(document).on('submit','#f-form-delete', function(ev){
    ev.preventDefault();
    var url = $(this).attr("action");
    $.ajax({
        url: url,
        data: $(this).serialize(),
        method: 'POST',
        beforeSend: function(){
            // show loading
            $(".modal-section").hide();
            $("#form-delete-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                $("#deleteKpiModal").modal("hide");
                var row = $("tr.kpi-"+res.kpi);
                var pilar = row.parent().parent().attr("pilar");
                var view = $(".switch-view[pilar='"+pilar+"']").attr('view');
                loadMatrix(pilar,view,true);

                // TO DO [LATER VERSION]
                /*
                    // find row & remove
                    row.remove();
                    // update rowspans
                    var d = $("tr.kpi-"+k).attr('dim');
                    var o = $("tr.kpi-"+k).attr('strat');
                */
            }else{
                console.error('error deleting kpi: '+res.msg);
            }
        },
        error: function(err){
            console.error('error deleting kpi');
        }
    });
});

$(document).on('click','.btn-comments', function(ev){
    ev.preventDefault();
    var obj = $(this).data("obj");
    $("#comments_form [name='objective_id']").val(obj);
    loadComments(obj, true);
});

$(document).on('submit','#comments_form', function(ev){
    ev.preventDefault();
    console.log("submit new comment");
    var obj = $("#comments_form [name='objective_id']").val();
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        method: "POST",
        beforeSend: function(){
            $("#comm_desc").val("");
            addSpinner("#comments_list");
        },
        success: function(res){
            if(res.status = "ok"){
                loadComments(obj, true);
            }else{
                alert(res.msg);
            }
        },
        error: function(err){
            loadComments(obj, true);
        }
    });
});

$(document).on("click",".comm-delete", function(ev){
    ev.preventDefault();
    var id = $(this).attr("commid");
    var url = $(this).attr("href");
    var obj = $("#comments_form [name='objective_id']").val();
    $.ajax({
        url: url,
        data: {
            _token: $("[name=_token]").val(),
            id: id
        },
        method: "POST",
        beforeSend: function(){
            addSpinner("#comments_list");
        },
        success: function(res){
            if(res.status = "ok"){
                loadComments(obj, true);
            }else{
                alert(res.msg);
            }
        },
        error: function(err){
            loadComments(obj, true);
        }
    });
});