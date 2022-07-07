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
    // check if click on cell or schedule
    var schedule_pressed = false;
    $(".area-travel").each(function(){
        if($(this).is(":hover")){
            schedule_pressed = true;
        }
    });

    if(!schedule_pressed){
        // load modal
        var start = $(this).data('date');
        prepareNewSchModal(null, start);
    }

});

$(document).on('click','.area-travel', function(ev){
    ev.preventDefault();
    var id = $(this).data('travelid');
    var start = $(this).data('date');
    prepareNewSchModal(id, start);
});

$(document).on('click',".add-act", function(ev){
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

$(document).on('submit', "#form_schedule", function(ev){
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

function prepareNewSchModal(id, start){
    var action = 1; // NEW
    if(id != null){
        action = 2; // SHOW
    }
    $.ajax({
        url: pop_schedule_route,
        method: 'GET',
        data: {
            start_date: start,
            id: id,
            action: action, // SHOW or NEW
        },
        success: function(res){
            //load modal
            $("#newScheduleModal .modal-content").html(res);

            //load scripts
            $("#sch_date_end").datepicker({
                dateFormat: "dd/mm/yy",
                beforeShow: function(){
                    var date = $("#sch_date_start").val();
                    $("#sch_date_end").datepicker('option', 'minDate', date);
                }
            });

            //show modal
            $("#newScheduleModal").modal('show');
        }
    });
}

function formatDate(d){
    return d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
}