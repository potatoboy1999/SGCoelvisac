$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix("general",true);
    loadSummaryMatrix(true);
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
                addSpinner("#matrix_content");
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
                addSpinner("#matrix_summary");
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