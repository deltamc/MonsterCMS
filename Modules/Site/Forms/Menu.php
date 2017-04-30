<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Lang;

return array
(
    array
    (
        'name'  => "name",
        'type'  => 'text',
        'label' => Lang::get('Site.nameMenu'). '*:',
        'valid' => array
        (
            'required'
        )
    ),
    array
    (
        'type' => 'submit',
        'value' => Lang::get('Page.save')
    )

);