<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Core\Mcms;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!Core\User::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$action = 'add';

//базовый шаблон
Core\Mcms::setDialogTheme();

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Site.addMenuTitle'));

$formItems = include($this->modulePath . 'Forms' . DS . 'Menu.php');

//Получаем данные формы с других модулей
$formItems = Core\Events::eventsForm($formItems);

//Выводим форму
$this->view->add('BODY', $this->model('Menu')->add($formItems));

