const gb1 = document.getElementById('graph-bar');
const gb2 = document.getElementById('graph-bar-acum');
const gl1 = document.getElementById('graph-line');
const gl2 = document.getElementById('graph-line-acum');

var ch_gb1, ch_gb2, ch_gl1, ch_gl2;

$(document).ready(function() {
    // loadAllPilars
    if(kpi != ""){
        loadNowMatrix(ogFrequency, ogType, false);
        loadFutureMatrix(ogFrequency, ogType, false);
        loadGraphDataNow("bar","simple");
        loadGraphDataNow("bar","accumulated");
        loadGraphDataNow("line","simple");
        loadGraphDataNow("line","accumulated");
    }else{
        loadEmptyGraphs();
    }
});

$(document).on('change', ".input-number", function(ev){
    var val = $(this).val();
    var def = 0;
    if(val == ""){
        $(this).val(def);
    }else if(isNaN(val)){
        $(this).val(def);
    }
});

var months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$(document).on('change','#kpi_frequency', function(ev){
    console.log("Frequency: ", $(this).val());
    var freq = $(this).val();

    // change matrix now
    var table = createTable("now", freq);
    $("#matrix_now .card-body").html(table);

    // change matrix future
    table = createTable("future", freq);
    $("#matrix_future .card-body").html(table);
});

$(document).on("click", ".show-highlights", function(ev){
    var dateid = $(this).attr('dateid');
    $("#hl-label").html($(this).html());
    $("#btn-add-high").attr('kpidate', dateid);
    loadHighlights(dateid);
});

$(document).on('click', '.btn-delete', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');
    var dateid = $(this).data('date');
    var row = $(this).parent().parent();
    row.remove();
    $.ajax({
        url: rmvHighUrl,
        data:{
            id: id,
            _token: $("[name='_token']").val()
        },
        method: "POST",
        beforeSend: function(){
            //$("#table-highlights").html('<div class="spinner-border" role="status"><span class="sr-only"></span></div>');
        },
        success: function(res){
            //loadHighlights(dateid);
        },
        error: function(err){
            console.log('error removing highlight');
        }
    });
});

$(document).on('click','#btn-add-high', function(ev){
    ev.preventDefault();
    var kpidate = $(this).attr('kpidate');
    $.ajax({
        url: addHighUrl,
        data:{
            kpidate: kpidate,
            descr: $("input[name='highlight_desc']").val(),
            _token: $("[name='_token']").val()
        },
        method: "POST",
        beforeSend: function(){
            $("input[name='highlight_desc']").val('');
            $("#table-highlights").html('<div class="spinner-border" role="status"><span class="sr-only"></span></div>');
        },
        success: function(res){
            loadHighlights(kpidate);
        },
        error: function(err){
            console.log('error adding highlight');
        }

    });
})

function createTable(type, freq) {
    var table = 
        "<table class='table table-bordered m-0'>"+
            "<thead>"+
                "<tr>"+
                "<th class='text-center align-middle' width='110'>Metas</th>";
    for (let i = 1; i <= cicles[freq].count; i++) {
        table += "<th class='text-center align-middle "+(freq=="men"?'f-14':'')+"'>";
        switch(freq){
            case 'men':
                table += months[i-1];
                break;
            case 'anu':
                table += (type=="now"?cicles[freq].label_now:cicles[freq].label_future);
                break;
            default:
                table += cicles[freq].label+" "+i;
        }
        table += "</th>";
    }
    table +="</thead>";
    if(type=="now"){
        table +="<tbody>"+
                    "<tr><td class='text-center align-middle'>Real</td>";
        for (let i = 1; i <= cicles[freq].count; i++) {
            table += "<td class='text-center align-middle p-0'><input class='form-control input-number border-0 text-center' type='number' step='0.0001' min='0' max='9999999999' name='real_cicle[]' value='0'></td>";
        }
        table += "</tr>"+
                    "<tr><td class='text-center align-middle'>Planificado</td>";
        for (let i = 1; i <= cicles[freq].count; i++) {
            table += "<td class='text-center align-middle p-0'><input class='form-control input-number border-0 text-center' type='number' step='0.0001' min='0' max='9999999999' name='plan_cicle[]' value='0'></td>";
        }
        table +="</tbody>";
    }else{
        table += 
        "<tbody>"+
            "<tr><td class='text-center align-middle'>Real</td>";
        for (let i = 1; i <= cicles[freq].count; i++) {
            table += "<td class='text-center align-middle p-0'>"+
            "<input type='hidden' name='plan_pastcicle[]' value='0'>"+
            "<input class='form-control input-number border-0 text-center' type='number' step='0.0001' min='0' max='9999999999' name='real_pastcicle[]' value='0'>"+
            "</td>";
        }
        table += 
            "</tr>"+
        "</tbody>";
    }

    table += 
        "</table>";
    return table;
}

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
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }else if(graph=="line"){
                    ch_gl1 = new Chart(gl1, {
                        type: 'line',
                        data: res.v2,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }else if(type=="accumulated"){
                if(graph=="bar"){
                    ch_gb2 = new Chart(gb2, {
                        type: 'bar',
                        data: res.v1,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }else if(graph=="line"){
                    ch_gl2 = new Chart(gl2, {
                        type: 'line',
                        data: res.v1,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        }
    });
}

function loadEmptyGraphs() {
    var data = {
        labels: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        datasets: [
            {"label":"Monto Real","data":[0,0,0,0,0,0,0,0,0,0,0,0],"borderWidth":1},
            {"label":"Monto Planificado","data":[0,0,0,0,0,0,0,0,0,0,0,0],"borderWidth":1}
        ]
    };
    ch_gb1 = new Chart(gb1, {
        type: 'bar',
        data: data,
        options: {scales: {y: {beginAtZero: true}}}
    });
    ch_gb2 = new Chart(gb2, {
        type: 'bar',
        data: data,
        options: {scales: {y: {beginAtZero: true}}}
    });
    ch_gl1 = new Chart(gl1, {
        type: 'line',
        data: data,
        options: {scales: {y: {beginAtZero: true}}}
    });
    ch_gl2 = new Chart(gl2, {
        type: 'line',
        data: data,
        options: {scales: {y: {beginAtZero: true}}}
    });
}