$(function(){
    checkThemes();
});

$("#presenters").autocomplete({
    minLength: 0,
    source: usernames_route,
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
    $parent = $(this).parent().parent().parent();
    $removable = $parent.find(".area_docs[area-count='"+area+"']");
    // add removable docs
    $removable.find(".dlt-old-file").each(function(e){
        var doc_id = $(this).attr("docid");
        $("#docs-deleted").append("<input type='hidden' name='docs_deleted[]' value='"+doc_id+"'>");
    });

    $removable.remove();
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
                '<select class="form-select area_select" name="area['+target+'][area'+counter+']" id="area_name'+counter+'">';
        // ADD AREAS OPTION
        areas.forEach(function(area){
            html += '<option value="'+area.id+'">'+area.nombre+'</option>';
        });
        html += '</select>'+
                '<hr>'+
                '<label class="form-label" for="area_name">Archivos:</label>'+
                '<input type="file" class="form-control not-filled mb-2" name="files['+target+'][area'+counter+'][]">'+
            '</div>'+
        '</div>';
    $("#"+target+" .area_docs_list").append(html);

    $("#"+target+" .area_docs_list").attr('counter', counter);
});

$("#addTheme").on('click', function (ev) {
    ev.preventDefault();
    var counter = $("#themes_div").attr('counter');
    counter = parseInt(counter);
    counter++;
    var html = 
        '<div class="theme_elem card mb-4" id="theme'+counter+'" theme_code="'+counter+'">'+
            '<div class="card-header">'+
                '<div class="float-end">'+
                    '<a href="#" class="btn btn-sm btn-danger text-white rm_theme" theme="'+counter+'" style="line-height: 1;">X</a>'+
                '</div>'+
                '<span>Tema:</span>'+
            '</div>'+
            '<div class="card-body">'+
                '<div class="row">'+
                    '<div class="col-sm-4 border-right">'+
                        '<div class="mb-2">'+
                            '<label for="theme'+counter+'" class="form-label">Nombre de Tema</label>'+
                            '<input type="text" class="form-control" name="theme[theme'+counter+']" required>'+
                        '</div>'+
                        '<div><a class="btn btn-secondary text-white newAreaBtn" href="#" target="theme'+counter+'">+ Nueva Área</a></div>'+
                    '</div>'+
                    '<div class="col-sm-8">'+
                        '<div class="area_docs_list" counter="1">'+
                            '<div class="area_docs border rounded p-3 mb-2" area-count="1">'+
                                '<label class="form-label" for="area_name">Área:</label> '+
                                '<select class="form-select area_select" name="area[theme'+counter+'][area1]" id="area_name1">';
                        // ADD AREAS OPTION
                        areas.forEach(function(area){
                            html += '<option value="'+area.id+'">'+area.nombre+'</option>';
                        });
                        html += 
                                '</select>'+
                                '<hr>'+
                                '<label class="form-label">Archivos:</label>'+
                                '<input type="file" class="form-control not-filled mb-2" name="files[theme'+counter+'][area1][]">'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';
    $("#themes_div").append(html);
    $("#themes_div").attr('counter',counter);

    checkThemes();
});

$(document).on('click','.rm_theme', function(ev) {
    ev.preventDefault();
    var theme_id = $(this).attr("theme");
    var theme = $(".theme_elem[theme_code='"+theme_id+"']");
    var id = theme.attr("themeid");
    if(id != undefined){
        $("#themes-deleted").append("<input type='hidden' name='themes_deleted[]' value='"+id+"'>");
    }
    theme.remove();

    checkThemes();
});

$(document).on('change', "input.not-filled", function(ev){
    $(this).removeClass('not-filled');
    var parent = $(this).parent();
    if($(this).hasClass('new')){
        parent = parent.parent();
    }
    $(this).wrap(function(){
        return '<div class="extra-inputs"></div>';
    });

    $(this).before('<a class="btn btn-danger btn-sm text-white rm_doc">X</a>');

    var name = $(this).attr('name');
    // var html =
    //     '<div class="extra-inputs">'+
    //         '<a class="btn btn-danger btn-sm text-white rm_doc">X</a>'+
    //         '<input type="file" class="form-control new not-filled mb-2" name="'+name+'">'+
    //     '</div>';
    var html = '<input type="file" class="form-control not-filled mb-2" name="'+name+'">';
    parent.append(html);
});

$(document).on('click', '.rm_doc', function(ev){
    ev.preventDefault();
    $(this).parent().remove();
})

$(".dlt-old-file").on('click', function(ev){
    ev.preventDefault();
    var doc_id = $(this).attr('docid');
    var old_file = $(this).parent();
    var old_files = $(this).parent().parent();
    old_file.remove();
    if(old_files.find(".old-file").length == 0){
        old_files.remove();
    };
    $("#docs-deleted").append("<input type='hidden' name='docs_deleted[]' value='"+doc_id+"'>");
});

$("button[form='new_results_form']").on('click', function(ev){
    ev.preventDefault();

    // check at least one presenter
    if($(".presenter-name").length == 0){
        $("#alertModal .modal-body").html('<p class="text-danger">Agrege al menos un presentador</p>');
        $("#alertModal").modal('show');
        return;
    }

    // check at least one area per theme
    var area_val = true;
    $(".area_docs_list").each(function(area){
        if($(this).children().length == 0){
            area_val = false;
        }
    });
    if(!area_val){
        $("#alertModal .modal-body").html('<p class="text-danger">Cada tema debe tener al menos una área</p>');
        $("#alertModal").modal('show');
        return;
    }

    // check all areas have at least one old or new file added per area
    var docs_valid = true;
    $(".area_docs").each(function(i){
        var old_files = $(this).find(".old-file").length;
        var imp_files = $(this).find("input[type=file]:not(.not-filled)").length;
        // console.log("Old Files Found: "+old_files+" | Imported Files Found:"+imp_files);
        if(old_files == 0 && imp_files == 0){
            docs_valid = false;
        }
    });
    if(!docs_valid){
        $("#alertModal .modal-body").html('<p class="text-danger">Cada área debe tener al menos un documento</p>');
        $("#alertModal").modal('show');
        return;
    }

    //$("#new_results_form").submit();
    $("#hidden-form-submit").trigger('click');
});

function checkThemes(){
    var count = $(".theme_elem").length;
    if(count == 1){
        $(".theme_elem .rm_theme").hide();
    }else{
        $(".theme_elem .rm_theme").show();
    }
}