$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    var cal_type = $("#form-area-sel select[name='cal_type']").val();
    $.ajax({
        url: calendar_route,
        data: {
            year: year,
            month: month,
            cal_type: cal_type
        },
        method:'GET',
        beforeSend: function(){
            $("#calendar-card .card-body").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
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
    $(".area-reunion").each(function(){
        if($(this).is(":hover")){
            schedule_pressed = true;
        }
    });

    if(!schedule_pressed){
        // load modal
        var date = $(this).data('date');
        redirectToCreate(date);
    }

});

$(document).on('click','.area-reunion', function(ev){
    ev.preventDefault();
    var id = $(this).data('reuid');
    prepareModal(id);
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
            $("#newReunionModal .form-btns").hide();
            $("#newReunionModal .modal-form").hide();
            $("#newReunionModal .modal-loading").show();
        },
        success: function(res){
            console.log(res);
            if(res.status == "ok"){
                $("#newReunionModal .form-btns").show();
                $("#newReunionModal .modal-loading").hide();
                $("#newReunionModal .modal-success").show();
                $("#newReunionModal input[type='submit']").hide();
            }else{
                alert("ERROR: No se pudieron guardar los datos");
            }
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

$('.btn-show').on('click', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');
    prepareModal(id);
});

$('.btn-remove').on('click', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');

    $("input[name='reunion_id']").val(id);

    $("#deleteReunionModal .modal-area").hide();
    $("#deleteReunionModal .modal-form").show();
    $("#deleteReunionModal .btn-actions").show();
    $("#deleteReunionModal").modal("show");
});

$("#form_delete").on('submit', function(ev){
    ev.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: "POST",
        data: $(this).serialize(),
        beforeSend: function(){
            $("#deleteReunionModal .modal-area").hide();
            $("#deleteReunionModal .modal-loading").show();
            $("#deleteReunionModal .btn-actions").hide();
        },
        success: function(res){
            $("#deleteReunionModal .modal-area").hide();
            if(res.status == 'ok'){
                $("#deleteReunionModal .modal-success").show();
            }else{
                $("#error_msg").html(res.msg);
                $("#deleteReunionModal .modal-error").show();
            }

            // delete row
            $(".row-reunion[reunionid='"+$("input[name='reunion_id']").val()+"']").remove();
        }
    });
});

function redirectToCreate(date){
    var url = result_create;
    location.href = url+"?date="+encodeURI(date);
}
function prepareModal(id){
    $.ajax({
        url: show_popup,
        method: 'GET',
        data:{
            id: id,
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

function formatDate(d){
    return d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
}