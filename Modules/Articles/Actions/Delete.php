<?php namespace Monstercms\Modules\Articles;

defined('MCMS_ACCESS') or die('No direct script access.');


use \Monstercms\Core\Module;
use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

$id = $this->getObjectId();


$out = array(
    'id' => $id,
    'delete' => false,
    'message' => ''
);

if (empty($id)) {
    $out['message'] = 'Empty ID';
    print json_encode($out);
    exit();
}

$this->model->deleteArticle($id);
$out['delete'] = true;


print json_encode($out);
exit();

