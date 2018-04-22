<?php
defined('MCMS_ACCESS') or die('No direct script access.');

return array
(
    /* Отображать в форме добавления пункта меню*/
    "menu_item"                 => false,
    "menu_item_name"            => '',
    'db_table_menu'       => DB_PREFIX . 'module_site_menu',
    'db_table_menu_items' => DB_PREFIX . 'module_site_menu_items',
    'structure_url'       => 'Site/Structure'
);