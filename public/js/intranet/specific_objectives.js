$(document).ready(function() {
    // load Objectives
    loadSpecificMatrix(true);
    loadSummaryMatrix(true);
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