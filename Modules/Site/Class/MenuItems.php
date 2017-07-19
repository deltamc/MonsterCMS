<?php namespace Monstercms\Modules\Site;

use Monstercms\Core as Core;
use Monstercms\Lib  as Lib;


/*TODO убрать наследие crud*/

class MenuItems extends Lib\Crud
{
    private $tree = null;
    private $modules = null;

    //private $tree = array();
    //private $db;
    function __construct($config, $db)
    {
        //$this->db = $db;

        $this->config = $config;


        parent::__construct($config['db_table_menu']);
    }

    public function menuItemsList($menu_id)
    {

        $menu_id = intval($menu_id);
        $t = $this->config['db_table_menu_items'];
        $t_url = DB_TABLE_URL;
        $sql = 'SELECT mi.*,url.url as sematic_url, mi.url as link_url
                FROM `'.$t.'` mi LEFT OUTER JOIN `'.$t_url.'` url ON mi.url_id = url.id
                WHERE menu_id = '.$menu_id.' ORDER BY `pos`';
        //print  $sql;
        $result = $this->db->query($sql);

        return $result->fetchAll();
    }

    public function menuItemInfo($id)
    {
        /*
        $table = $this->config['db_table_menu_items'];

        $obj = new Lib\Object($table, intval($id));
        */
        $id = intval($id);
        $t = $this->config['db_table_menu_items'];
        $t_url = DB_TABLE_URL;

        $sql = 'SELECT mi.*,url.url as sematic_url, mi.url as link_url
                FROM `'.$t.'` mi LEFT OUTER JOIN `'.$t_url.'` url ON mi.url_id = url.id
                WHERE mi.id = '.$id;

        $result = $this->db->query($sql);
        return $result->fetchObject();
    }




    public function moduleList()
    {
        $out = array();
        $this->modules = null;

        $items = Core\Events::cell(
            'Site.menuItemAddModuleList',
            'array'
        );


        if (!is_array($items) || empty($items)) {
            return $out;
        }

        $default = array(
            'hiddenFormItems'    => array(),
            'menuItemIcon'       => '',

            'menuItemName'       => ''
        );

        $necessarily = array(
            'module',
            'itemType',

        );

        foreach ($items as $item) {
            if (!self::isNecessarily($item, $necessarily)) {
                continue;
            }
            $out[] = $item;
            $this->modules[$item['module']][$item['itemType']] = array_merge($default, $item);
        }

        return $out;
    }

    public function getModuleInfo($module, $itemType)
    {
        if (!$this->modules) {
            $this->moduleList();
        }

        if (empty($this->modules) || !isset($this->modules[$module][$itemType])) {
            return null;
        }

        return $this->modules[$module][$itemType];
    }

    /**
     * Метод проверяет ассоциативный массив на наличии в нем обязательных ключей
     * @param array $arr - ассоциативный массив
     * @param array $necessarily - массив с ключами
     * @return bool
     */
    private static function isNecessarily(array $arr, array $necessarily)
    {
        foreach ($necessarily as $val) {
           if (!isset($arr[$val])) {
                return false;
           }
        }

        return true;
    }

    public function setIndex($itemMenuId)
    {
        $table = $this->config['db_table_menu_items'];
        $this->db->update(array('index'=>0), $table, '1=1');
        $this->db->update(array('index'=>1), $table, $itemMenuId);
    }

    /**
     * returns Id index page
     * if not found, return 0
     * @return int
     */
    public function getIndexId()
    {
        $table = $this->config['db_table_menu_items'];

        $sql = 'SELECT `id` FROM `'.$table.'` WHERE `index`=1';

        $result = $this->db->query($sql);
        $row = $result->fetch();

        if(isset($row['id'])) return $row['id'];

        return 0;
    }

