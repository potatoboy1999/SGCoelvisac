$(document).ready(function() {
    
});

function addSpinner(target){
    $(target).html(
        '<div class="spinner-border" role="status"><span class="sr-only"></span></div>'
    );
}

function loadEditFormRole(role_id){
    $.ajax({
        url: editFormUrl,
        data: {
            id: role_id
        },
        method: "GET",
        beforeSend: function(){
            addSpinner("#form-edit-role");
        },
        success: function(res){
            $("#form-edit-role").html(res);
        },
        error: function(err){
            console.error("error loading new form");
        }
    });
}

$(document).on('click','.new-role',function(ev){
    ev.preventDefault();
    $(".modal-section").hide();
    $("#form-role").show();
});

$(document).on('click','.edit-role',function(ev){
    ev.preventDefault();
    var role = $(this).attr("role");
    $(".modal-section").hide();
    $("#form-edit-role").show();
    loadEditFormRole(role);
});

$(document).on('click','.dlt-role',function(ev){
    ev.preventDefault();
    var role = $(this).attr('role');
    var role_name = $(".role-"+role+" .role-name").html();
    $("#f-form-delete input[name='id']").val(role);
    $("#role_dlt_name").html(role_name);

    $(".modal-section").hide();
    $("#form-delete").show();
});

$(document).on('show.coreui.dropdown','.dropdown', function() {
    var ddTrack = $(this).attr('ddTrack');
    $('body').append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position: 'absolute',
        left: $(this).offset().left,
        top: $(this).offset().top ,
        display: "block"
    }).detach());
});
$(document).on('hidden.coreui.dropdown', '.dropdown',function () {
    var ddTrack = $(this).attr('ddTrack');
    $(".dropdown[ddTrack='"+ddTrack+"']").append($(".dropdown-menu[ddTrack='"+ddTrack+"']").css({
        position:false, left:false, top:false, display: "none"
    }).detach());
});

$(document).on('submit',"#form-newRole", function(ev){
    ev.preventDefault();
    var data = $("#form-newRole").serialize();
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-newRole input[name='name']").val('');
            // show loading
            $(".modal-section").hide();
            $("#form-new-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                location.reload();
            }else{
                console.error("error creating role: "+res.msg);
            }
        },
        error: function(err){
            console.error("error creating role");
        }
    });
});

$(document).on('submit',"#form-editRole", function(ev){
    ev.preventDefault();
    var data = $("#form-editRole").serialize();
    $.ajax({
        url: $(this).attr("action"),
        data: data,
        method: 'POST',
        beforeSend: function(){
            // clean form
            $("#form-editRole input[name='name']").val('');
            // show loading
            $(".modal-section").hide();
            $("#form-edit-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                location.reload();
            }else{
                console.error("error updating role: "+res.msg);
            }
        },
        error: function(err){
            console.error("error updating role");
        }
    });
});

$(document).on('submit','#f-form-delete', function(ev){
    ev.preventDefault();
    var url = $(this).attr("action");
    $.ajax({
        url: url,
        data: $(this).serialize(),
        method: 'POST',
        beforeSend: function(){
            // show loading
            $(".modal-section").hide();
            $("#form-delete-loading").show();
        },
        success: function(res){
            if(res.status == "ok"){
                // $("#deleteActionModal").modal("hide");
                // loadSpecificMatrix(true);
                location.reload();
                // TO DO: find row & remove [LATER VERSION]
            }else{
                console.error('error deleting action: '+res.msg);
            }
        },
        error: function(err){
            console.error('error deleting action');
        }
    });
});