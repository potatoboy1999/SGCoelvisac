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

$("#filter").on('click', function(ev){
    ev.preventDefault();
    $("#trackingFilterModal").modal('show');
});

$("#pdf_report").on('click', function(ev){
    ev.preventDefault();
    $.ajax({
        url: report_form_route,
        method: 'GET',
        success: function(res){
            $("#trackingReportModal .modal-content").html(res);

            // load scripts

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

            $("#user_name").autocomplete({
                minLength: 0,
                source: usernames_route,
                select: function(ev, ui){
                    ev.preventDefault();
                    //console.log(ui);
                    if($(".user"+ui.item.userid).length == 0){
                        var html = '<div class="user-item user'+ui.item.userid+'" userid="'+ui.item.userid+'">'+ui.item.label+'</div>'+
                                '<input class="user'+ui.item.userid+'" type="hidden" name="users[]" value="'+ui.item.userid+'">';
                        $("#users").append(html);
                        $("#users").show();
                    }
                    $("#user_name").val("");
                }
            });

            $("#trackingReportModal").modal('show');
        },
        error: function(err){
            alert("Ocurrió un error");
        }
    });
});

$("#filter_from").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function(date){
        $("#filter_to").datepicker('option', 'minDate', date);
    }
});

$("#filter_to").datepicker({
    dateFormat: "dd/mm/yy",
    beforeShow: function(){
        var date = $("#filter_from").val();
        $("#filter_to").datepicker('option', 'minDate', date);
    }
});

$(document).on('click','.user-item', function(ev){
    var userid = $(this).attr('userid');
    $('.user'+userid).remove();
});

$(document).on('click',".form_submit",function(ev){
    ev.preventDefault();
    var form = $(this).attr('form');
    var valid = formValidation(form);

    if(valid){
        $("#"+form).submit();
    }
});

function formValidation(form) {
    var valid = true;

    // check branch checkboxes
    var branch_valid = false;
    $("#"+form+" input[name='branches[]']").each(function(){
        if($(this).is(':checked')){
            branch_valid = true;
        }
    });
    if(!branch_valid){
        valid = false;
        $("#"+form+" .branch_error").show();
    }else{
        $("#"+form+" .branch_error").hide();
    }

    // check area checkboxes
    var areas_valid = false;
    var count = $("#"+form+" input[name='areas[]']").length;
    if(count == 0){
        areas_valid = true;
    }else{
        $("#"+form+" input[name='areas[]']").each(function(){
            if($(this).is(':checked')){
                areas_valid = true;
            }
        });
        if(!areas_valid){
            valid = false;
            $("#"+form+" .area_error").show();
        }else{
            $("#"+form+" .area_error").hide();
        }
    }
    return valid;
}