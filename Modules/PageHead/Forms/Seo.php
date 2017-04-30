<?php
use \Monstercms\Core as Core;
use \Monstercms\Core\Lang;

$form = array
(
    array(
        'type'  => 'tab',
        'label' => 'SEO',
        'items' => array
        (
            array
            (
                'name'  => 'menu_item_seo_title',
                'type'  => 'text',
                'label' => Lang::get('PageHead.seoTitle'),
                'help'  => Lang::get('PageHead.seoTitleHelp')
            ),
            array
            (
                'name'  => 'menu_item_seo_description',
                'type'  => 'textarea',
                'label' => Lang::get('PageHead.seoDescription'),
                'help'  => '',
                'maxlength' => 150
            ),
            array
            (
                'name'  => 'menu_item_seo_keywords',
                'type'  => 'text',
                'label' =>Lang::get('Page.seoKeywords')
            ),
            array
            (
                'name'  => 'menu_item_seo_canonical',
                'type'  => 'text',
                'label' => Lang::get('PageHead.seoCanonical'),
            ),
            array
            (
                'name' => "menu_item_seo_noindex",
                'type' => 'checkbox',
                'check_value' => '1',
                'no_check_value' => '0',
                'label' => Lang::get('PageHead.seoNoindex'),
                'help'  => Lang::get('PageHead.seoNoindexHelp'),

            )
        )
    ),
);

return $form;