
function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    $.ajax({
        url: calendar_route,
        data: {
            year: year
        },
        method:'GET',
        beforeSend: function(){
            $("#calendar-card .card-body").html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
        },
        success:function(res){
            $("#calendar-card .card-body").html(res);
        },
    });
}

$("#search-year").on('click',function(ev){
    ev.preventDefault();
    getCalendar();
})