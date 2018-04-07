var toolsClass = "mcms-widget-tools";
var widgetClass = ".mcms-widget";

$(function ()
{

    bindEventTools();
    widgetToolsBind($(widgetClass), toolsClass);
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

        if($this.attr("data-window-size") == "") {
            addWidgetNoWindows(widget, pageId);
        } else {
            var windowSize = $this.attr("data-window-size");

            addWidgetWindows(widget, windowSize, pageId);
        }
    });




}

function addWidgetNoWindows(widget, pageId) {
    url = '/Widgets/AddWidgetOutsideWindow/widget/' + widget + '/pageId/' + pageId;

    $.ajax({
        url: url,
        dataType : "json",
        success: function (data, textStatus)
        {

            if (typeof data.js === "object") {
                addJavaScriptFiles(data.js, function(){
                    addWidget(data.html, data.widget, data.id, data.pos);
                });
            } else {
                addWidget(data.html, data.widget, data.id, data.pos);
            }
            addCssFiles(data.css);

        }
    });
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
        quickClose:false
    };

    /*Открываем окно*/
    $('#MCMSwindow').MCMSwindow(setting);


    $('#MCMSwindow iframe').attr('src',url);
}

function addWidget(html, widget, id, pos){


    //$('#mcms-widgets').append(widgetWrap(id, widget, html, pos));
    $('#mcms-widgets').append(html);
    $widget = getWidget(id);
    $widget.css('display','block');
    $('#MCMSwindow').MCMSwindow('close');

    //$(document).scrollTop($widget.offset().top);

    $.scrollTo($widget, {offset:{'top':-200}, 'duration':600, 'onAfter':function(){
        $widget.hide().fadeIn();
    }});

    widgetToolsBind($widget, toolsClass )


}

function getWidget(id){
    return $('.mcms-widget[data-id="'+id+'"]');
}

/*
function widgetWrap(id, widget, html, pos)
{
    return '<div class="mcms-widget ' + widget +
        '" data-id="' + id +
        '" data-pos="'+pos+'" data-widget="'+widget+'">' + html + '</div>';

}
*/
/**
 * шаблон панели инструментов виджета
 * @param toolsClass - имя css класса
 * @returns {string}
 */
function widgetTools(toolsClass, edit)
{
    edit = edit || false;
    html = '<div class="' + toolsClass + '" >';

    if(edit) {
        html += '<a href="#" class="edit fa fa-pencil" title="Редактировать"></a>';
    }


    html += '<a href="#" class="up fa fa-angle-up"  title="Вверх" ></a>';
    html += '<a href="#" class="down fa fa-angle-down" title="Вниз"></a>';
    html += '<a href="#" class="del fa fa-times"  title="Удалить"></a>';
    html += '</div>';

    return html;
}

/**
 * функция добавляет к виджету панель инструментов
 *
 * @param el - dom объект
 * @param toolsClass - toolsClass
 */

function widgetToolsCreate(el, toolsClass)
{

    var windowSize = $(el).attr('data-window-size');

    var edit = true;
    if(windowSize == "") edit = false;

    var html = widgetTools(toolsClass, edit);

    $(el).append(html);



    $('.'+ toolsClass +' .edit').click(function (event)
    {
        event.preventDefault();
        var $el = $(this).parent().parent();
        var windowName = $el.attr('data-widget');

        var widgetId = $el.attr('data-id');

        widgetEditWindows(widgetId, windowSize);
    });


    $('.'+ toolsClass +' .del').click(function (event)
    {
        event.preventDefault();
        var $el = $(this).parent().parent();
        var $widget =  $(this).parent().parent();
        if (confirm("Удалить блок")) {

            var id = $widget.attr("data-id");

            widgetDelete(id, $widget);
        }
        $(this).unbind('click');
        return false;
    });

    $('.'+ toolsClass +' .up').click(function (event)
    {
        event.preventDefault();
        var $el = $(this).parent().parent();

        widgetSetPos($el, 'up');
        //save();
        widgetToolsHide(toolsClass);
        return false
    });

    $('.'+ toolsClass +' .down').click(function (event)
    {
        event.preventDefault();
        var $el = $(this).parent().parent();

        widgetSetPos($el, 'down');
        //save();
        widgetToolsHide(toolsClass);
        return false
    });
}

