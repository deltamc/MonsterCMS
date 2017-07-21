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

        var widget = $this.attr("date-widget");

        if($this.attr("date-window_add") == "") {
            addWidgetNoWindows(widget);
        } else {
            var windowSize = $this.attr("date-window-size");
            addWidgetWindows(widget, windowSize);
        }
    });
}

function addWidgetNoWindows(widget) {

}

function addWidgetWindows(widget, windowSize)
{
    url = '/Widgets/AddForm/widget/' + widget;

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