$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix();
    loadFormActions();
});

function loadSpecificMatrix(withLoading) {
    $.ajax({
        url: matrixUrl,
        data: {
            strat_id: objId
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                addSpinner("#matrix_content");
            }
        },
        success: function(res){
            $("#matrix_content").html(res);
        },
        error: function (err) {
            
        }
    });
}

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

function loadFormActions(){
    $.ajax({
        url: newFormUrl,
        data:{
            obj_id: objId
        },
        method: "GET",
        beforeSend: function(){
            addSpinner("#form-action");
        },
        success: function(res){
            $("#form-action").html(res);
            
            $("#act_new_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(date){
                    $("#act_new_date_end").datepicker('option', 'minDate', date);
                }
            });

            $("#act_new_date_end").datepicker({
                dateFormat: "dd/mm/yy",
            });
        },
        error: function(err){
            console.error("error loading new form");
        }
    });
}

function loadEditAction(obj){
    $.ajax({
        url: editFormUrl,
        data: {
            id: obj,
        },
        method: "GET",
        beforeSend: function(){
            addSpinner("#form-edit-action");
        },
        success: function(res){
            $("#form-edit-action").html(res);

            $("#act_edit_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(date){
                    $("#act_edit_date_end").datepicker('option', 'minDate', date);
                }
            });
            
            $("#act_edit_date_end").datepicker({
                dateFormat: "dd/mm/yy",
            });
        },
        error: function(err){
            console.error("error loading new form");
        }
    });
}

function addSpinner(target){
    $(target).html(
        '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
    );
}

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

$(document).on('click', '.btn-new-action', function(ev){
    $(".modal-section").hide();
    $("#form-action").show();
});

$(document).on('click','.edit-action',function(ev){
    ev.preventDefault();
    var action = $(this).attr("action");
    $(".modal-section").hide();
    $("#form-edit-action").show();
    loadEditAction(action);
});

$(document).on('submit',"#form-newAction", function(ev){
    ev.preventDefault();
    var data = $("#form-newAction").serialize();
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-newAction input[name='name']").val('');
            $("#form-newAction input[name='hito']").val('');
            // show loading
            $(".modal-section").hide();
            $("#form-new-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                $("#actionModal").modal("hide");
                loadSpecificMatrix(true);
            }else{
                console.error("error creating action");
                console.error("msg:",res.msg);
            }
        },
        error: function(err){
            console.error("error creating action");
        }
    });
});

$(document).on('submit',"#form-editAction", function(ev){
    ev.preventDefault();
    var data = $("#form-editAction").serialize();
    var pilar = $(this).attr("pilar");
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-editAction input[name='name']").val('');
            $("#form-editAction input[name='hito']").val('');
            // show loading
            $(".modal-section").hide();
            $("#form-edit-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                $("#actionEditModal").modal("hide");
                loadSpecificMatrix(true);
            }else{
                console.error("error updating action");
                console.error("msg:",res.msg);
            }
        },
        error: function(err){
            console.error("error updating action");
        }
    });
});

$(document).on('click','.dlt-action',function(ev){
    ev.preventDefault();
    var action = $(this).attr('action');
    var action_name = $(".action-"+action+" .action-name").html();
    $("#f-form-delete input[name='id']").val(action);
    $("#action_dlt_name").html(action_name);

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
                $("#deleteActionModal").modal("hide");
                loadSpecificMatrix(true);
                // TO DO: find row & remove [LATER VERSION]
            }else{
                console.error('error deleting action: '+res.msg);
            }
        },
        error: function(err){
            console.error('error deleting action');
        }
    });
});