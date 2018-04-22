<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class select extends form_item
{
    public function item_special()
    {
        return array
        (
            'options'        => array(),
            'options_attr'   => array(),
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

        }

        foreach($it['options'] as $key => $value)
        {
            $input  .= '<option ';
            $input  .= 'value="'.$key.'" ';
            if($it['value'] == $key) $input  .= 'selected ';
            if (isset($it['options_attr'][$key])) {
                $input  .= $it['options_attr'][$key] . ' ';
            }
            $input  .= '>';
            $input  .= $value;
            $input .='</option>';
        }
        $input .='</select>';
        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }
}