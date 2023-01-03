const gb1 = document.getElementById('graph-bar');
const gb2 = document.getElementById('graph-bar-acum');
const gl1 = document.getElementById('graph-line');
const gl2 = document.getElementById('graph-line-acum');

var ch_gb1, ch_gb2, ch_gl1, ch_gl2;

$(document).ready(function() {
    // loadAllPilars
    loadNowMatrix(ogFrequency, ogType, false);
    loadFutureMatrix(ogFrequency, ogType, false);
    loadGraphDataNow("bar","simple");
    loadGraphDataNow("bar","accumulated");
    loadGraphDataNow("line","simple");
    loadGraphDataNow("line","accumulated");
});

$(document).on("click", ".show-highlights", function(ev){
    var dateid = $(this).attr('dateid');
    $("#hl-label").html($(this).html());
    $("#btn-add-high").attr('kpidate', dateid);
    loadHighlights(dateid);
});

function loadHighlights(dateid) {
    $.ajax({
        url: highlightsUrl,
        data:{
            kpi_date: dateid
        },
        method: "GET",
        beforeSend: function(){
            $("#table-highlights").html('<div class="spinner-border" role="status"><span class="sr-only"></span></div>');
        },
        success: function(res){
            $("#table-highlights").html(res);
        },
        error: function(err){
            console.log("loading highlights error");
        }
    });
}

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

function loadGraphDataNow(graph, type) {
    var yOptions = {scales:{y:{beginAtZero: true}}};
    $.ajax({
        url: graphUrl,
        data:{
            id: kpi,
            type: type
        },
        method: "GET",
        success: function(res){
            if(type=="simple"){
                if(graph=="bar"){
                    ch_gb1 = new Chart(gb1, {
                        type: 'bar',
                        data: res.v1,
                        options: yOptions
                    });
                }else if(graph=="line"){
                    ch_gl1 = new Chart(gl1, {
                        type: 'line',
                        data: res.v2,
                        options: yOptions
                    });
                }
            }else if(type=="accumulated"){
                if(graph=="bar"){
                    ch_gb2 = new Chart(gb2, {
                        type: 'bar',
                        data: res.v1,
                        options: yOptions
                    });
                }else if(graph=="line"){
                    ch_gl2 = new Chart(gl2, {
                        type: 'line',
                        data: res.v1,
                        options: yOptions
                    });
                }
            }
        }
    });
}