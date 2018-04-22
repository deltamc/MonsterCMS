<?php defined('MCMS_ACCESS') or die('No direct script access.');?>
var html = $('#widget-html').html();
var widget = '<?=$widget?>';
var widgetId = '<?=$id?>';
top.editWidget(html, widgetId);