/**
 * функция отображает панель инструментов виджета
 * @param el - doom объект обвертки виджета
 * @param toolsClass
 */

function widgetToolsShow(el, toolsClass)
{
    widgetToolsCreate(el, toolsClass);

}
/**
 * функция скрывает панель инструментов виджета
 */

function widgetToolsHide(toolsClass)
{

    $('.' + toolsClass).remove();
}


/*
 * Функция добавляет событие, которое срабатывает при наведения курсора на виджет
  * (отображает панель инструментов)
 * */
function widgetToolsBind($el, toolsClass )
{

    $el.hover
    (
        function ()
        {

            widgetToolsShow(this, toolsClass);

        },

        function ()
        {
            widgetToolsHide(toolsClass);

        }

    );
}

function widgetEditWindows(widgetId, windowSize){
    url = '/Widgets/EditForm/widgetId/' + widgetId;

    widgetWindow(url, windowSize)
}

function geWindowsSize(widget){
    return $('#medit-widget-but-' + widget).attr('data-window-size');
}

function editWidget(html, widgetId){
    $widget = getWidget(widgetId);
    $widget.html(html);
    $('#MCMSwindow').MCMSwindow('close');
    /*
    $.scrollTo($widget, {offset:{'top':-200}, 'duration':600, 'onAfter':function(){
        $widget.hide().fadeIn();
    }});
    */

}

/**
 * функция меняет позицию виджета на странице
 * @param $widget - jquery объект виджета
 * @param action - действие up|down
 * @returns {null}
 * @TODO переделать, данные о позиции не обновляются (если не перезагрузить страницу) после изменения в бд
 */
function widgetSetPos($widget, action)
{
    if(action != 'up' && action != 'down') return null;
    if($widget.size() == 0 )                  return null;


    var $widget2;

    if(action == "up")   $widget2    = $widget.prev();
    if(action == "down") $widget2    = $widget.next();

    var widget1Pos = getWidgetPos($widget);
    var widget1Id  = getWidgetId($widget);

    var widget2Pos = getWidgetPos($widget2);
    var widget2Id  = getWidgetId($widget2);


    var url = '/Widgets/SetPos/id1/' + widget1Id;
    url    += '/pos1/'+widget1Pos+'/id2/' + widget2Id+'/pos2/' + widget2Pos;


    $.ajax({
        url: url,
        dataType: 'json',
        success: function(data)
        {

            $el1 = $('[data-id='+data.id1+']');
            $el2 = $('[data-id='+data.id2+']');

            //alert(action);
            if(action == "up")   $el1.insertBefore ($el1.prev());
            if(action == "down") $el1.insertAfter  ($el1.next());

            $el1.attr('data-pos',data.pos1);
            $el2.attr('data-pos',data.pos2);
        }
    });
}

function getWidgetPos($widget){
    return $widget.attr('data-pos');
}

function getWidgetId($widget){
    return $widget.attr('data-id');
}

/**
 * функция удаляет виджет
 * @param id
 * @param widget
 */

function widgetDelete(id, $widget)
{
    var url = '/Widgets/Delete/id/' + id;

    $.ajax({
        url: url,
        success: function()
        {
            $widget.fadeOut(500, function(){this.remove();});
        }
    });
}

function addCssFiles(files){
    for(var i in files) {
        $('head').prepend('<link rel="stylesheet" href="'+files[i]+'" />');
    }
}

function addJavaScriptFiles(files, callback){
    //$('head').append('<script href="'+file+'" ></script>');
    $.getMultiScripts(files).done(function() {
        callback();
    });

}

$.getMultiScripts = function(arr, path) {
    var _arr = $.map(arr, function(scr) {
        return $.getScript( (path||"") + scr );
    });

    _arr.push($.Deferred(function( deferred ){
        $( deferred.resolve );
    }));

    return $.when.apply($, _arr);
};