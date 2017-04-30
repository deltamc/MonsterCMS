<?php

class ckeditor extends form_item
{

    public $javascript = array
    (
        "ckeditor/ckeditor.js",
        //"AjexFileManager/ajex.js"
    );
    public function item_special()
    {
        return array
        (

            'upload_script'    => null
        );
    }
    public function format_default()
    {
        return array
        (
            'html' => true,

        );
    }

    function __toString()
    {
        $it = $this->item;

        $input  = '<textarea ';
        $input .= 'id="'.$it['id'].'" ';

        if(!empty($it['name']))       $input .= 'name = "'.$it['name'].'" ';
        if(!empty($it['style']))      $input .= 'style = "'.$it['style'].'" ';
        if(!empty($it['maxlength']))  $input .= 'maxlength="'.$it['maxlength'].'" ';
        if($it['autofocus'])          $input .= 'autofocus ';

        if(!empty($it['attributes'])) $input .= $this->attributes($it['attributes']);
        $input  .= '>';
        if(!empty($it['value']))      $input .= $it['value'];
        $input  .= '</textarea>';


        $input  .= '<script type="text/javascript">';

        if(!empty($it['upload_script']))
        $input  .= 'CKEDITOR.config.filebrowserUploadUrl = "'.$it['upload_script'].'";';

        $input  .= 'var ckeditor1 = CKEDITOR.replace( "'.$it['id'].'" );';

/*
        $input  .= 'AjexFileManager.init({';
        $input  .= 'returnTo: "ckeditor",';

            //$input  .= 'path: "'.$it['upload_path'].'",';
        $input  .= 'editor: ckeditor1';
        $input  .= '});';

        if(!empty($it['upload_path']))
            $_SESSION['mform_upload_path'] = $it['upload_path'];


*/
        $input  .= '</script>';


        $this->item['input'] = $input;

        return $this->template($this->item['tpl'], $this->item );
    }



}
?>