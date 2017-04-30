<?php

class images_upload extends form_item
{
    public $tpl = '<div {item_attr}><label for="{id}">{label}</label>
    <div><img  src="{path}/{value}" width="150"  align="top">
    {input}<br><span id="error_{id}" class="error">{error}</span></div></div>';

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
        if(empty($this->item['value']) ) $this->item['value'] = $this->item['img_default'];

        $this->item['input'] = $input;

        return $this->template($this->tpl, $this->item );
    }

    public function item_special()
    {
        return array
        (
            'path'       => null,
            'accept'     => 'image/*',
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
?>