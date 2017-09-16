<?php
use Monstercms\Core\Lang;
use Monstercms\Core\User;

return array(
    'redirectAfterEntry' => '/',
    'redirectAfterLogOut' => '/',
    'roles' => array(
        User::ADMIN            => Lang::get("Users.roleAdmin"),
        User::USER             => Lang::get("Users.roleUser"),
        User::DEMO             => Lang::get("Users.roleDemo"),
        User::CONTENT_MANAGER  => Lang::get("Users.roleContentManager"),
    )
);