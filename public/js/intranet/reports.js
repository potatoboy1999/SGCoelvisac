$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).ready(function(){
    $('#schedules_tbl').DataTable({
        paging: false,
        bInfo: false,
        searching: false,
        order: [[colDefaultSort, 'asc']],
        columnDefs: [{
            orderable: false,
            targets: "no-sort"
        }]
    });
});

$(".new_activity").on('click', function(ev){
    ev.preventDefault();
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
            console.log('loading modal...');
        },
        success: function(res){
            $('#modalActivity .modal-content').html(res);

            //load scripts
            $("#act_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(date){
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

$("#modalActivity").on('submit', '#form_activity', function(ev){
    ev.preventDefault();
    console.log('submit');
    var route = $(this).attr('action');
    var dStart = $("#act_date_start").val();
    var dEnd = $("#act_date_end").val();
    var type = $("#form_activity input[name='tipo']").val();
    if(dStart != "" && dEnd != ""){
        $.ajax({
            url: route,
            method: 'POST',
            data: $(this).serialize(),
            beforeSend: function(){
                $("#modalActivity .modal-area").hide();
                $("#modalActivity .modal-loading").show();
                $("#modalActivity .btn-actions").hide();
            },
            success: function(res){
                console.log(res);
                $("#modalActivity .modal-area").hide();
    
                if(res.status == 'ok'){
                    //$("#modalActivity .modal-success").show();
                    $("#modalActivity").modal('hide');
                    var html = "";
                    if(res.action == "new"){
                        // add row to table
                        html = "<tr class='rep-act' act-id='"+res.report.id+"'>"+
                                    "<td class='d-description align-middle'>"+res.report.descripcion+"</td>"+
                                    "<td class='d-deal align-middle'>"+res.report.acuerdo+"</td>"+
                                    "<td class='d-start align-middle'>"+res.report.fecha_comienzo+"</td>"+
                                    "<td class='d-end align-middle'>"+res.report.fecha_fin+"</td>"+
                                    "<td class='d-status align-middle "+res.report.color+"'></td>"+
                                    "<td class='d-action align-middle text-center'>"+
                                        '<a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="'+res.report.id+'" data-type="'+res.report.type+'" travelid="'+res.schedule_id+'">'+
                                            '<svg class="icon">'+
                                                '<use xlink:href="'+asset_url+'#cil-pencil"></use>'+
                                            '</svg>'+
                                        '</a>'+
                                        '<a href="#" class="btn btn-danger btn-sm text-white btn-delete" data-id="'+res.report.id+'" style="margin-left: 5px;">'+
                                            '<svg class="icon">'+
                                                '<use xlink:href="'+asset_url+'#cil-trash"></use>'+
                                            '</svg>'+
                                        '</a>'+
                                    "</td>"+
                                "</tr>";
                        $(".activity-table[data-type='"+type+"']").append(html);
                    }else if(res.action == "edit"){
                        html = 
                            "<td class='d-description align-middle'>"+res.report.descripcion+"</td>"+
                            "<td class='d-deal align-middle'>"+res.report.acuerdo+"</td>"+
                            "<td class='d-start align-middle'>"+res.report.fecha_comienzo+"</td>"+
                            "<td class='d-end align-middle'>"+res.report.fecha_fin+"</td>"+
                            "<td class='d-status align-middle "+res.report.color+"'></td>"+
                            "<td class='d-action align-middle text-center'>"+
                                '<a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="'+res.report.id+'" data-type="'+res.report.type+'" travelid="'+res.schedule_id+'">'+
                                    '<svg class="icon">'+
                                        '<use xlink:href="'+asset_url+'#cil-pencil"></use>'+
                                    '</svg>'+
                                '</a>'+
                                '<a href="#" class="btn btn-danger btn-sm text-white btn-delete" data-id="'+res.report.id+'" style="margin-left: 5px;">'+
                                    '<svg class="icon">'+
                                        '<use xlink:href="'+asset_url+'#cil-trash"></use>'+
                                    '</svg>'+
                                '</a>'+
                            "</td>";
                        $(".rep-act[act-id='"+res.report.id+"']").html(html);
                    }
                    
                }else{
                    $("#modalActivity .modal-error").show();
                }
            },
            error: function(e){
                $("#modalActivity .modal-area").hide();
                $("#modalActivity .modal-error").show();
            }
        });
    }
});

$(".activity-table").on('click', '.btn-edit', function(ev){
    ev.preventDefault();
    var rep_id = $(this).data('id');
    var type = $(this).data('type');
    var schedule = $(this).attr('travelid');
    $.ajax({
        url: activity_modal,
        method: 'GET',
        data:{
            schedule_id: schedule,
            type: type,
            report_id: rep_id
        },
        beforeSend: function(){
            console.log('loading modal...');
        },
        success: function(res){
            $('#modalActivity .modal-content').html(res);

            //load scripts
            $("#act_date_start").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(date){
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

$(".activity-table").on('click', '.btn-delete', function(ev){
    ev.preventDefault();
    var act_id = $(this).data('id');
    $("#dltActivity").attr('activity-id', act_id);
    $("#deleteActivity").modal("show");
});

$(document).on('click','#dltActivity', function(ev){
    ev.preventDefault();
    var act_id = $(this).attr('activity-id');
    var data = {
        id: act_id,
        _token: $('input[name="_token"]').val(),
    };
    
    $.ajax({
        url: delete_route,
        method: 'POST',
        data: data,
        beforeSend: function(){
            $("#deleteActivity .modal-area").hide();
            $("#deleteActivity .modal-loading").show();
            $("#deleteActivity .btn-actions").hide();
        },
        success: function(res){
            if(res.status == "ok"){
                $(".rep-act[act-id="+act_id+"]").remove();
                $("#deleteActivity").modal("hide");
            }
        }
    });
});

$('#saveFinalBtn').on('click', function(ev){
    ev.preventDefault();
    $("#confirmFinalVersion").modal("show");
});

$("#confirmFinalBtn").on('click',function(ev){
    var act_id = $(this).data('id');
    var data = {
        id: act_id,
        _token: $('input[name="_token"]').val(),
    };
    
    $.ajax({
        url: finalize_url,
        method: 'POST',
        data: data,
        beforeSend: function(){
            $("#confirmFinalVersion .modal-area").hide();
            $("#confirmFinalVersion .btn-actions").hide();
            $("#confirmFinalVersion .modal-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                location.reload()
            }else{
                alert("Ha ocurrido un error");
            }
        }
    });
});