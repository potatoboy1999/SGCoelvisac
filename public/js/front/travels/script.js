function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    console.log('calendar route:',calendar_route);
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

// $(document).on('click','.day-clickable',function(ev){
//     // check if click on cell or schedule
//     var schedule_pressed = false;
//     $(".area-travel").each(function(){
//         if($(this).is(":hover")){
//             schedule_pressed = true;
//         }
//     });

//     if(!schedule_pressed){
//         // load modal
//         var start = $(this).data('date');
//         prepareNewSchModal(null, start);
//     }

// });

$(document).on('click','.area-travel', function(ev){
    ev.preventDefault();
    var id = $(this).data('travelid');
    var start = $(this).data('date');
    prepareNewSchModal(id, start);
});

function prepareNewSchModal(id, start){
    action = 2; // SHOW
    $.ajax({
        url: pop_schedule_route,
        method: 'GET',
        data: {
            start_date: start,
            id: id,
            action: action, // SHOW or NEW
            source: 'front'
        },
        success: function(res){
            //load modal
            $("#scheduleModal .modal-content").html(res);

            //show modal
            $("#scheduleModal").modal('show');
        }
    });
}