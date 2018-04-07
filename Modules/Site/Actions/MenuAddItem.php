<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

$modules = $this->model('MenuItems')->moduleList();
$vars    = array('modules' => $modules);

Core\Mcms::setDialogTheme();

$this->view->inc('BODY', 'AddItemModuleList.php', $vars);


