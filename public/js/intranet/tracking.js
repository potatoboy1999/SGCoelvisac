$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).on('click','.edit-activity', function(ev){
    ev.preventDefault();
    var id = $(this).data("actid");
    $.ajax({
        url: pop_activity_route,
        method: 'GET',
        data:{
            id: id
        },
        beforeSend: function(){
            console.log('search activity');
        },
        success: function(res){
            if(res.status !== undefined){
                console.error(res.msg);
            }else{
                $("#trackingModal .modal-content").html(res);

                $("#date_from").datepicker({
                    dateFormat: "dd/mm/yy",
                    onSelect: function(date){
                        $("#date_to").datepicker('option', 'minDate', date);
                    }
                });
    
                $("#date_to").datepicker({
                    dateFormat: "dd/mm/yy",
                    beforeShow: function(){
                        var date = $("#date_from").val();
                        $("#date_to").datepicker('option', 'minDate', date);
                    }
                });

                $("#trackingModal").modal('show');
            }
        },
        error: function(er){
            console.error(er);
        }
    });
});
$(document).on('click','.close-activity', function(ev){
    ev.preventDefault();
    var id = $(this).data('actid');
    $('#confirm-close').attr('actid',id);

    $(".modal-area").hide();
    $(".modal-form").show();
    $(".btn-action").show();
    $("#trackingCloseModal").modal('show');
});

$(document).on('click','#confirm-close', function(ev){
    ev.preventDefault();
    var id = $(this).attr('actid');
    $.ajax({
        url: close_route,
        method: 'POST',
        data:{
            id: id,
            _token: $("[name='_token']").val()
        },
        beforeSend: function(){
            $(".modal-area").hide();
            $(".btn-action").hide();
            $(".modal-loading").show();
        },
        success: function(res){
            $(".modal-area").hide();
            $(".btn-cancel").show();
            if(res.status == "ok"){
                $(".modal-success").show();
                $(".act-row[data-actid='"+id+"']").remove();
            }else{
                $("#err_msg").html("No se encontró la actividad");
                $(".modal-error").show();
            }
        },
        error: function(err){
            $(".btn-cancel").show();
            $("#err_msg").html("Ocurrio un error al intentar ");
            $(".modal-error").show();
        }
    });
})

$("#pdf_report").on('click', function(ev){
    ev.preventDefault();
    $("#trackingReportModal").modal('show');
});

$("#search_from").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function(date){
        $("#search_to").datepicker('option', 'minDate', date);
    }
});

$("#search_to").datepicker({
    dateFormat: "dd/mm/yy",
    beforeShow: function(){
        var date = $("#search_from").val();
        $("#search_to").datepicker('option', 'minDate', date);
    }
});