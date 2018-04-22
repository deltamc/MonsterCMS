<?php defined('MCMS_ACCESS') or die('No direct script access.');?>
var html = $('#widget-html').html();

var widget = '<?=$widget?>';
var widgetId = '<?=$id?>';
var widgetPos = '<?=$pos?>';
<?php if(is_array($css) && !empty($css)):?>

        top.addCssFiles(<?=json_encode($css)?>);

<?endif?>
<?php if(is_array($js) && !empty($js)):?>
top.addJavaScriptFiles(<?=json_encode($js)?>, function(){
    top.addWidget(html, widget, widgetId, widgetPos);
});
<?php else:?>

    top.addWidget(html, widget, widgetId, widgetPos);
<?endif?>



