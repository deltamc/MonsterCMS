/**
 * mEdit v1.0
 */

$(function ()
{
    bind_event_tools();

    $("#medit-widget-window .ok").click
        (
            function()
            {
                $('#widget_form')[0].submit();
            }
        );

    bind_event_element_art_tools($('.medit-element'));
    // Перемещаем параграф из #myDiv1 в #myDiv2
    //$('#myDiv2').append( $('#myDiv1>p') );
    $('body').append( $('#medit-widget-window') );

});

/**
 * Функция добавляет события нажатий на кнопки добавление виджетов
 */
function bind_event_tools()
{
    $("#medit-tools img").bind("click", function(event)
    {
        $this = $(this);

        window_size = $this.attr("date-window-size");
        set_window_size(window_size);
        $('#medit-widget-window').MCMSwindow({autoOpen:true,mask:true});

        widget = $this.attr("date-widget");
        $("#widget_form_error").text("");

        load_widget_form(widget,'add', 0, function ()
        {

        });
        //alert("fsd");
    });
}

/*
* Функция добавляет события при на видении на виджет (отображает панель инструментов)
* */
function bind_event_element_art_tools($el)
{

    $el.hover
    (
        function ()
        {
            $(this).addClass('block-hover');
            element_art_tools_show(this);
        },

        function ()
        {
            $(this).removeClass('block-hover');
            element_art_tools_hide(this);
        }

    );
}

/**
 * Функция задает размер окна редактирование/добавления виджета
 * @param size - Размер окна, в формате WidthxHeight (на пример 200x100)
 */
function set_window_size(size)
{
    var button_bar_height = $('#medit-widget-window .button_bar').height();

    $window = $('#medit-widget-window');
    size = size.split("x");

    width  = size[0];
    height = size[1];
    $window.width(width);
    $window.height(height);
    $("#widget_form").height(height-button_bar_height);
}

/**
 * Функция подгружает форму редактирования/добавления виджета
 * @param widget - имя виджета
 * @param action - действие add|edit
 * @param id     - ид виджета на странице
 * @param complete - функция обратного вызова, вызывается после загрузки формы
 *
 */
function load_widget_form(widget, action, id, complete )
{
    clear_widget_form();

    if(action != "add" &&  action != "edit") return null;

    var this_url = getThisURL();

    url = this_url + "medit=widget_form&widget=" + widget + '&widget_id=' + id;


    $("#widget_form").attr("action", "");

    action_url = this_url + "medit=" + action + "_widget&widget=" + widget + '&widget_id=' + id;


    $('#widget_form').load(url,    function()
    {

        $("#widget_form").attr("action", action_url);

        if (complete !== undefined) complete();
    });

}

/**
 * функция возвращает текущий url страницы
 * @returns {string}
 */
