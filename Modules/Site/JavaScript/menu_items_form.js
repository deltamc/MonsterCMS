$("select[name=menu_item_menu]").change(function (){menu_select(0)});
$(function (){menu_select($("select[name=menu_item_parent]").val())});



function menu_select(selected)
{
    val = $("select[name=menu_item_menu]").val();

    //$("select[name=menu_item_parent]").val('0');

    $menu_item_parent_option = $("select[name=menu_item_parent] option");

    $menu_item_parent_option.show();

    $menu_item_parent_option.not("[data-menu="+val+"]").hide();

    $("select[name=menu_item_parent]").val(selected);


}
