$(function ()
{

    bindEventTools();

});

/**
 * Функция добавляет события в toolbar
 */
function bindEventTools()
{

    $("#medit-tools img").bind("click", function(event)
    {
        var $this = $(this);

        var widget = $this.attr("data-widget");
        var pageId = $('#medit-tools').attr("data-page-id");

        if($this.attr("data-window_add") == "") {
            addWidgetNoWindows(widget);
        } else {
            var windowSize = $this.attr("data-window-size");

            addWidgetWindows(widget, windowSize, pageId);
        }
    });




}

function addWidgetNoWindows(widget) {

}

function addWidgetWindows(widget, windowSize, pageId)
{
    url = '/Widgets/AddForm/widget/' + widget + '/pageId/' + pageId;

    widgetWindow(url, windowSize);
}

function widgetWindow(url, size)
{
    //MCMSwindowCreateHtml();

    /* размер окна*/
    size = size.split("x");

    width  = size[0];
    height = size[1];

    $('#MCMSwindow').width(width);
    $('#MCMSwindow').height(height);
    $('#MCMSwindow iframe').contents().find('body').html('<body><div style="position:absolute;left:50%;top:50%;margin-left: -50px;margin-top: -50px;width:100px;">Load...</div></body>');

    var setting = {
        x:false,
        y:false,
        autoOpen:true,
        mask:true,
        quickClose:true
    };

    /*Открываем окно*/
    $('#MCMSwindow').MCMSwindow(setting);


    $('#MCMSwindow iframe').attr('src',url);
}

function addWidget(html, widget, id){



    $('#mcms-widgets').append(widgetWrap(id, widget, html));
    $widget = getWidget(id);

    $('#MCMSwindow').MCMSwindow('close');

    //$(document).scrollTop($widget.offset().top);

    $.scrollTo($widget, {offset:{'top':-200}, 'duration':600, 'onAfter':function(){
        $widget.hide().fadeIn();
    }});


}

function getWidget(id){
    return $('.mcms-widget[data-id="'+id+'"]');
}


function widgetWrap(id, widget, html)
{
    return '<div class="mcms-widget ' + widget +
        '" data-id="' + id + '">' + html + '</div>';

}

/**
 * шаблон панели инструментов виджета
 * @param tools_class - имя css класса
 * @returns {string}
 */
function widgetTools(tools_class)
{
    html = '<div class="' + tools_class + '">';

    html += '<a href="#" class="edit fa fa-pencil-square-o" title="Редактировать"></a>';

    html += '<a href="#" class="up fa fa-angle-up"  title="Вверх" ></a>';
    html += '<a href="#" class="down fa fa-angle-down" title="Вниз"></a>';
    html += '<a href="#" class="del fa fa-times"  title="Удалить"></a>';
    html += '</div>';

    return html;
}

/**
 * функция добавляет к виджету панель инструментов
 * @param el - dom объект
 */

function widgetToolsCreate(el)
{
    var tools_class = "mcms-widget";

    html = element_art_tools_tpl(tools_class);

    $(el).append(html);

    $('.'+ tools_class +' .edit').click(function (event)
    {
        event.preventDefault();
        var $el = $(this).parent().parent();
        element_art_edit($el);
    });

    $('.'+ tools_class +' .del').click(function ()
    {
        var el = $($(this).parent().parent())[0];
        if (confirm("Удалить блок")) {
            id = $(el).attr("date-id");
            widget =  $(el).attr("date-widget");
            delete_element_art(id, widget);
        }
        $(this).unbind('click');
        return false;
    });

    $('.'+ tools_class +' .up').click(function ()
    {
        var $el = $(this).parent().parent();

        element_art_pos($el, 'up');
        //save();
        element_art_tools_hide();
        return false
    });

    $('.'+ tools_class +' .down').click(function ()
    {
        var $el = $(this).parent().parent();

        element_art_pos($el, 'down');
        //save();
        element_art_tools_hide();
        return false
    });
}




