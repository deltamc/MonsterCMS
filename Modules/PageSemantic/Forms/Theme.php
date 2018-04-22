<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Lang;

return array(
    array
    (
        'type'  => 'tab',
        'label' => Lang::get('PageSemantic.theme'),
        'items' => array(
            array
            (
                'name'           => "menu_item_theme",
                'type'           => 'select',
                'label'          => '',
                'first'          => true,
                'first_text'     => Lang::get('PageSemantic.default'),
                'options'        => \Monstercms\Core\Theme::getNames(),
                'options_attr'   => \Monstercms\Core\Theme::getPreviewsForAttr(),
            ),
        )
    ),
);
