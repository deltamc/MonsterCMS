<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;
use Monstercms\Core\User;
use Monstercms\Core\Mcms;
use Monstercms\Core\Lang;
use Monstercms\Lib\Request;
use Monstercms\Lib\Form;

if (!User::isAccess(User::ADMIN)) {
    throw new Core\HttpErrorException(403);
}

$userId = (int) $this->getParam('Id');

if ($userId === 0) {
    throw new Core\HttpErrorException(404);
}

Mcms::setDialogTheme();

$users = $this->model('Users');

$users->delete($userId);

header('Location: /Users');