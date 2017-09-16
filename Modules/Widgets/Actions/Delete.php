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

if (!$this->isParam('id')) {
    throw new Core\HttpErrorException(404);
}

$params = $this->getParams();

$id = (int) $params['id'];
$this->model->delete($id);