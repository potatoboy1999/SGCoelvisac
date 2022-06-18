// btn that opens adjacent document show/upload modal
$(".btn-show-adjacent").on("click",function(ev){
    ev.preventDefault();
    var id = $(this).data("id");
    var route = $(this).data("route");

    $.ajax({
        url: route,
        method: 'get',
        data:{
            id: id,
        },
        success:function(res){
            console.log("sup");
            $("#adjacentModal .modal-content").html(res);
            // show modal
            $("#adjacentModal").modal("show");
        }
    });
});

$(document).on("click",".btn-file-download",function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    route = route+"?id="+id;
    window.location.href = route+"?id="+id;
});