    public function getIndexObject()
    {

        $t = $this->config['db_table_menu_items'];
        $tUrl = DB_TABLE_URL;

        $sql = 'SELECT mi.*,url.url as sematic_url, mi.url as link_url
                FROM `'.$t.'` mi LEFT OUTER JOIN `'.$tUrl.'` url ON mi.url_id = url.id
                WHERE mi.index = 1';

        $result = $this->db->query($sql);

        return $result->fetchObject();
    }

    public function menuItemAdd($data)
    {
        $table = $this->config['db_table_menu_items'];

        $result = $this->db->insert($data, $table);

        return $this->db->lastInsertId();

    }

    public function menuItemEdit($data, $id)
    {
        $table = $this->config['db_table_menu_items'];
        $this->db->update($data, $table, intval($id));
        return $this->db->lastInsertId();
    }

    public function maxPos($menu_id)
    {
        $menu_id = intval($menu_id);

        $table   = $this->config['db_table_menu_items'];

        $sql = 'SELECT MAX(pos)+1 FROM `' . $table . '` WHERE menu_id=' . $menu_id;

        $result = $this->db->query($sql);

        $pos = $result->fetch();

        return $pos[0];
    }

    public function menuUpdate($values)
    {
        $fields = array('id', 'parent_id', 'pos', 'child_count');
        $this->db->insertOrUpdate($fields, $values, $this->config['db_table_menu_items']);
    }

    public function isChilds($menu_id, $parent=0)
    {
        $menu_id = intval($menu_id);
        $parent  = intval($parent);

        $table   = $this->config['db_table_menu_items'];

        $sql = 'SELECT * FROM ' . $table . ' WHERE menu_id=' . $menu_id;
        if($parent != 0) $sql .= ' AND parent_id=' . $parent;

        $result = $this->db->query($sql);

        $row = $result->fetch();
        if(!empty($row)) return true;


        return false;


    }


    public function menuItemDelete($id)
    {
        $id = intval($id);
        $table   = $this->config['db_table_menu_items'];

        //$sql = 'DELETE FROM '.$table.' WHERE id='.$id;
        $this->db->delete($table, $id);

    }

    public function itemMoveInMenu($id, $menu_id_old, $menu_id_new)
    {
        $menu_id_old = intval($menu_id_old);
        $menu_id_new = intval($menu_id_new);
        $id = intval($id);

        $table   = $this->config['db_table_menu_items'];

        $tree = new Lib\Tree($this->db, $table, 'menu_id='.$menu_id_old);

        $tree->update($id, array('menu_id'=>$menu_id_new));
    }



    private function getTree($menu_id)
    {
        $table   = $this->config['db_table_menu_items'];
        $table_url = DB_TABLE_URL;
        $menu_id = intval($menu_id);

        $tree = new Lib\Tree($this->db, $table, 'menu_id=' . $menu_id);
        $tree->sql = 'SELECT `'.$table.'`.*, `'.$table_url.'`.url as sematic_url, `'.$table_url.'`.url as link_url
                FROM `'.$table.'` LEFT OUTER JOIN `'.$table_url.'` ON `'.$table.'`.url_id = `'.$table_url.'`.id ';
        return $tree;

    }

    public function getMenuItems($menu_id, $parent_id = 0)
    {
        if(is_null($this->tree)) $this->tree = $this->getTree($menu_id);

        $items = $this->tree->childNodes($parent_id, false);
        $out = array();
        foreach($items as $id => $item)
        {
            $out[$id]['name'] = $item['name'];

            $out[$id]['css']= $item['css_class'];
            $out[$id]['target']= $item['target'];
            $out[$id]['hide']  = $item['hide'];
            $out[$id]['child_count']  = $item['child_count'];


            /*if link*/
            if($item['url_id'] == 0 && !empty($item['link_url']))
            {
                $out[$id]['url'] = $item['link_url'];
            }
            /*if index*/
            else if($item['index'] == 1)
            {
                $out[$id]['url'] = '/';
            }
            /*if not index and not link*/
            else
            {
                $out[$id]['url'] = '/' . $item['sematic_url'] . URL_SEMANTIC_END;
            }
        }

        return $out;
    }

}