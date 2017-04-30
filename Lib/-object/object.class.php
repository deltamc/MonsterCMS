<?php
namespace  Monstercms\Lib;
class object
{
    private $___dbTable = '';
    private $___id;
    private $___idrow = 'id';
    private $___db;
    private $___result = array();

    function __construct($dbTable, $id, $idrow = 'id')
    {
        global $DB;

        $this->___db = $DB;
        $this->___dbTable = $this->___db->escape_string($dbTable);
        $this->___id      = $this->___db->escape_string($id);
        $this->___idrow   = $this->___db->escape_string($idrow);
    }

    function __get($name)
    {
        $this->result();


        if(!isset($this->___result[$name])) return null;

        return $this->___result[$name];
    }

    function __isset($name){
        $this->result();

        if(!isset($this->___result[$name])) return null;

        return $this->___result[$name];
    }



    function __set($name, $value)
    {
        /* индификатор менять нельзя! */
        //$this->result();

        if($name == $this->___idrow)
        {
            throw new Exception('Индификатор менять нельзя!');
            return;
        }
        //if(!isset($___result[$name])) return null;

        $this->___result[$name] = $value;

    }

    public function save()
    {
        //$this->result();

        if(empty($this->___result)) return null;

        $result = $this->___db->update($this->___result, $this->___dbTable, $this->where());

        return $result;

    }

    private function result()
    {
        if(!empty($this->___result)) return false;


        $sql = 'SELECT * FROM `'.$this->___dbTable.'` WHERE '.$this->where();


        $result = $this->___db->query($sql);
        $this->___result = $this->___db->fetch_assoc($result);

        if(empty($this->___result)) return false;
        return true;
    }

    private function where()
    {
        return '`'.$this->___idrow.'` = "'.$this->___id.'"';
    }


}
?>