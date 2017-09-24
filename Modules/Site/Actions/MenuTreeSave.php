<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if (empty($_POST['tree']) || !is_array($_POST['tree'])){
    throw new Core\HttpErrorException(404);
}
if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

$values = array();

foreach($_POST['tree'] as $node)
{
    $values[] = array
    (
        intval($node[0]),
        intval($node[1]),
        intval($node[2]),
        intval($node[3])
    );
}

$this->model('MenuItems')->menuUpdate($values);
exit();