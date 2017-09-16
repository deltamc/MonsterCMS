<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Core\Lang;
use \Monstercms\Lib;
use \Monstercms\Lib\View;
use \Monstercms\Lib\JavaScript;

if (!Core\User::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

Core\Mcms::setDialogTheme();

$this->view->add('TITLE', Lang::get('Site.structure'));
$this->view->inc('BODY', 'StructureTools.php');



$menuList = $this->model('Menu')->menuList();

$vars = array
(
    'menu_list'   => $menuList,
    'moduleName' => $this->moduleName
);

JavaScript::add(SITE_URL . '/JavaScript/jquery.ztree.all.min.js');
JavaScript::add(SITE_URL . '/Modules/Site/JavaScript/site_tree.js');

foreach ($menuList as $menu) {
    JavaScript::add('site_tree_init(' . $menu['id'] . ');');
}
$this->view->inc('BODY', 'MenuList.php', $vars);



