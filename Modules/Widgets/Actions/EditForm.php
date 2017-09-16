<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!Core\User::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$params = $this->getParams();

if (!isset($params['widgetId'])) {
    throw new Core\HttpErrorException(404);
}

$widgetId = $params['widgetId'];

Core\Mcms::setDialogTheme();

$widgetInfo = $this->model->getInfoById($widgetId);
$widget = $this->model->get($widgetInfo['widget']);

$form = new Lib\Form();

$itemsForm = $widget->getFormAdd();

$form->add_items($itemsForm);
$full = $widget->getParameters();

//Заполняем форму
if (is_array($full)) {
    $form->full($full);
}

$form->full($widgetInfo['options']);

$html = '';

if(!$form->is_submit()) {
    $html  .= $form->render();
} elseif(!$form->is_valid()) {
    $html .= $form->error();
} else {

    $this->setParams(
        array(
            'widgetName' => $widgetInfo['widget'],
            'widgetId'   => $widgetInfo['id'],
            'widget'     => $widget,
        )
    );
    $this->editFormSaveAction();
}

$this->view->add('BODY', $html);