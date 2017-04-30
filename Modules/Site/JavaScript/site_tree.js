var tree_data = new Array();

var loader =
{
    SaveStart:function(menuId)
    {
        $("#mcms-list-page-" + menuId+ '-panel .save').show()
    },
    SaveEnd:function(menuId)
    {
        $("#mcms-list-page-" + menuId+ '-panel .save').hide()
    },
    LoadStart:function(menuId)
    {
        $("#mcms-list-page-" + menuId+ '-panel .load').show()
    },
    LoadEnd:function(menuId)
    {
        $("#mcms-list-page-" + menuId+ '-panel .load').hide()
    }
};


$(function(){
    $('[role=menu-delete]').click(

        function(){
            var menu_id = $(this).attr('data-menu-id');
            loader.LoadStart(menu_id);

            $.ajax({
                url: '/Site/MenuDelete/id/' + menu_id,
                dataType : "json",
                success: function (data, textStatus)
                {
                    loader.LoadEnd(data.menuId);
                    if(data.message != '') alert(data.message);

                    if(data.delete) $('#mcms-list-page-' + data.menuId + '-panel').remove();

                }
            });
        }
    );
});

function site_tree_init(menu_id) {
    var setting = {
        data: {
            simpleData: {
                enable: true
            }
        },
        view: {
            /*событие срабатывает поле добавления узла*/
            addDiyDom: addDiyDom,
            selectedMulti: false
        },
        edit: {
            enable: true,
            showRemoveBtn: false,
            showRenameBtn: false
        },
        callback: {

            onDrop: onDrop
        }

    };
    loader.LoadStart(menu_id);
    $.ajax({
        url: '/Site/MenuTree/id/' + menu_id,
        dataType : "json",
        success: function (data, textStatus) {
            loader.LoadEnd(data.menuId);

            var $tree = $("#mcms-list-page-" + data.menuId);

            var treeObj =  $.fn.zTree.init($tree, setting, data.items);

            $tree.find('[role=menu-item-delete]').click(function()
            {

                $link =  $(this);
                deleteItemMenu(treeObj, $link, data.menuId);



                return false;
            });

/*
            $tree.find('[role=set-index]').click(function()
            {

                $link =  $(this);


                makeIndex(treeObj, $link, data.menuId)

                return false;
            });
            */
        }

    });


}

function deleteItemMenu(treeObj, $link, menu_id){

    var confirm_text = $link.attr('data-confirm-text');
    var durl = $link.attr('href');

    var isDelete = confirm(confirm_text);
    if(isDelete)
    {
        var nodes = treeObj.getSelectedNodes();
        loader.SaveStart(menu_id);
        $.ajax({
            url: durl,
            dataType : "json",
            success: function (data, textStatus) {

                console.dir(data);
                if(data.message != '') alert(data.message);

                if(data.delete) treeObj.removeNode(nodes[0]);
                $('.dropdown').removeClass('open');
                loader.SaveEnd(menu_id);

            }
        });
    }
}


function makeIndex(treeObj, $link, menu_id){


    var iurl = $link.attr('href');


        var nodes = treeObj.getSelectedNodes();


        loader.SaveStart(menu_id);

        $.ajax({
            url: iurl,
            dataType : "json",
            success: function (data, textStatus) {


                if(data.message != '') alert(data.message);

                if(data.makeIndex){

                    $('.ico.fa-home').removeClass('fa-home').addClass('fa-file-o');
                    $ico = $('#'+nodes[0].tId).find('.ico.fa-file-o');
                    $ico.removeClass('fa-file-o').addClass('fa-home');
                }
                $('.dropdown').removeClass('open');
                loader.SaveEnd(menu_id);

            }
        });

}

function onDrop(event, treeId, treeNodes, targetNode, moveType)
{
    var menu_id = treeNodes[0].menuId;

    //$("#mcms-list-page-" + treeNodes[0].menuId+ '-panel .save').show();
    loader.SaveStart(menu_id);
    update_tree_node($("#" + treeId), 0);
    console.dir(tree_data);
    $.ajax({
        url: '/Site/MenuTreeSave',
        type: 'POST',
        data: ({tree:tree_data}),
        success: function(data)
        {
            console.dir(data);
            loader.SaveEnd(menu_id);
           // $("#mcms-list-page-" + treeNodes[0].menuId+ '-panel .save').hide();
        }//,      traditional:true
    });

}

