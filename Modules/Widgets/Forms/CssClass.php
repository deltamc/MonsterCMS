<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Lang;

return array
(
    'name' => "css_class",
    'type' => 'text',
    'label' => 'CSS class:',
    'valid' => array
    (
        'pattern' => array(
            '^[\w\d-_ ]*$',
            Lang::get('Widgets.unacceptableSymbols')
        ),
    ),
);