<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Lib;
use \Monstercms\Core\Mcms;

//Проверяем права пользователя
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

//Передан ли ид меню
if ($this->getObjectId() === 0) {
    throw new Core\HttpErrorException(404);
}
$action = 'edit';

$id = $this->getObjectId();

//Шаблон
Core\Mcms::setDialogTheme();

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Site.editMenuTitle'));

$formItems = include($this->modulePath . 'Forms' . DS . 'Menu.php');

//Получаем данные формы с других модулей
$formItems = Core\Events::eventsForm($formItems);

//Заполняем элементы форм получив массив из других модулей

$full = Core\Events::cell('Site.menuEditFullForm', 'array_merge',
    array('menuId' => $id));


$this->view->add('BODY', $this->model('Menu')->edit($id, $formItems, null, $full));

