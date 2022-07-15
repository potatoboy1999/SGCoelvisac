$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
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
                                    "<td class='d-status align-middle'>"+res.report.estado+"</td>"+
                                    "<td class='d-action align-middle'>"+
                                        '<a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="'+res.report.id+'">'+
                                            '<svg class="icon">'+
                                                '<use xlink:href="'+asset_url+'#cil-pencil"></use>'+
                                            '</svg>'+
                                        '</a>'+
                                        '<a href="#" class="btn btn-info btn-sm text-white btn-delete" data-id="'+res.report.id+'">'+
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
                            "<td class='d-status align-middle'>"+res.report.estado+"</td>"+
                            "<td class='d-action align-middle'>"+
                                '<a href="#" class="btn btn-info btn-sm text-white btn-edit" data-id="'+res.report.id+'">'+
                                    '<svg class="icon">'+
                                        '<use xlink:href="'+asset_url+'#cil-pencil"></use>'+
                                    '</svg>'+
                                '</a>'+
                                '<a href="#" class="btn btn-danger btn-sm text-white btn-delete" data-id="'+res.report.id+'">'+
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

$(".activity-table").on('click', '.btn-delete', function(ev){
    ev.preventDefault();
    $("#deleteActivity").modal("show");
});