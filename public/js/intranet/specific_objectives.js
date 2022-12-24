$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix("general",true);
    loadSummaryMatrix(true);
    loadFormObjectives();
});

function loadSpecificMatrix(view, withLoading) {
    $.ajax({
        url: matrixUrl,
        data: {
            strat_id: stratId,
            view: view
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                $("#matrix_content").html(
                    '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
                );
            }
        },
        success: function(res){
            $("#matrix_content").html(res);
        },
        error: function (err) {
            console.log("error ajax loading specific matrix");
        }
    });
}

function loadSummaryMatrix(withLoading){
    $.ajax({
        url: summMatrixUrl,
        data: {
            strat_id: stratId
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                $("#matrix_summary").html(
                    '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
                );
            }
        },
        success: function(res){
            $("#matrix_summary").html(res);
        },
        error: function (err) {
            console.log("error ajax loading summary matrix");
        }
    });
}

function loadFormObjectives(){
    $.ajax({
        url: newFormUrl,
        data:{
            obj_strat: stratId,
        },
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
        data: {
            strat_id: stratId,
        },
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

function addSpinner(target){
    $(target).html(
        '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
    );
}

$(document).on('click','.switch-view',function(ev){
    var view = $(this).attr("view");

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

    loadSpecificMatrix(view, true);
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

$(document).on('change','#sponsor_select', function(ev){
    var area_id = $(this).val();
    var area = obj_form_data['areas'].find((area)=>area.id == area_id);
    var roles = area.roles;
    var users = area.users;
    var roleHtml = "";
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
                var view = $(".switch-view").attr('view');
                loadSpecificMatrix(view,true);
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
                var view = $(".switch-view").attr('view');
                loadSpecificMatrix(view,true);

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