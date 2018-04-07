<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

//проверяем, права доступа
if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

if (!$this->isParam('id')) {
    throw new Core\HttpErrorException(404);
}

$params = $this->getParams();

$id = (int) $params['id'];
$this->model->delete($id);