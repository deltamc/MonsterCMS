<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class Tree
{
    private $db;
    private $dbTable;
    public  $colId          = 'id';
    public  $colParent      = 'parent_id';
    public  $colChildCount  = 'child_count';
    public  $colName        = 'name';
    public  $colOrderBy     = 'pos';

    private $tree = array();
    private $where = '';
    public  $sql = '';

    function __construct($db, $dbTable, $where = '')
    {
        $this->dbTable = $dbTable;
        $this->db      = $db;
        $this->where = $where;
    }

    function __destruct(){
        unset($this->tree);
    }

    private function getTree($reset = false)
    {
        if(!empty($this->tree) && !$reset) return $this->tree;



        if(empty($this->sql))
        {
            $sql = 'SELECT * FROM '.$this->dbTable.' ';
        }else
        {
            $sql = $this->sql;
        }

        if(!empty($this->where))      $sql .= 'WHERE ' . $this->where;
        if(!empty($this->colOrderBy)) $sql .= ' ORDER BY ' . $this->colOrderBy;

        $result = $this->db->query($sql);
        $this->tree = array();
        $rows = $result->fetchAll();
        foreach ($rows as $row)
        {
            $this->tree[$row[$this->colId]] = $row;
        }

        return $this->tree;
    }


    public function childNodes($parent_id, $get_child = true)
    {
        $parent_id = intval($parent_id);

        $this->getTree();

        if($parent_id !=0 && (empty($this->tree) || !isset($this->tree[$parent_id])))
            return array();

        if($parent_id !=0 && intval($this->tree[$parent_id][$this->colChildCount]) == 0)
            return array();

        $out = array();

        $ccc = $this->colChildCount;
        $cp  = $this->colParent;



        foreach($this->tree as $id => $node)
        {
            if($node[$cp] != $parent_id) continue;
            $out[$id] = $node;

            if(!$get_child) continue;
            if(intval($node[$ccc]) == 0) continue;

            $childs = $this->childNodes($id);

            if(empty($childs) || !is_array($childs)) continue;

            foreach($childs as $child_id => $child)
            {
                $out[$child_id] = $child;
                //$out[$child_id]['level'] = $level;
            }
        }

        return $out;

    }

    /**
     * update this node and child nodes
     * @param $id
     * @param array $values
     * $values = array('menu_id'=>3, 'show'=> '')
     */
    public function update($id, array $values)
    {
        $child_nodes = $this->childNodes($id);

        $child_nodes[$id] = $this->tree[$id];

        //fields
        $fields = array();
        $fields[] = $this->colId;
        foreach ($values as $field=>$value)
        {
            $fields[] = $field;
        }

        //values
        $_values = array();
        $s = 0;

        foreach ($child_nodes as $node_id=>$node)
        {
            $_values[$s][] = $node_id;
            foreach ($values as $field=>$value)
            {
                $_values[$s][] = $value;
            }
            $s++;
        }

        $this->db->insertOrUpdate($fields,  $_values, $this->dbTable);
    }


}
