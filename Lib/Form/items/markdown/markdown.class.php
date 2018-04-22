<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class markdown extends form_item
{

    public $javascript = array
    (


        "markitup/jquery.markitup.js",
        "markitup/sets/markdown/set.js"
    );

    public $css = array
    (

        "markitup/skins/simple/style.css",
        "markitup/sets/markdown/style.css",

    );

    public $js_load = '$(".markItUp").markItUp(mySettings);';

    function __toString()
    {
        $it = $this->item;

        $input  = '<textarea ';
        $input .= 'id="'.$it['id'].'" ';
        $input .= 'class = "markItUp" ';

        if(!empty($it['name']))       $input .= 'name = "'.$it['name'].'" ';
        if(!empty($it['style']))      $input .= 'style = "'.$it['style'].'" ';
        if(!empty($it['maxlength']))  $input .= 'maxlength="'.$it['maxlength'].'" ';
        if($it['autofocus'])          $input .= 'autofocus ';
        if(!empty($it['attributes'])) $input .= $this->attributes($it['attributes']);
        $input  .= '>';
        if(!empty($it['value']))      $input .= $it['value'];
        $input  .= '</textarea>';

        if(intval($it['maxlength']) > 0)
        {
            $input  .= '<span class="textarea-length"></span>';
        }

        $this->item['input'] = $input;

        return $this->template($this->item['tpl'], $this->item );
    }



}