<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$params = $this->getParams();

if (!isset($params['widget']) || empty($params['widget']) || !isset($params['pageId'])) {
    throw new Core\HttpErrorException(404);
}

$widgetName = $params['widget'];

if (!$this->model->isWidget($widgetName)){
    throw new \Exception('Widget ' . $widgetName . ' no found');
}

$pageId    = (int) $params['pageId'];
$widget    = $this->model->get($widgetName, $pageId);
$newWidget = $this->model->add($widget, $_POST, $pageId);
$jsFiles   = $widget->getJavaScript();
$cssFiles  = $widget->getCSS();

if (!is_array($jsFiles) && !empty($jsFiles)) {
    $jsFiles = array($jsFiles);
}

if (!is_array($cssFiles)  && !empty($cssFiles)) {
    $cssFiles = array($cssFiles);
}

$vars = array(
    'html' => $newWidget['cache'],
    'id'    => $newWidget['id'],
    'widgetName' => $newWidget['widget'],
    'pos' => $newWidget['pos'],
    'class' => $newWidget['css_class'],
    'windowSize' => $widget->getEditFormWindowSize()

);
$cache = $this->view->get("Wrap.php",$vars);





$vars = array(
    'html' => $cache,
    'id'    => $newWidget['id'],
    'widget' => $newWidget['widget'],
    'pos' => $newWidget['pos'],
    'js'  => $jsFiles,
    'css'  => $cssFiles,

);

print json_encode($vars);
exit();

/*
$this->view->add('BODY', '<div id="widget-html" style="display: none">' . $cache .'</div>');
$js = $this->view->get('AddWidgetJs.php', $vars);

Lib\Javascript::add($js);
*/