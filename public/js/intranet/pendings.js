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

$(document).on('click', ".travel-confirm", function(ev){
    ev.preventDefault();
    var id = $(this).data('travelid');
    var confirmation = $(this).data('confirmation');
    var form = $("#form_schedule").serialize()+"&id="+id+"&confirmation="+confirmation;
    console.log('form: ', form);
    $.ajax({
        url: confirm_route,
        method: 'POST',
        data: form,
        beforeSend: function(){
            $("#scheduleModal .form-btns").hide();
            $("#scheduleModal .modal-form").hide();
            $("#scheduleModal .modal-loading").show();
        },
        success: function(res){
            console.log(res);
            if(res.status == "ok"){
                $("#scheduleModal .form-btns").show();
                $("#scheduleModal .modal-loading").hide();
                $("#scheduleModal .modal-success").show();
                $("#scheduleModal .btn-actions").hide();
                // destroy row
                $("tr[data-travelid='"+id+"']").remove();
                $("#scheduleModal .modal-success p").html('<span class="text-success"><strong>!ÉXITO!</strong></span><br> La agenda fue validada');
            }else{
                alert("ERROR: No se pudieron guardar los datos");
            }
        }
    });
});

$(document).on('click', ".travel-deny", function(ev){
    ev.preventDefault();
    var id = $(this).data('travelid');
    var confirmation = $(this).data('confirmation');
    $.ajax({
        url: deny_route,
        method: 'POST',
        data: {
            id: id,
            confirmation: confirmation,
            _token: $("input[name='_token']").val()
        },
        beforeSend: function(){
            $("#scheduleModal .form-btns").hide();
            $("#scheduleModal .modal-form").hide();
            $("#scheduleModal .modal-loading").show();
        },
        success: function(res){
            console.log(res);
            if(res.status == "ok"){
                $("#scheduleModal .form-btns").show();
                $("#scheduleModal .modal-loading").hide();
                $("#scheduleModal .modal-success").show();
                $("#scheduleModal .btn-actions").hide();
                // destroy row
                $("tr[data-travelid='"+id+"']").remove();
                $("#scheduleModal .modal-success p").html('<span class="text-success"><strong>!ÉXITO!</strong></span><br> La agenda fue rechazada');
            }else{
                alert("ERROR: No se pudieron guardar los datos");
            }
        }
    });
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
    var act_id = $(this).data('act');
    $("#deleted_act").append("<input type='hidden' name='deleted_act[]' value='"+act_id+"'>");
});