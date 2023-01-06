$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix("general",true);
});

function loadSpecificMatrix(view, withLoading) {
    $.ajax({
        url: matrixUrl,
        method: "GET",
        data:{
            view: view
        },
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
    // addComment(comment);
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