function getThisURL()
{
    var this_url = location.href;
    this_url = this_url.replace(/#.*$/gi,"");
    if(this_url.indexOf('?') + 1) this_url += '&';
    else                          this_url += '?';

    return this_url;
}

/**
 * функция отображает ошибку, если она возникла при заполнении формы
 * @param input_name
 * @param error
 */
function widget_form_error(input_name, error)
{
    $("#widget_form_error").text(error);
    $("#widget_form input[name='"+input_name+"']")[0].focus();
}

/**
 * функция добавляет в конец страницы виджет
 * @param html - html код виджета
 * @param widget - имя виджета
 * @param id - ид виджета на странице
 * @param pos - позиция виджета
 */
function app_end_element_art(html, widget, id, pos)
{
    $('#medit-art').append(element_art_tpl(id, widget, html, pos));
    element_art_tools_hide();

    bind_event_element_art_tools($('#medit-element-'+id));
}

/**
 * функция обновляет виджет
 * @param id - ид виджета
 * @param html - html код виджета
 */
function update_element_art(id, html)
{
    $("#medit-element-" + id).html(html);
}

/**
 * функция удаляет виджет
 * @param id
 * @param widget
 */

function delete_element_art(id, widget)
{
    url = getThisURL()+'medit=delete_widget&id=' + id + '&widget=' + widget;

    $.ajax({
        url: url,
        success: function()
        {
            $("#medit-element-" + id).remove();
        }
     });
}

/**
 * Шаблон обвертки виджета
 * @param id - ид виджета на странице
 * @param widget - имя виджета
 * @param html - код видета
 * @param pos - позиция виджета
 * @returns {string}
 */
function element_art_tpl(id, widget, html, pos)
{
    var idpre = 'medit-element';

    return '<div class="' + idpre + '" date-pos="' + pos + '" date-id="' + id + '" date-widget="' +
            widget + '" id="' + idpre + '-' + id + '">' + html + '</div>';
}

/**
 * функция закрывает окно редактирования/добавления виджета
 */
function close_widget_window()
{
    $('#medit-widget-window').MCMSwindow('close');
    clear_widget_form();
}

/**
 * функция очищает форму редактирования/добавления виджета
 */
function clear_widget_form()
{
    $("#widget_form").attr("action", "");
    $("#widget_form").html("");
}

/**
 * функция отображает панель инструментов виджета
 * @param el - doom объект обвертки виджета
 */

function element_art_tools_show(el)
{
    element_art_tools_create(el);
    posel = $(el).offset();

    $tools = $(".medit-element-art-tools");

    $tools.css('top', posel.top - 25);
    $tools.css('left', posel.left);
    $tools.show();
}
/**
 * функция скрывает панель инструментов виджета
 */
function element_art_tools_hide()
{
    $('.medit-element-art-tools').remove();
}
/**
 * шаблон панели инструментов виджета
 * @param tools_class - имя css класса
 * @returns {string}
 */
function element_art_tools_tpl(tools_class)
{
    html = '<div class="' + tools_class + '">';

    html += '<a href="#" class="edit" >[e]</a>';
    html += '<a href="#" class="del"  >[d]</a>';
    html += '<a href="#" class="up"   >[u]</a>';
    html += '<a href="#" class="down" >[d]</a>';
    html += '</div>';

    return html;
}

/**
 * функция добавляет к виджету панель инструментов
 * @param el - dom объект
 */

function element_art_tools_create(el)
{
    var tools_class = "medit-element-art-tools";

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
/**
 * функция меняет позицию виджета на странице
 * @param $el1 - jquery объект виджета
 * @param action - действие up|down
 * @returns {null}
 */
function element_art_pos($el1, action)
{
    if(action != 'up' && action != 'down') return null;
    if($el1.size() == 0 )                  return null;


    var param1 = element_art_param($el1);

    var $el2;
    if(action == "up")   $el2    = $el1.prev();
    if(action == "down") $el2    = $el1.next();

    var param2  = element_art_param($el2);

    url = getThisURL() + 'medit=pos_widget&id1=' + param1.id + '&id2=' + param2.id
        + '&pos1=' + param1.pos + '&pos2=' + param2.pos;

    //alert(url);

    $.ajax({
        url: url,

        success: function()
        {
            //alert(action);
            if(action == "up")   $el1.insertBefore ($el1.prev());
            if(action == "down") $el1.insertAfter  ($el1.next());
        }
    });
}


/**
 * функция возвращает параметры виджета:
 * id - ид вижета,
 * widget - имя виджета,
 * pos - позиция виджета,
 * wSize - размер окна
 * @param $el - jquery объект виджета
 * @returns {Object|*}
 */
function element_art_param($el)
{
    param             = {};

    param.widget      = $el.attr("date-widget");
    param.id          = $el.attr("date-id");
    param.pos         = $el.attr("date-pos");

    param.wSize       = $("#medit-widget-but-" + param.widget).attr("date-window-size");

    return param;
}

/**
 * функция отображает окно редактирования виджета
 * @param $el - jquery объект виджета
 *
 */
function element_art_edit($el)
{
    param = element_art_param($el);

    load_widget_form(param.widget, 'edit', param.id, function (){});

    set_window_size(param.wSize);

    $('#medit-widget-window').MCMSwindow({autoOpen:true,mask:true});


}

