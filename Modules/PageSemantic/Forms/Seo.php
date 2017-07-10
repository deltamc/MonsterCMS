<?php
use \Monstercms\Core;
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
                'label' => Lang::get('PageSemantic.seoTitle'),
                'help'  => Lang::get('PageSemantic.seoTitleHelp')
            ),
            array
            (
                'name'  => 'menu_item_seo_description',
                'type'  => 'textarea',
                'label' => Lang::get('PageSemantic.seoDescription'),
                'help'  => '',
                'maxlength' => 150
            ),
            array
            (
                'name'  => 'menu_item_seo_keywords',
                'type'  => 'text',
                'label' =>Lang::get('PageSemantic.seoKeywords')
            ),
            array
            (
                'name'  => 'menu_item_seo_canonical',
                'type'  => 'text',
                'label' => Lang::get('PageSemantic.seoCanonical'),
            ),
            array
            (
                'name' => "menu_item_seo_noindex",
                'type' => 'checkbox',
                'check_value' => '1',
                'no_check_value' => '0',
                'label' => Lang::get('PageSemantic.seoNoindex'),
                'help'  => Lang::get('PageSemantic.seoNoindexHelp'),

            )
        )
    ),
);

return $form;