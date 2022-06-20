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

$(".t_collapsible button[data-bs-toggle='collapse']").on('click', function(ev){
    ev.preventDefault();
    var type = $(this).attr('type');
    var target = $(this).data('target');
    var parent = $(this).data('parent');
    var expand = $(this).attr('aria-expand');
    var icon = $(this).find('use');
    var href = icon.attr('xlink:href');
    
    if(expand == "true"){
        $(this).attr('aria-expand','false');
        $(target).parent().parent().addClass('border-w-0');
        href = href.replace('cil-chevron-double-up','cil-chevron-double-down');
        icon.attr('xlink:href',href);
        $(target).collapse("hide");

        if(parent == undefined){
            console.log('search children:',"button[data-parent='"+target+"']");
            var children = $("button[data-parent='"+target+"']");
            children.each(function(){
                var c_target = $(this).data('target');
                var c_icon = $(this).find('use');
                var c_href = icon.attr('xlink:href');

                $(this).attr('aria-expand','false');
                $(c_target).parent().parent().addClass('border-w-0');
                c_href = c_href.replace('cil-chevron-double-up','cil-chevron-double-down');
                c_icon.attr('xlink:href',c_href);
                $(c_target).collapse("hide");
            });
        }
    }else{
        $(this).attr('aria-expand','true');
        $(target).parent().parent().removeClass('border-w-0');
        href = href.replace('cil-chevron-double-down','cil-chevron-double-up');
        icon.attr('xlink:href',href);
        $(target).collapse("show");
        // COMMENTED TO DONT OPEN ALL THEME OPTIONS
        // if(parent == undefined){
        //     console.log('search children:',"button[data-parent='"+target+"']");
        //     var children = $("button[data-parent='"+target+"']");
        //     children.each(function(){
        //         var c_target = $(this).data('target');
        //         var c_icon = $(this).find('use');
        //         var c_href = icon.attr('xlink:href');

        //         $(this).attr('aria-expand','true');
        //         $(c_target).parent().parent().remove('border-w-0');
        //         c_href = c_href.replace('cil-chevron-double-down','cil-chevron-double-up');
        //         c_icon.attr('xlink:href',c_href);
        //         $(c_target).collapse("show");
        //     });
        // }
    }
});

$(document).on("click",".btn-file-download",function(ev){
    ev.preventDefault();
    var route = $(this).attr("href");
    var id = $(this).attr("file-id");
    route = route+"?id="+id;
    window.location.href = route+"?id="+id;
});