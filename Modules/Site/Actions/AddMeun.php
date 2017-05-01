<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Lib;

if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

Lib\View::setBasicTemplate(THEMES_DIALOG_PATH);

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Site.addMenuTitle'));

$form_items = include($this->modulePath . 'Forms' . DS . 'Menu.php');

$this->view->add('BODY', $this->model('Menu')->add($form_items));