<?php
use Monstercms\Core\Lang;
$error = (isset($error)) ? $error : '';

return array(
    array(
        'type' => 'html',
        'html' => '<div class="error">'.$error.'</div>'
    ),
    array
    (
            'name' => "login",
            'type' => 'text',
            'label' => Lang::get('Users.login'),
            'valid' => array
            (
                'required'
            ),
    ),
    array
    (
        'name' => "password",
        'type' => 'password',
        'label' => Lang::get('Users.password'),
        'valid' => array
        (
            'required'
        ),
    ),
    array
        (
            'type' => 'submit',
            'value' => Lang::get('Users.entry')
        ),
);