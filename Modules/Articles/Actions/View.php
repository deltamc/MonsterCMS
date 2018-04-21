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

Lib\Css::add('/' . Lib\Path::dsUrl($this->modulePath) . 'Css/Admin.css');

$edit   = false;
$delete = false;
$add    = false;

$pageHead = Core\PageSemantic::init();
$pageHead->setData($this->moduleName, $id);


$title = $pageHead->getTitle();
$canonical = $pageHead->getCanonical();


if(empty($title)) {
    $title = $catalogInfo->name . ' - ' . SITE_NAME;
    $pageHead->setTitle($title);
}


Lib\Path::thisUrlWithoutGet();
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

$varsCell = Core\Events::cell
(
    'Articles.articleItemTplVars',
    'array_merge',
    $vars
);

if (!empty($varsCell)) {
    $vars = array_merge($varsCell, $vars);
}

$body = Core\Events::cell(
    $this->moduleName . '.top',
    'string',
    $vars
);

$this->view->add('BODY', $body);

$this->view->inc('BODY', 'Catalog.php', $vars);

$body = Core\Events::cell(
    $this->moduleName . '.bottom',
    'string',
    $vars
);

$this->view->add('BODY', $body);


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
            'add' => '/Articles/Add/MenuItem/' . $catalogInfo->menu_item_id,
            'addTitle' =>  Core\Lang::get('Articles.articleAdd'),
        )
    );
}