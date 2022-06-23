$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

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

// FILTER MODAL FUNCTIONS

$("#search_from").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function(date){
        $("#act_date_end").datepicker('option', 'minDate', date);
    }
});

$("#search_to").datepicker({
    dateFormat: "dd/mm/yy",
});

$(".s-choice").on("click", function(ev){
    var target = $(this).data('target');
    var active = $(this).attr('active');

    if(active == "on"){
        $(this).parent().removeClass('active');
        $(target).prop('checked',false);
        $(this).attr('active','off');
    }else{
        $(this).parent().addClass('active');
        $(target).prop('checked',true);
        $(this).attr('active','on');
    }

    // make sure an status filter is active
    var isOn = false;
    $(".s-choice").each(function(){
        if($(this).attr('active') == "on"){
            isOn = true;
        }
    });

    if(!isOn){
        var choice = $(".s-choice").first()
        target = choice.data('target');
        active = choice.attr('active');

        choice.parent().addClass('active');
        $(target).prop('checked',true);
        choice.attr('active','on');
    }

});

$('.switch-f-choice').on('change',function(ev){
    var target = $(this).data('target');
    var active = $(this).prop('checked');

    console.log('target: ',target);
    console.log('active: ',active);

    if(active == true){
        $(target).prop('disabled',false);
    }else{
        $(target).prop('disabled',true);
    }

});

$(".toggle-dates").on("click",function(ev){
    ev.preventDefault();
    if($(this).attr("toggle-visible") == "false"){
        $(".th-date-start").show();
        $(".th-date-end").show();
        $(".t-date-start").show();
        $(".t-date-end").show();
        $(this).find("span").html("Ocultar Fechas");
        $(this).attr("toggle-visible","true");
    }else{
        $(".th-date-start").hide();
        $(".th-date-end").hide();
        $(".t-date-start").hide();
        $(".t-date-end").hide();
        $(this).find("span").html("Ver Fechas");
        $(this).attr("toggle-visible","false");
    }
});