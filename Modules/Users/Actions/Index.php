<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;
use Monstercms\Core\User;
use Monstercms\Core\Mcms;
use Monstercms\Core\Lang;

Mcms::setDialogTheme();

if (!User::isAccess(User::ADMIN)) {
    throw new Core\HttpErrorException(403);
}


$users = $this->model('Users');

$usersList = $users->getAll();
$vars = array(
    'users' => $usersList,
    'roles' => $this->config['roles'],
    'thisUserId' => Core\User::getId()

);
$this->view->inc('BODY', 'Users.php', $vars);

