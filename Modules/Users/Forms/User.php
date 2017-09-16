<?php
use Monstercms\Core\Lang;

return array(
    array
    (
        'name' => "login",
        'type' => 'text',
        'label' => Lang::get('Users.login'),
        'valid' => array
        (
            'required',
            'call' => array(
                array
                (
                    '\Monstercms\Core\User::isUserByLogin',
                    array
                    (
                         (isset($userId)) ? $userId: null
                    ),
                ),
                Lang::get('Users.loginInvalid')
            ),
        ),
    ),

    array
    (
        'name' => "password",
        'type' => 'text',
        'label' => Lang::get('Users.password'),
        'valid' => array
        (
            (!isset($userId)) ? 'required': null,
        ),
    ),
    array
        (
            'name' => "role",
            'type' => 'select',
            'label' => Lang::get('Users.role'),
            'options' => $this->config['roles'],

        ),
    array
        (
            'type' => 'submit',
            'value' => Lang::get('Users.save'),
        ),
);