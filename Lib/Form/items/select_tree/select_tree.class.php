<?php

class select_tree extends form_item
{
    private $tree = '';

    public function item_special()
    {
        return array
        (
            'table'          => '',
            'col_parent'     => '',
            'where'          => '',
            'prefix'         => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            'order_by'       => '',
            'col_value'         => '',
            'col_option'       => '',
            'root'           => true,
            'root_option'      => 'Root',
            'root_value'      => 0,
            'value'           => 0
        );
    }

    public function __toString()
    {
        $it = $this->item;
        $input  = '<select ';
        $input .= 'id="'.$it['id'].'" ';

        if(!empty($it['name']))       $input .= 'name="'.$it['name'].'" ';
        if(!empty($it['style']))      $input .= 'style="'.$it['style'].'" ';
        if($it['autofocus'])          $input .= 'autofocus ';
        if(!empty($it['attributes'])) $input .= $this->attributes($it['attributes']);
        $input  .= '>';

        if($it['root'])
        {
            $input  .= "<option value='" . $it['root_value'] . "'";
            if($it['value'] == $it['root_value']) $input  .= " selected";
            $input  .= " >" . $it['root_option'] . "</option>";
            $input  .= $this->tree(0, $it['prefix']);
        }
        else $input  .= $this->tree(0);


        /*
        foreach($it['options'] as $key => $value)
        {
        $input  .= '<option ';
        $input  .= 'value="'.$key.'" ';
        if($it['value'] == $key) $input  .= 'selected ';
        $input  .= '>';
        $input  .= $value;
        $input .='</option>';
        }
        */

        $input .='</select>';
        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }


    private function sql($parent)
    {
        $it = $this->item;

        $sql = "SELECT * FROM ".$it['table']." WHERE ".$it['col_parent']." = '".intval($parent)."'";
        if(!empty($it['where']))     $sql .= ' AND ' . $it['where'];
        if(!empty($it['order_by']))  $sql .= ' ORDER BY '.$it['order_by'];

        return $sql;
    }


    private function tree($parent_id = 0, $prefix = null)
    {
        Global $DB;

        $it = $this->item;
        $query = $this->sql($parent_id);

        $result = $DB->query($query);

        while ($row = $DB->fetchArray($result))
        {

            $this->tree .= "<option value=\"".$row[$it['col_value']]."\" ";
            if($it['value'] == $row[$it['col_value']]) $this->tree .= "selected";
            $this->tree .= " >".$prefix.$row[$it['col_option']]."</option>";
            $this->tree($row[$it['col_value']], $prefix.$it['prefix']);

        }

        return $this->tree;
    }



}