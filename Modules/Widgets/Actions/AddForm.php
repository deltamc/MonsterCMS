<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}
$params = $this->getParams();

if (!isset($params['widget']) || empty($params['widget']) || !isset($params['pageId'])) {
    throw new Core\HttpErrorException(404);
}

$widgetName = $params['widget'];

if (!$this->model->isWidget($widgetName)) {
    throw new \Exception('Widget ' . $widgetName . ' no found');
}

$pageId = (int) $params['pageId'];

Core\Mcms::setDialogTheme();

$widget = $this->model->get($widgetName, $pageId);

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
    $this->setParams(array('widget'=>$widget, 'widgetName' => $widgetName, 'pageId' => $pageId));
    $this->addFormSaveAction();
}

Lib\JavaScript::add($form->js());
$this->view->add('BODY', $html);