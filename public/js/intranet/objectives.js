$(document).ready(function() {
    // loadAllPilars
    $(".pilar-body").each(function(){
        var pilarId = $(this).attr("pilar");
        if(pilarId != undefined){
            loadMatrix(pilarId, false);
        }
    });
});

function loadMatrix(pilarId, withLoading) {
    $.ajax({
        url: matrixUrl,
        data: {
            pilar_id: pilarId
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                $(".pilar-body.pilar-"+pilarId).html(
                    '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
                );
            }
        },
        success: function(res){
            $(".pilar-body.pilar-"+pilarId).html(res);
        },
        error: function (err) {
            
        }
    });
}