$("#presenters").autocomplete({
    minLength: 0,
    source: [ 
        {label: "Alejandro" ,value:"Alejandro", userid:"0"}, 
        {label: "Melvin"    ,value:"Melvin", userid:"1"}, 
        {label: "Diego"     ,value:"Diego", userid:"2"}, 
        {label: "Javier"    ,value:"Javier", userid:"3"}, 
        {label: "Roberto"   ,value:"Roberto", userid:"4"}, 
        {label: "Catalina"  ,value:"Catalina", userid:"5"}, 
        {label: "Rosa"      ,value:"Rosa", userid:"6"}
    ],
    select: function(ev, ui){
        ev.preventDefault();
        console.log(ui);
        if($(".user"+ui.item.userid).length == 0){
            var html = '<div class="presenter-name user'+ui.item.userid+'" userid="'+ui.item.userid+'">'+ui.item.label+'</div>'+
                    '<input class="user'+ui.item.userid+'" type="hidden" name="users[]" value="'+ui.item.userid+'">';
            $("#presenters_names").append(html);
        }
        $("#presenters").val("");
    }
});

$(document).on('click','.presenter-name', function(ev){
    var userid = $(this).attr('userid');
    $('.user'+userid).remove();
});

$(document).on('click', '.rm_area', function(ev){
    ev.preventDefault();
    var area = $(this).attr('area');
    $(".area_docs[area-count='"+area+"']").remove();
});

$(document).on('click','.newAreaBtn',function(ev){
    ev.preventDefault();
    var target = $(this).attr('target');
    var counter = $("#"+target+" .area_docs_list").attr('counter');
    var theme_code = $("#"+target).attr('theme_code');
    counter = parseInt(counter);
    counter++;
    var html = 
            '<div class="area_docs border rounded mb-2" area-count="'+counter+'">'+
                '<div class="w-100 text-end">'+
                    '<a href="#" class="rm_area" area="'+counter+'">X</a>'+
                '</div>'+
                '<div class="p-3">'+
                    '<label class="form-label" for="area_name">Área:</label> '+
                    '<select class="form-select area_select" name="area['+target+'][]" id="area_name'+counter+'">';
            // ADD AREAS OPTION
            areas.forEach(function(area){
                html += '<option value="'+area.id+'">'+area.nombre+'</option>';
            });
            html += '</select>'+
                    '<hr>'+
                    '<label class="form-label" for="area_name">Archivos:</label>'+
                    '<input type="file" class="form-control" name="files['+target+'][area'+counter+'][]">'+
                '</div>'+
            '</div>';
    $("#"+target+" .area_docs_list").append(html);

    $("#"+target+" .area_docs_list").attr('counter', counter);
});