function getReunion(date){
    $.ajax({
        url: reunion_route,
        data: {
            date: date
        },
        method:'GET',
        beforeSend: function(){
            $("#calendar-wrapper").hide();
            $("#reunion-wrapper").show();
            $("#reunion-wrapper .cuerpo").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
        },
        success:function(res){
            $("#reunion-wrapper .cuerpo").html(res);
        },
    });
}

function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    $.ajax({
        url: calendar_route,
        data:{
            year: year,
            month: month,
        },
        method:'GET',
        beforeSend: function(){
            $("#reunion-wrapper").hide();
            $("#calendar-wrapper").show();
            $("#calendar-wrapper .cuerpo").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
        },
        success:function(res){
            $("#calendar-wrapper .cuerpo").html(res);
        },
    });
}

$(document).on('click',".search-calendar",function(ev){
    ev.preventDefault();
    getCalendar();
})

$(document).on('click','.day-clickable',function(ev){
    var date = $(this).data('date');
    getReunion(date);
});

$(document).on('click','.btn-view',function(ev){
    ev.preventDefault();
    var id = $(this).attr('docid');
    console.log("id:"+id);
    $.ajax({
        url: document_route,
        method: 'GET',
        data:{
            id: id,
            source: 'front'
        },
        success: function(res){
            $("#documentModal .modal-content").html(res)
            $("#documentModal").modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(thrownError);
        }
    });
});

$(document).on('click', '.btn-download', function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("docid");
    route = route+"?id="+id;
    window.location.href = route+"?id="+id;
});