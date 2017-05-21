<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;

return array
(
    'module' => 'Page',
/*
    "menu_items" => array(
        'page_text' => array(
            //экшен при переходе из пункта меню
            "action_link_menu"                 => 'view',

            //"menu_item"                 => true,
            "menu_item_name"            => Core\Lang::get('Page.menuItemName'),

            "hidden_form_items"  => array("menu_item_url"),
            "menu_item_icon"     => "",
            "menu_item_add_heading"     => Core\Lang::get('Page.menuItemHeading'),
        ),

        'page_text2' => array(

            "action_link_menu"                 => 'view',



            "menu_item"                 => true,
            "menu_item_name"            => "тест",

            "hidden_form_items"  => array("menu_item_url"),

            "menu_item_icon"     => "",
            "menu_item_add_heading"     => Core\Lang::get('Page.menuItemHeading'),
        ),

    ),

*/

    "image_path"                    => UPLOAD_ROOT . DS. 'Page',
    "db_table"                      => DB_PREFIX."module_page",
    "db_table_images"               => DB_PREFIX."module_page_images",
    "db_article_widgets"            => DB_PREFIX."module_page_widgets",


    "module_path"                   => "modules" . DS .  "page",
    "images_width"                  =>  570,
    "images_height"                 =>  600,
    'medit'                         => array
    (
        'position'              => 'left',
        'db_table_elements_art' => DB_PREFIX.'module_page_widgets',
        'path_js'               => '/lib/medit/javascript',
        'widgets_dir'           => LIB_DIR.'/medit/widgets',
        'theme'                 => 'classic',
        'theme_dir'             => '/lib/medit/themes',
        'templates_dir'         => LIB_DIR.'/medit/templates',
        'callback'              => array
        (
            'add'    => 'add_widget',
            'edit'   => 'edit_widget',
            'delete' => 'delete_widget',
        ),
        'widgets'               => array
        (
            'images' => array
            (
                'dbRow'      => 'image',  // имя столбеца в таблице бд с именем изображения
                'dbTable'    => DB_PREFIX."module_page_images", // таблица в бд
                'dbId_group' => 'widgets_id',     // имя столбеца в таблице бд с идифекатором группы изображений
                'path'       => UPLOAD_ROOT . DS. 'page',  // путь для загрузки изображений
                'width'      => 570,      // ширена
                'height'     => 600,      // высота

            ),
            'feedback' => array
            (
                'key'         => "r3wdf5668)(7678@sa*&fdWDS",
                'send_script' => '/index.php?action=email_send&module=page'
            ),
            'video' => array
            (
                'height' => 360,
                'width'  => 640
            ),
            'docfiles' => array
            (
                'path' => $_SERVER['DOCUMENT_ROOT'].'/files',
                'path_link' => '/files',
                'types' => 'zip, doc, docx, pdf, jpg, jpeg, gif, png, ppt'
            )
        )
    )
);