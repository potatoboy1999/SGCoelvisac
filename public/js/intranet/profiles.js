$("#profile_btn").on('click',function(ev){
    ev.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        url: url,
        method: 'GET',
        success: function(res){
            $('#profileModal .modal-content').html(res);
            $('#profileModal').modal('show');
        }
    });
})

$(".btn-profile-edit").on('click',function(ev){
    ev.preventDefault();
    var url = $(this).data('route');
    var id = $(this).data('id');
    $.ajax({
        url: url+'?id='+encodeURIComponent(id),
        method: 'GET',
        success: function(res){
            $('#profileModal .modal-content').html(res);
            $('#profileModal').modal('show');
        }
    });
});