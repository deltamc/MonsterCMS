var html = $('#widget-html').html();

var widget = '<?=$widget?>';
var widgetId = '<?=$id?>';
var widgetPos = '<?=$pos?>';
<?php if(is_array($js) && !empty($js)):?>
    <?php foreach ($css as $item ):?>
        top.addCssFile('<?=$item?>');
    <?endforeach?>
<?endif?>
<?php if(is_array($js) && !empty($js)):?>
top.addJavaScriptFiles(<?=json_encode($js)?>, function(){
    top.addWidget(html, widget, widgetId, widgetPos);
});
<?php else:?>

    top.addWidget(html, widget, widgetId, widgetPos);
<?endif?>



