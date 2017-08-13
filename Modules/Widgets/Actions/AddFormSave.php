<?php namespace Monstercms\Modules\Widgets;
use \Monstercms\Core;
use \Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

$params = $this->getParams();
if(!isset($params['widget']) || !isset($params['pageId'])) {
    throw new Core\HttpErrorException(404);
}

$widget     = $params['widget'];
$widgetName = $params['widgetName'];
$pageId     = $params['pageId'];
$newWidget = $this->model->add($widget, $_POST, $pageId);
$cache = $newWidget['cache'];

$vars = array(
    'cache' => $newWidget['cache'],
    'id'    => $newWidget['id'],
    'widget' => $newWidget['widget'],
    'pos' => $newWidget['pos'],
);
Core\Mcms::setDialogTheme();
$this->view->add('BODY', '<div id="widget-html" style="display: none">' . $cache .'</div>');
$js = $this->view->get('AddWidgetJs.php', $vars);

Lib\Javascript::add($js);

