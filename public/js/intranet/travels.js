$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

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

$(document).on('click','.week-clickable',function(ev){
    var branch = $(this).parent().data('branchname');
    var branch_id = $(this).parent().data('branchid');
    var start = $(this).data('start');
    var end = $(this).data('end');
    prepareNewSchModal(branch, branch_id, start, end);
    $("#newScheduleModal").modal('show');
});

$("#sch_date_start").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function(date){
        $("#sch_date_end").datepicker('option', 'minDate', date);
    }
});
$("#sch_date_end").datepicker({
    dateFormat: "dd/mm/yy",
});

function prepareNewSchModal(branch, id, start, end){
    $("#sch_branch_name").val(branch);
    $("#form_schedule input[name='branch']").val(id);

    var dStart = new Date(start+' 00:00:00');
    var dEnd = new Date(end+' 00:00:00');
    dEnd.setDate(dEnd.getDate() - 1);

    $("#sch_date_start").val(formatDate(dStart));
    $("#sch_date_end").val(formatDate(dEnd));
    $("#sch_date_start").datepicker('option', 'minDate', dStart);
    $("#sch_date_start").datepicker('option', 'maxDate', dEnd);
}

function formatDate(d){
    return d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
}