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

function loadFormDocs(action_id) {
    $.ajax({
        url: docsFormUrl,
        method: 'GET',
        data:{
            id: action_id,
        },
        beforeSend: function(){
            // show loading
            addSpinner("#form-docs-action");
        },
        success:function(res){
            $("#form-docs-action").html(res);

            // change table btn if no docs remaining
            var btn = $("a.btn-show-doc[data-id="+action_id+"]");
            var icon = btn.find("use");
            var qnty = $(".doc-item").length;
            if(qnty == 0){
                if(btn.hasClass("btn-success")){
                    btn.removeClass("btn-success");
                    btn.addClass("btn-secondary");
                }
                var href = icon.attr("xlink:href");
                href = href.replace("cil-file","cil-arrow-thick-from-bottom");
                icon.attr("xlink:href", href); 
            }
        },
        error: function(err){
            console.error("error loading docs");
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

$(document).on('click', '.btn-show-doc', function(ev){
    ev.preventDefault();
    var action_id = $(this).data('id');
    $(".modal-section").hide();
    $("#form-docs-action").show();
    loadFormDocs(action_id);
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

$(document).on("change","[name='a_file']",function(ev){
    $("[name='a_edit']").val("true");
});

$(document).on("submit", "#docs-form", function(ev){
    ev.preventDefault();
    var form = $("#docs-form");
    var data = new FormData(form[0]);
    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $(".modal-section").hide();
            $("#form-docs-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                var action_id = $("#docs-form [name='action_id']").val();
                $(".modal-section").hide();
                $("#form-docs-action").show();
                loadFormDocs(action_id);
                
                // change table btn
                var btn = $("a.btn-show-doc[data-id="+action_id+"]");
                var icon = btn.find("use");
                if(btn.hasClass("btn-secondary")){
                    btn.removeClass("btn-secondary");
                    btn.addClass("btn-success");
                }
                var href = icon.attr("xlink:href");
                href = href.replace("cil-arrow-thick-from-bottom","cil-file");
                icon.attr("xlink:href", href); 

            }else{
                $("#a_error").html(res.msg);
                console.error("error uploading document: "+res.msg);
            }
        },
        error: function(err){
            console.error("error uploading document");
        }
    });
});

$(document).on("click",".btn-file-download",function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    route = route+"?id="+id;
    window.location.href = route;
});

$(document).on("click",".btn-file-delete",function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    $.ajax({
        url: route,
        data: {
            id: id,
            _token: $("[name=_token]").val(),
        },
        method: "POST",
        beforeSend: function(){
            $(".modal-section").hide();
            $("#form-docs-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                var action_id = $("#docs-form [name='action_id']").val();
                $(".modal-section").hide();
                $("#form-docs-action").show();
                loadFormDocs(action_id);
            }else{
                // $("#a_error").html(res.msg);
                console.error("error deleting document: "+res.msg);
            }
        },
        error: function(err){
            console.error("error deleting document");
        }
    });
});