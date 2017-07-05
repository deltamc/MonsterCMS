<?php
use \Monstercms\Core\Lang;

return array(
    array
    (
        'type'  => 'tab',
        'label' => Lang::get('PageHead.theme'),
        'items' => array(
            array
            (
                'name'           => "menu_item_theme",
                'type'           => 'select',
                'label'          => '',
                'first'          => true,
                'first_text'     => Lang::get('PageHead.default'),
                'options'        => \Monstercms\Core\Theme::getNames(),
                'options_attr'   => \Monstercms\Core\Theme::getPreviewsForAttr(),
            ),
        )
    ),
);
