$(document).ready(function() {
    // loadAllPilars
    $(".pilar-body").each(function(){
        var pilarId = $(this).attr("pilar");
        if(pilarId != undefined){
            loadMatrix(pilarId, "general", false);
        }
    });
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