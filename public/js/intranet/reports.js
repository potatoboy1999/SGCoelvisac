$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$(".new_activity").on('click', function(ev){
    ev.preventDefault();
    var target = $(this).data('target');
    var type = $(this).data('type');
    var schedule = $(this).attr('travelid');
    $.ajax({
        url: activity_modal,
        method: 'GET',
        data:{
            schedule_id: schedule,
            type: type
        },
        beforeSend: function(){

        },
        success: function(res){
            $('#modalActivity .modal-content').html(res);

            //load scripts
            $("#act_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onselect: function(date){
                    $("#act_date_end").datepicker('option', 'minDate', date);
                }
            });

            $("#act_date_end").datepicker({
                dateFormat: "dd/mm/yy",
                beforeShow: function(){
                    var date = $("#act_date_start").val();
                    $("#act_date_end").datepicker('option', 'minDate', date);
                }
            });

            $('#modalActivity').modal('show');
        }
    });
    
});

$(".activity-table").on('click', '.btn-edit', function(ev){
    ev.preventDefault();

});

$(".activity-table").on('click', '.btn-delete', function(ev){
    ev.preventDefault();
    $("#deleteActivity").modal("show");
});