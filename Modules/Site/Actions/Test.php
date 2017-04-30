<?php namespace Monstercms\Modules\Site;

use \Monstercms\Core as Core;
use \Monstercms\Lib as Lib;

$tree = new Lib\Tree($this->db, $this->config['db_table_menu_items'], 'menu_id=1');

$tree->update(13, array('menu_id'=>6, 'css_class'=>'css'));

