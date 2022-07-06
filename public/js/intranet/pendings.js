$('.show-schedule').on('click', function(ev){
    ev.preventDefault();
    var id = $(this).data('travelid');
    var action = $(this).data('action');
    $.ajax({
        url: pop_schedule_route,
        method: 'GET',
        data: {
            id: id,
            action: action,
        },
        success: function(res){
            //load & show modal
            $("#scheduleModal .modal-content").html(res);
            $("#scheduleModal").modal('show');
        }
    });
});