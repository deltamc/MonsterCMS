<?php namespace Monstercms\Modules\Site;
/**
 * @var $this Core\ControllerAbstract
 */

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if ($this->getObjectId() === 0){
    throw new Core\HttpErrorException(404);
}

if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

$id = $this->getObjectId();
$menu_item = $this->model('MenuItems')->menuItemInfo($id);

/*if link*/
if ($menu_item->url_id === 0 && !empty($menu_item->link_url)) {
    $url = $menu_item->link_url;
} else {
    $url = '/'.$menu_item->sematic_url . URL_SEMANTIC_END;
}

Lib\Header::location($url, 'top');