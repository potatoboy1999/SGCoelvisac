$(document).ready(function() {
    // loadAllPilars
    loadNowMatrix(ogFrequency, ogType, false);
    loadFutureMatrix(ogFrequency, ogType, false);
});

function loadNowMatrix(frequency, type, withLoading) {
    $.ajax({
        url: nowMatrixUrl,
        data:{
            id: kpi,
            frequency: frequency,
            type: type
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                $("#matrix_now").html(
                    '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
                );
            }
        },
        success: function(res){
            $("#matrix_now").html(res);
        },
        error: function (err) {
            console.log("loading matrix now error");
        }
    });
}

function loadFutureMatrix(frequency, type,  withLoading) {
    $.ajax({
        url: futureMatrixUrl,
        data:{
            id: kpi,
            frequency: frequency,
            type: type
        },
        method: "GET",
        beforeSend: function(){
            if(withLoading){
                $("#matrix_future").html(
                    '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
                );
            }
        },
        success: function(res){
            $("#matrix_future").html(res);
        },
        error: function (err) {
            console.log("loading matrix future error");
        }
    });
}