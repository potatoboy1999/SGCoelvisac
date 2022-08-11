$("#newBranch").on('click', function(ev){
    ev.preventDefault();
    $.ajax({
        url: new_popup,
        method: 'GET',
        beforeSend: function(){
            console.log('Popup New Branch');
        },
        success: function(res){
            $("#newEditModal .modal-content").html(res);
            $(".modal-area").hide();
            $(".modal-form").show();
            $("#newEditModal").modal("show");
        },
        error: function(e){
            console.log(e);
        }
    });
});

$(document).on('click','.btn-edit', function(ev){
    ev.preventDefault();
    var id = $(this).data("id");
    $.ajax({
        url: edit_popup,
        method: 'GET',
        data: {id: id},
        beforeSend: function(){
            //console.log('Popup New Branch');
        },
        success: function(res){
            $("#newEditModal .modal-content").html(res);
            $(".modal-area").hide();
            $(".modal-form").show();
            $("#newEditModal").modal("show");
        },
        error: function(e){
            console.log(e);
        }
    });
});

$(document).on('click','.btn-remove', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');
    $("#form_delete [name='reunion_id']").val(id);
    $("#deleteBranchModal").modal('show');
});

$(document).on('submit','#branchForm',function(ev){
    ev.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        beforeSend: function(){
            $(".modal-area").hide();
            $(".btn-actions").hide();
            $(".modal-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                location.reload();
                // $(".modal-success").show();
            }else{
                $(".modal-area").hide();
                $("#error_msg").html(res.message);
                $(".modal-error").show();
            }
        },
        error: function(e){
            $(".modal-area").hide();
            $(".modal-error").show();
        }
    });
});