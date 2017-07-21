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

if (!isset($params['widget']) || empty($params['widget'])) {
    throw new Core\HttpErrorException(404);
}
$widgetName = $params['widget'];

if (!$this->model->isWidget($widgetName)){
    throw new \Exception('Widget ' . $widgetName . ' no found');
}

Core\Mcms::setDialogTheme();

$widget = $this->model->get($widgetName);

$form = new Lib\Form();

$itemsForm = $widget->getFormAdd();

$form->add_items($itemsForm);
$full = $widget->getParameters();

//Заполняем форму
if (is_array($full)) {
    $form->full($full);
}


$html = '';

if(!$form->is_submit()) {
    $html  .= $form->render();
} elseif(!$form->is_valid()) {
    $html .= $form->error();
} else {

    $this->setParams(array('widget'=>$widget, 'widgetName' => $widgetName));
    $this->addFormSaveAction();
}

$this->view->add('BODY', $html);