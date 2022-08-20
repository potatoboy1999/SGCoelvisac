function getReunion(date){
    $.ajax({
        url: reunion_route,
        data: {
            date: date
        },
        method:'GET',
        beforeSend: function(){
            $("#calendar-card").hide();
            $("#reunion-card").show();
            $("#reunion-card .card-body").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
        },
        success:function(res){
            $("#reunion-card .card-body").html(res);
        },
    });
}

function getCalendar(){
    var year = $("#form-area-sel input[name='year']").val();
    var month = $("#form-area-sel select[name='month']").val();
    $.ajax({
        url: calendar_route,
        data:{
            year: year,
            month: month,
        },
        method:'GET',
        beforeSend: function(){
            $("#reunion-card").hide();
            $("#calendar-card").show();
            $("#calendar-card .card-body").html('<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>');
        },
        success:function(res){
            $("#calendar-card .card-body").html(res);
        },
    });
}

$(document).on('click',".search-calendar",function(ev){
    ev.preventDefault();
    getCalendar();
})

$(document).on('click','.day-clickable',function(ev){
    var date = $(this).data('date');
    getReunion(date);
});

$(document).on('click', '.btn-download', function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("docid");
    route = route+"?id="+id;
    window.location.href = route;
});

$(document).on('click','.btn-view',function(ev){
    ev.preventDefault();
    var id = $(this).attr('docid');
    console.log("id:"+id);
    $.ajax({
        url: document_route,
        method: 'GET',
        data:{
            id: id
        },
        success: function(res){
            $("#documentModal .modal-content").html(res)
            $("#documentModal").modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(thrownError);
        }
    });
});

$(document).on('click','.dlt-old-file', function(ev){
    ev.preventDefault();
    var doc_id = $(this).attr('docid');
    var old_file = $(this).parent().parent();
    old_file.addClass('border-danger');
    $.ajax({
        url: delete_route,
        method: 'POST',
        data:{
            doc_id: doc_id,
            _token: $("input[name='_token']").val()
        },
        beforeSend: function(){
            old_file.addClass('border-danger');
            var html = 
                '<div class="spinner-border text-danger" role="status">'+
                    '<span class="visually-hidden">Cargando...</span>'+
                '</div>';
            old_file.html(html);
        },
        success: function(res){
            if(res.status == "ok"){
                old_file.remove();
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
            console.error(xhr.responseText);
            getReunion();
        }
    });
});

$('.btn-show').on('click', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');
    prepareModal(id);
});

$(document).on("change", ".add-file", function(ev){
    ev.preventDefault();
    var fileinput = $(this);
    var areaid = fileinput.attr("areaid");
    var form = $("#newFileForm"+areaid);
    var data = new FormData(form[0]);
    $.ajax({
        url: form.attr("action"),
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function(){
            // add loading indicator
            var html =  '<div class="old-file file-loading mb-1" areaid="'+areaid+'">'+
                            '<div class="file-section">'+
                                '<div class="spinner-border" role="status">'+
                                    '<span class="visually-hidden">Cargando...</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
            $(".old-files[areaid='"+areaid+"']").append(html);
            fileinput.val('');
            $("#newFileForm"+areaid).hide();
        },
        success: function(res){
            $("#newFileForm"+areaid).show();
            if(res.status == "ok"){
                // update forms with reunion id
                $("input[name='reunion']").val(res.reunion.id);

                // update with document info
                var newfile = $(".file-loading[areaid='"+areaid+"']");
                newfile.removeClass('file-loading');
                var html = '<div class="file-section file-action">'+
                                '<div class="action-buttons bg-danger dlt-old-file" docid="'+res.document.id+'">'+
                                    '<svg class="icon">'+
                                        '<use xlink:href="'+asset_route+'#cil-x"></use>'+
                                    '</svg>'+
                                '</div>'+
                            '</div>'+
                            '<div class="file-section file-action">'+
                                '<div class="action-buttons bg-success btn-view" href="'+download_route+'" docid="'+res.document.id+'">'+
                                    '<i class="fa-solid fa-eye"></i>'+
                                '</div>'+
                            '</div>'+
                            '<div class="file-section file-name">'+
                                '<p class="filename m-0">'+res.document.nombre+'</p>'+
                            '</div>';
                newfile.html(html);
            }else{
                if(res.code != 1){
                    // update forms with reunion id
                    $("input[name='reunion']").val(res.reunion.id);
                }
                $(".file-loading[areaid='"+areaid+"']").remove();
                alert(res.msg)
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
            console.error(xhr.responseText);
            getReunion();
        }
    });
});