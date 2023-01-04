$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix();
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

$(document).on('show.bs.dropdown','.dropdown', function() {
    var ddTrack = $(this).attr('ddTrack');
    $('body').append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position: 'absolute',
        left: $(this).offset().left,
        top: $(this).offset().top ,
        display: "block"
    }).detach());
});
$(document).on('hidden.bs.dropdown', '.dropdown',function () {
    var ddTrack = $(this).attr('ddTrack');
    $(".dropdown[ddTrack='"+ddTrack+"']").append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position:false, left:false, top:false, display: "none"
    }).detach());
});

$(document).on('click', '.btn-show-doc', function(ev){
    ev.preventDefault();
    var action_id = $(this).data('id');
    $(".modal-section").hide();
    $("#form-docs-action").show();
    loadFormDocs(action_id);
});

$(document).on("click",".btn-file-download",function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    route = route+"?id="+id;
    window.location.href = route;
});