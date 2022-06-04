$(function() {
    $.datepicker.setDefaults($.datepicker.regional['es']);
});
$("#act_date_start").datepicker({
    dateFormat: "dd/mm/yy",
    minDate: new Date(),
    onSelect: function(date){
        $("#act_date_end").datepicker('option', 'minDate', date);
    }
});
$("#act_date_end").datepicker({
    dateFormat: "dd/mm/yy",
    minDate: new Date(),
});

$("#item_save").on("click", function(ev){
    ev.preventDefault();
    var data = {
        new_role: $("#newRoleSwitch").prop("checked"),
        role_sel: $("#role_sel").val(),
        role_name: $("#role_name").val(),
        new_theme: $("#newThemeSwitch").prop("checked"),
        theme_sel: $("#theme_sel").val(),
        theme_name: $("#theme_name").val(),
        new_objective: $("#newObjSwitch").prop("checked"),
        obj_sel: $("#obj_sel").val(),
        obj_name: $("#obj_name").val(),
        activity_desc: $("#activity_desc").val(),
        date_start: $("#act_date_start").val(),
        date_end: $("#act_date_end").val(),
    };
    console.log("Save New Item", data);
    $("#role_form").submit();
});

$(".new_item_switch").on("change",function(ev){
    var object = $(this).data("object");
    var n = $(this).prop("checked");
    if(n){
        if(["obj","theme","role"].includes(object)){
            $("#obj_sel").hide();
            $("#obj_name").show();
            $("#newObjSwitch").prop("checked", true);
        }
        
        if(["theme","role"].includes(object)){
            $("#theme_sel").hide();
            $("#theme_name").show();
            $("#newThemeSwitch").prop("checked", true);
        }
        
        if(["role"].includes(object)){
            $("#role_sel").hide();
            $("#role_name").show();
            $("#newRoleSwitch").prop("checked", true);
        }
    }else{
        if(["obj","theme","role"].includes(object)){
            $("#role_sel").show();
            $("#role_name").hide();
            $("#newRoleSwitch").prop("checked", false);
        }
        if(["obj","theme"].includes(object)){
            $("#theme_sel").show();
            $("#theme_name").hide();
            $("#newThemeSwitch").prop("checked", false);
        }
        if(["obj"].includes(object)){
            $("#obj_sel").show();
            $("#obj_name").hide();
            $("#newObjSwitch").prop("checked", false);
        }
    }
});

$("#role_sel").on("change",function(){
    console.log("role change:",$(this).val());
    var role_id = $("#role_sel option:selected").val();
    var role = global_items.find(role=> role.id == role_id);

    var theme_options = "";
    var obj_options = "";

    var x = 0;
    role.themes.forEach(theme => {
        theme_options += "<option value='"+theme.id+"'>"+
                         ((theme.id < 10)?"0"+theme.id:theme.id)+": "+
                         theme.nombre+
                         "</option>";
        if(x == 0){
            theme.objectives.forEach(objective => {
                obj_options += "<option value='"+objective.id+"'>"+
                                ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                objective.nombre+
                                "</option>";
            });
        }
    });

    $("#theme_sel").html(theme_options);
    $("#obj_sel").html(obj_options);

});

$("#theme_sel").on("change",function(){
    console.log("theme change:",$(this).val());
    var theme_id = $("#theme_sel option:selected").val();
    var role = global_items.find(role=> role.themes.find(theme=>theme.id == theme_id));

    var obj_options = "";

    var x = 0;
    role.themes.forEach(theme => {
        if(theme.id == theme_id){
            theme.objectives.forEach(objective => {
                obj_options += "<option value='"+objective.id+"'>"+
                                ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                objective.nombre+
                                "</option>";
            });
        }
    });
    
    $("#obj_sel").html(obj_options);
});

var global_items = [];

function setupNewItemModal(){
    if(global_items.length > 0){
        var role_options = "";
        var theme_options = "";
        var obj_options = "";

        var i = 0;
        global_items.forEach(role => {
            role_options += "<option value='"+role.id+"'>"+
                            ((role.id < 10)?"0"+role.id:role.id)+": "+
                            role.nombre+
                            "</option>";
            if(i == 0){
                var x = 0;
                role.themes.forEach(theme => {
                    theme_options += "<option value='"+theme.id+"'>"+
                                     ((theme.id < 10)?"0"+theme.id:theme.id)+": "+
                                     theme.nombre+
                                     "</option>";
                    if(x == 0){
                        theme.objectives.forEach(objective => {
                            obj_options += "<option value='"+objective.id+"'>"+
                                            ((objective.id < 10)?"0"+objective.id:objective.id)+": "+
                                            objective.nombre+
                                            "</option>";
                        });
                    }
                });
            }
            i++;
        });

        $("#role_sel").html(role_options);
        $("#theme_sel").html(theme_options);
        $("#obj_sel").html(obj_options);
    }else{
        // switch all options to create NEW ITEM
        $("#newRoleSwitch").prop("checked", true);
        $("#newThemeSwitch").prop("checked", true);
        $("#newObjSwitch").prop("checked", true);
        $("#role_sel").hide();
        $("#theme_sel").hide();
        $("#obj_sel").hide();
    }
}