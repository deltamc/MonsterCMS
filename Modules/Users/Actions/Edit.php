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

$formElements = include($this->modulePath . 'Forms' . DS . 'User.php');

$form = new Form();

$user = $users->getById($userId);


$form->add_items($formElements);

$form->full($user);

$html  = '';

if(!$form->is_submit())
{
    $html  = $form->render();
}
elseif($form->is_valid())
{
    $update = array(
        'login' => Request::getPost('login'),
        'role'  => Request::getPost('role'),
        'password' => Request::getPost('password'),
    );
    $users->update($update, $userId);

    header('Location: /Users');
}
else
{
    $html .= $form->error();
}


$this->view->add('BODY',$html);