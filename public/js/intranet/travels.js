$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    $.ajax({
        url: calendar_route,
        data: {
            year: year,
            month: month
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

$(".search-calendar").on('click',function(ev){
    ev.preventDefault();
    getCalendar();
})

$(document).on('click','.day-clickable',function(ev){
    var start = $(this).data('start');
    prepareNewSchModal(null, null, start, null);
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
    beforeShow: function(){
        var date = $("#sch_date_start").val();
        $("#sch_date_end").datepicker('option', 'minDate', date);
    }
});

$(".add-act").on('click', function(ev){
    ev.preventDefault();
    var type = $(this).attr('type');
    var areas = $(this).parent().parent().find('.act_areas');
    var counter = parseInt(areas.attr('count')) + 1;
    if(counter <= 7){
        areas.attr('count', counter);
        if(type == "area"){
            areas.append(newActivity('area_act',type));
        }else{
            areas.append(newActivity('non_area_act', type));
        }
    }
    if (counter == 7) {
        $(".add-act[type='"+type+"']").hide();
    }
});
$(document).on('click', '.btn-del-act', function(ev){
    ev.preventDefault();
    var type = $(this).attr('type');
    var areas = $(this).parent().parent().parent().parent();
    var counter = parseInt(areas.attr('count')) - 1;
    areas.attr('count', counter);
    $(this).parent().parent().parent().remove();
    if (counter < 7) {
        $(".add-act[type='"+type+"']").show();
    }
});

$("#form_schedule").on('submit', function(ev){
    ev.preventDefault();
    
    $.ajax({
        url: $("#form_schedule").attr('action'),
        method: 'POST',
        data: $("#form_schedule").serialize(),
        beforeSend: function(){
            $("#newScheduleModal .form-btns").hide();
            $("#newScheduleModal .modal-form").hide();
            $("#newScheduleModal .modal-loading").show();
        },
        success: function(res){
            console.log(res);
            if(res.status == "ok"){
                $("#newScheduleModal .form-btns").show();
                $("#newScheduleModal .modal-loading").hide();
                $("#newScheduleModal .modal-success").show();
                $("#newScheduleModal input[type='submit']").hide();
            }else{
                alert("ERROR: No se pudieron guardar los datos");
            }

        }
    });
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

    $("#area_act").html('');
    $("#non_area_act").html('');
    $("add-act[type='area']").hide();
    $("add-act[type='non_area']").hide();
    $("#vehicle_check").prop('checked', false);
    $("#hab_check").prop('checked', false);
    $("#extras_check").prop('checked', false);

    $("#newScheduleModal .modal-form").show();
    $("#newScheduleModal .modal-loading").hide();
    $("#newScheduleModal .modal-success").hide();

    $("#newScheduleModal .form-btns").show();
    $("#newScheduleModal input[type='submit']").show();
}

function formatDate(d){
    return d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
}

function newActivity(name, type){
    var html = '<div class="mb-2 act-ta">'+
                    '<div style="display: flex;">'+
                        '<div style="flex: 0 40px;">'+
                            '<a class="btn btn-danger btn-sm btn-del-act" type="'+type+'">'+
                                '<svg class="icon">'+
                                    '<use xlink:href="http://localhost:8000/icons/sprites/free.svg#cil-minus"></use>'+
                                '</svg>'+
                            '</a>'+
                        '</div>'+
                            '<div style="flex: 1;">'+
                            '<textarea name="'+name+'[]" rows="2" class="form-control" required></textarea>'+
                        '</div>'+
                    '</div>'+
                '</div>';
    return html;
}