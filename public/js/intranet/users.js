$(".btn-edit-user").on('click', function(ev){
    ev.preventDefault();
    var id = $(this).data('id');
    var route = $(this).data('route');
    $.ajax({
        url: route+"?id="+id,
        method: 'GET',
        success: function(res){
            $("#saveUserModal .modal-content").html(res);
            $("#saveUserModal").modal("show");
        }
    });
});

$("#new_user_btn").on('click', function(ev){
    ev.preventDefault();
    var route = $(this).attr('href');
    $.ajax({
        url: route,
        method: 'GET',
        success: function(res){
            $("#saveUserModal .modal-content").html(res);
            $("#saveUserModal").modal("show");
        }
    });
});

$(document).on('change',"#user_area", function(ev){
    ev.preventDefault();
    var area_id = $(this).val();
    var area = areas.find(function(a){
        return a.id == area_id;
    });
    var positions = area.positions;
    var html = "";
    positions.forEach(pos => {
        html += "<option value='"+pos.id+"'>"+pos.nombre+"</option>";
    });
    if(html == ""){
        html += "<option value=''>-- No hay posiciones --</option>";
        $("#save_user_btn").attr('disabled',true);
    }else{
        $("#save_user_btn").attr('disabled',false);
    }
    $("#user_position").html(html);
});