function site_tree_context_menu(menu_data)
{

    if(typeof (menu_data) != "object") return '';

    var html = '';

    for (var i in menu_data)
    {
        html += '<li role="presentation">';
        html += '<a role="'+ menu_data[i].role +'" tabindex="-1" href="' + menu_data[i].url + '" '+menu_data[i].attr+'>';
        html += '<i class="'+  menu_data[i].icoClass +'" aria-hidden="true"></i>';
        html += ' '+  menu_data[i].name +'</a></li>';
    }
    return html;
}


function addDiyDom(treeId, treeNode) {
    //console.dir(treeNode);
    var $node = $('#'+treeNode.tId);
    var aObj  = $("#" + treeNode.tId + '_a');


    if(!treeNode.pId) treeNode.pId = 0;
    $node.attr('data-pid', treeNode.pId);
    $node.attr('data-id', treeNode.id);
    $node.attr('data-pos', 0);
    $node.attr('data-menu-id', treeNode.menuId);
    $node.find('.node_name:first').html($node.find('.node_name:first').text());
    if(treeNode.show == 0) $node.addClass('hide-in-menu');

    //иконки
    var icon = '';
    if(typeof (treeNode.icons) == "object")
    {
        for (var i in treeNode.icons)
        {
            var ico_class = treeNode.icons[i].class;
            var title     = (treeNode.icons[i].title != undefined) ? treeNode.icons[i].title : '';

            icon += '<i class="' + ico_class + ' ico" title="' + title + '"></i>';
        }

    }

    var html = '';

    aObj.attr('data-toggle',"dropdown");
    //aObj.addClass('menu');

    aObj.wrap('<span class="dropdown"></span>');
    //$("#" + treeNode.tId + "_ico").remove();

    aObj.parent().append('<ul class="dropdown-menu" role="menu" aria-labelledby="drop1">' + site_tree_context_menu(treeNode.menu) + '</ul>');
    aObj.parent().append('<span class="module">'+ treeNode.module_name +'</span>');
    //aObj.parent().prepend(icon);
    $("#" + treeNode.tId + "_ico").prepend(icon);


}
//проходимся по дереву и обновляем позицию и родительский ид

function update_tree_node($ul, parent_id)
{
    var pos = 0;

    //$('mcms-list-page-<?=$menu['id']?>-save')

    var $lis = $ul.children('li').not('[role="presentation"]');
    $lis.each(
        function ()
        {

            var $node = $(this);
            var id = $node.attr('data-id');
            var attr_id = $node.attr('id');


            if(!id) return false;

            pos++;

            var child_count = $node.find('#'+attr_id+'_ul > li').size();

            console.log('111'+child_count);

            $node.find('.pos:first').text(pos);
            $node.find('.pid:first').text(parent_id);

            $node.attr('data-pid', parent_id);
            $node.attr('data-pos', pos);
            $node.attr('data-child-count', child_count);


            tree_data.push([id, parent_id, pos, child_count]);


            update_tree_node($('#'+attr_id+'_ul'), id);

        }
    );
}





/*

 <li id="mcms-list-page-1_15" class="level0" tabindex="0" hidefocus="true" treenode="">
    <span id="mcms-list-page-1_15_switch" title="" class="button level0 switch center_open" treenode_switch=""></span>
    <span class="dropdown">
        <a data-toggle="dropdown" id="mcms-list-page-1_15_a" class="level0" treenode_a="" onclick="" target="_blank" style="" title="Пункт 3">
            <span id="mcms-list-page-1_15_ico" title="" treenode_ico="" class="button ico_open" style="">
                <i class="fa fa-file-o" title="undefined"></i></span>
                <span id="mcms-list-page-1_15_span" class="node_name">Пункт 3</span>
        </a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="/?module=site&amp;action=item_menu_edit&amp;menu_item_id=3&amp;type=dialog">
            <i class="fa fa-pencil" aria-hidden="true"></i> Редактировать</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="/?module=site&amp;action=item_menu_delete&amp;menu_item_id=3&amp;type=dialog">
            <i class="fa fa-trash-o" aria-hidden="true"></i> Удалить</a></li>
        </ul>
        <span class="module">Страница</span>
         Id:3 pId:<span class="pid">null</span> pos: <span class="pos"></span>
    </span>
    <ul id="mcms-list-page-1_15_ul" class="level0 line" style="display:block">
        под пункты
    </ul>
  </li>

 */