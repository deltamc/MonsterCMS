<?php namespace Monstercms\Modules\Widgets;
use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

$params = $this->getParams();

if(!isset($params['widget']) || !isset($params['pageId'])) {
    throw new Core\HttpErrorException(404);
}

Core\Mcms::setDialogTheme();

$widget     = $params['widget'];
$pageId     = $params['pageId'];
$newWidget = $this->model->add($widget, $_POST, $pageId);

$jsFiles = $widget->getJavaScript();
$cssFiles = $widget->getCSS();

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
    'cache' => $newWidget['cache'],
    'id'    => $newWidget['id'],
    'widget' => $newWidget['widget'],
    'pos' => $newWidget['pos'],
    'js'  => $jsFiles,
    'css'  => $cssFiles,
);

$this->view->add('BODY', '<div id="widget-html" style="display: none">' . $cache .'</div>');
$js = $this->view->get('AddWidgetJs.php', $vars);

Lib\Javascript::add($js);

