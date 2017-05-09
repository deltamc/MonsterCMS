<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;
use Monstercms\Lib;


class Menu extends Lib\Crud
{
    //private $tree = array();
    //private $db;
    function __construct($config, $db)
    {
        //$this->db = $db;

        $this->config = $config;


        parent::__construct($config['db_table_menu']);
    }

    /**
     * Метод вызывается после добавления новой записи в БД
     * @param $id - ID новой записи
     * @param $formItems - Данные формы
     */
    public function eventAdd($id, $formItems)
    {
        Core\Events::Cell('Site.menuAddEnd', 'void', array($id, $formItems));
    }

    public function eventEdit($id, $form_items)
    {
        Core\Events::Cell('Site.menuEditEnd', 'void', array($id, $form_items));
    }

    public function eventDelete($id, $form_items)
    {
        Core\Events::Cell('Site.menuDeleteEnd', 'void', array($id, $form_items));
    }

    public function menuList()
    {
        $sql = 'SELECT * FROM '.$this->config['db_table_menu'];

        $query = $this->db->query($sql);

        return $query->fetchAll();
    }

    public function menuInfo($id)
    {
        $table = $this->config['db_table_menu'];
        $id = intval($id);
        $menu = $this->db->getObject($table, $id);
        return $menu;
    }
    public function menuDelete($menuId)
    {
        $menuId = intval($menuId);
        $table   = $this->config['db_table_menu'];
        $this->db->delete($table, $menuId);

    }
}