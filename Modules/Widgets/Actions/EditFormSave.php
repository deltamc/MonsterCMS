<?php namespace Monstercms\Modules\Widgets;
use \Monstercms\Core;
use \Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

$params = $this->getParams();
if(!isset($params['widget']) || !isset($params['widgetId'])) {
    throw new Core\HttpErrorException(404);
}

$widget     = $params['widget'];
//$widgetName = $params['widgetName'];
$widgetId     = $params['widgetId'];

$newWidget = $this->model->edit($widget, $_POST, $widgetId);
$cache = $newWidget['cache'];

$vars = array(
    'cache' => $newWidget['cache'],
    'id'    => $newWidget['id'],
    'widget' => $newWidget['widget'],
    'class' => $newWidget['css_class']
);
Core\Mcms::setDialogTheme();
$this->view->add('BODY', '<div id="widget-html" style="display: none">' . $cache .'</div>');
$js = $this->view->get('EditWidgetJs.php', $vars);

Lib\Javascript::add($js);

