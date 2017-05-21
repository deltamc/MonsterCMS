<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Lib;

if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$modules = $this->model('MenuItems')->moduleList();
$vars    = array('modules' => $modules);

Lib\View::setBasicTemplate(THEMES_DIALOG_PATH);

$this->view->inc('BODY', 'AddItemModuleList.php', $vars);


