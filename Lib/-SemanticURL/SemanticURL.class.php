<?
namespace  Monstercms\Lib;

class SemanticURL
{

    public $dbTable;
    public $db;

    function __construct($db, $db_table)
    {
        $this->db = $db;
        $this->dbTable = $db_table;
    }


    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->dbTable . " WHERE id=" . intval($id) );
    }

    public function add($url, $module, $action, $object_id, array $options = array() )
    {

        $options = serialize($options);

        $list = array
        (
            'id'        => 'NULL',
            'options'   => $options,
            'module'    => $module,
            'action'    => $action,
            'url'       => $url,
            'object_id' => $object_id
        );

        $result = $this->db->insert($list, $this->dbTable);

        if ($result == 'true') return $this->db->insertId();
        else return null;
    }

    public function update($id, $url, $module, $action, $object_id, array $options = array())
    {
        $id = intval($id);


        $list = array
        (
            'url'     => $url,
            'action'  => $action,
            'module'  => $module,
            'object_id'=> $object_id
        );
        if (!empty($options)){
            $options = serialize($options);
            $list['options'] = $options;
        }

        $result = $this->db->update($list, $this->dbTable, '`id`=' . $id);


        if ($result == 'true') return $this->db->insertId();
        else return null;
    }

    public function info($url)
    {
        $url     = $this->db->escape_string($url);
        $url_obj = new Object($this->dbTable, $url, 'url');

        if(!isset($url_obj->id)) return null;

        $options = unserialize($url_obj->options);

        $out = array();
        $out['id_url'] = $url_obj->id;
        $out['module'] = $url_obj->module;
        $out['action'] = $url_obj->action;
        $out['object_id'] = $url_obj->object_id;

        if(!empty($options)) $out = array_merge($out, $options);

        return $out;
    }

    public function getUrl($url_id)
    {
        $url_id     = intval($url_id);

        $url_obj = new Object($this->dbTable, $url_id);

        return $url_obj->url;
    }


   /*
    private $url = '';
    public $dbTable = 'urls';
    private $result = null;
    private $db;

    function __construct($url, $dbTable = 'urls')
    {
        global $DB;
        $this->db = $DB;
        $this->dbTable = $dbTable;

        $this->url = mysql_real_escape_string($url);
        return $this;
    }

    public function getOptions()
    {
        if (!$this->is()) return null;
        return $this->result['options'];
    }

    public function getModule()
    {
        if (!$this->is()) return null;
        return $this->result['module'];
    }

    public function getId()
    {
        if (!$this->is()) return null;
        return $this->result['id'];
    }

    public function getUrl()
    {
        if (!$this->is()) return null;
        return $this->result['module'];
    }

    public function is()
    {
        if (is_null($this->result) && !$this->result()) return false;
        return true;
    }

    private function result()
    {
        $where = 'url = "' . $this->url . '"';
        //if(is_int($this->url)) $where = 'id = "'.$this->url.'"';

        $sql = 'SELECT * FROM `' . $this->dbTable . '` WHERE ' . $where . ' LIMIT 1';

        $result = $this->db->query($sql);

        if (!$this->db->numRows($result)) return false;

        $row = $this->db->fetchArray($result);

        $this->result['id'] = $row['id'];
        $this->result['url'] = $row['url'];
        $this->result['options'] = unserialize($row['options']);

        return true;
    }

    public function insert($options = array())
    {
        $options = serialize($options);
        $options = mysql_real_escape_string($options);

        $sql = 'INSERT INTO `' . $this->dbTable . '` (`id`, `url`, `options`)
                VALUE(NULL, "' . $this->url . '", "' . $options . '" )';


        $result = $this->db->query($sql);
        if ($result == 'true') return $this->db->insertId();
        else return null;
    }

    public function delete()
    {

        $where = 'url = "' . $this->url . '"';

        $sql = 'DELETE FROM `' . $this->dbTable . '` WHERE ' . $where . ' LIMIT 1';

        $result = $this->db->query($sql);

        if ($result == 'true') return true;
        else return false;
    }



    public static function is_url($url,  $param=array())
    {
        $route = new SemanticURL($url);
        $url_id = $param[1]['url_id'];
        if(!$route->is()) return true;

        //��������������
        if(!is_null($url_id))
        {
            //���� ��� �������
            if($route->getId() != $url_id) return false;
            //if(!$route->is() && $route->getId() != $url_id) return false;
        }
        elseif($route->is()) return false;

        return true;
    }

*/
}
?>