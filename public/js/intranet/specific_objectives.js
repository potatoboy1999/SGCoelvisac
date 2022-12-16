$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix();
});

function loadSpecificMatrix(withLoading) {
    $.ajax({
        url: matrixUrl,
        data: {
            strat_id: stratId
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
            
        }
    });
}