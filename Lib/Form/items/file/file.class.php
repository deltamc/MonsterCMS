<?php

class file extends form_item
{


    public function __toString()
    {
        $it = $this->item;
        $input  = '<input ';
        $input .= 'type="file" ';
        $input .= 'id="'.$it['id'].'" ';

        if(!empty($it['name']))   $input .= 'name="'.$it['name'].'" ';
        if(!empty($it['style']))  $input .= 'style="'.$it['style'].'" ';
        if(!empty($it['accept'])) $input .= 'accept="'.$it['accept'].'" ';


        if(!empty($it['attributes'])) $input .= $this->attributes($it['attributes']);

        $input  .= '/>';
        if(is_int($it['max_file_size']))
            $input .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.$it['max_file_size'].'" />';

        if(empty($this->item['value']) && !empty($this->item['img_default']) )
            $this->item['value'] = $this->item['img_default'];

        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }

    public function item_special()
    {
        return array
        (
            'path'       => null,
            'accept'     => '*/*',
            'max_file_size' => null,
            'width' => 150

        );
    }

    public function format_default()
    {
        return array
        (
            'type' => 'file',

        );
    }

    public function valid_default()
    {
        return array();
    }
}