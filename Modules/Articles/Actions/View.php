<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

/**
 * @var $this Core\ControllerAbstract
 */

$id = $this->getObjectId();

if ($id === 0) {
    throw new Core\HttpErrorException(404);
}

$pageList    = $this->model->pageList($id);

$catalogInfo = $this->model->info($id);

$edit   = false;
$delete = false;
$add    = false;

$pageHead = Core\PageSemantic::init();
$pageHead->setData($this->moduleName, $id);


$title = $pageHead->getTitle();


if(empty($title)) {
    $title = $catalogInfo->name . ' - ' . SITE_NAME;
    $pageHead->setTitle($title);
}



Core\Mcms::setTheme($pageHead->getTheme());




if (User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    $edit   = true;
    $delete = true;
    $add    = true;
}

$vars = array(
    'items'       => $pageList['items'],
    'pagination'  => $pageList['pagination'],
    'textTop'     => $catalogInfo->text_top,
    'textBottom'  => $catalogInfo->text_bottom,
    'catalogName' => $catalogInfo->name,
    'catalogId'   => $id,
    'itemMenuId'  => $catalogInfo->menu_item_id,
    'edit'        => $edit,
    'delete'      => $delete,
    'add'         => $add
);

$this->view->inc('BODY', 'Catalog.php', $vars);
if (User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    $this->view->inc(
        'BODY',
        'ToolsBar.php',
        array(
            'edit' => "/Site/MenuItemEdit/id/{$catalogInfo->menu_item_id}/GoTo/Page",
            'editTitle' => Core\Lang::get('Articles.catalogEdit'),
            'delete' => "/Site/MenuItemDelete/id/{$catalogInfo->menu_item_id}",
            'deleteTitle' => Core\Lang::get('Articles.catalogDelete'),
            'deleteConfirm' => Core\Lang::get('Articles.catalogDeleteConfirm'),
        )
    );
}