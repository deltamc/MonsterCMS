<?php
class select extends form_item
{
    public function item_special()
    {
        return array
        (
            'options'        => array()
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

        foreach($it['options'] as $key => $value)
        {
            $input  .= '<option ';
            $input  .= 'value="'.$key.'" ';
            if($it['value'] == $key) $input  .= 'selected ';
            $input  .= '>';
            $input  .= $value;
            $input .='</option>';
        }
        $input .='</select>';
        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }
}
?>