function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    $.ajax({
        url: calendar_route,
        data: {
            year: year,
            month: month,
        },
        method:'GET',
        beforeSend: function(){
            $("#calendar-wrapper .cuerpo").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
        },
        success:function(res){
            $("#calendar-wrapper .cuerpo").html(res);
        },
    });
}

$(".search-calendar").on('click',function(ev){
    ev.preventDefault();
    getCalendar();
})

$(document).on('click', '.btn-download', function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("docid");
    route = route+"?id="+id;
    window.location.href = route+"?id="+id;
});

$(document).on('click','.area-reunion', function(ev){
    ev.preventDefault();
    var id = $(this).data('reuid');
    prepareModal(id);
});

function prepareModal(id){
    $.ajax({
        url: show_popup,
        method: 'GET',
        data:{
            id: id,
            source: 'front'
        },
        beforeSend: function(){
            console.log('getting modal');
        },
        success: function(res){
            $("#reunionModal .modal-content").html(res);
            $("#reunionModal").modal('show');
        },
        onerror: function(err){
            alert('Error: '+err);
        }
    });
}