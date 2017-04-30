<?php
class select_sql extends form_item
{
    private $values = '';

    public function item_special()
    {
        return array
        (
            'sql'            => '',
            'col_value'      => '',
            'col_option'     => '',
            'value'          => 0,
            'first'          => false,
            'first_value'    => "",
            'first_text'     => "",
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

        if($it['first'])
        {
            $input  .= "<option value='" . $it['first_value'] . "'";
            if($it['value'] == $it['first_value']) $input  .= " selected";
            $input  .= " >" . $it['first_text'] . "</option>";
            $input  .= $this->rows();
        }
        else $input  .= $this->rows();

        $input .='</select>';
        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }





    private function rows()
    {
        $db = \Monstercms\Core\Mcms::DB();

        $it = $this->item;
        if(empty($it['sql'])) return '';


        $result = $db->query($it['sql']);

        $rows = $result->fetchAll();

        foreach ($rows as $row) {

            $this->values .= "<option value=\"".$row[$it['col_value']]."\" ";
            if($it['value'] == $row[$it['col_value']]) $this->values .= "selected";
            $this->values .= " >".$row[$it['col_option']]."</option>";


        }

        return $this->values;
    }



}




?>