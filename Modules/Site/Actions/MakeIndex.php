<?php namespace Monstercms\Modules\Site;
defined('MCMS_ACCESS') or die('No direct script access.');
/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if ($this->getObjectId() === 0) {
    throw new Core\HttpErrorException(404);
}

if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

$menuItemId = intval($this->getObjectId());

$this->model('MenuItems')->setIndex($menuItemId);

Lib\Header::location($this->structureUrl);

