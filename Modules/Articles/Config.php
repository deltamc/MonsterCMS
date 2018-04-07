<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;

return array
(
    'module'      => 'Articles',
    'db_catalog'  => DB_PREFIX . 'module_articles_catalog',
    'db_articles' => DB_PREFIX . 'module_articles',
    //количество статьей на старнице
    'number_on_page' => 10,
    'pagination_link_tpl'      => '<a href="{URL}" >{PAGENUM}</a>',
    'pagination_link_tpl_this' => '<span class="this">{PAGENUM}</span>',
    'order_by' => Monstercms\Modules\Articles\Model::CREATE_DESC,
);