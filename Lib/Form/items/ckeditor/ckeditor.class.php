<?php
defined('MCMS_ACCESS') or die('No direct script access.');

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
            //Скрипт для загрузки изображения
            'upload_script' => null,
            //ширена
            'width'=>'100%',
            //высота
            'height'=>'500',
            //можно ли менять размер
            'resize_enabled'=>true,
            //css
            'bodyClass' => null,
            'bodyId'    => null,
            'contentsCss' => null,

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
        $input  .= '$(function(){';


        $input  .= 'var ckeditor1 = CKEDITOR.replace( "'.$it['id'].'" );';


        if(!empty($it['upload_script'])) {
            $input  .= 'CKEDITOR.config.filebrowserUploadUrl = "'.$it['upload_script'].'";';
            $input  .= 'CKEDITOR.config.extraPlugins = "uploadimage";';
        }

        if(!empty($it['width'])) {
            $input  .= 'CKEDITOR.config.width = "'.$it['width'].'";';
        }

        if(!empty($it['height'])) {
            $input  .= 'CKEDITOR.config.height = "'.$it['height'].'";';
        }

        if(!empty($it['resize_enabled'])) {
            $input  .= 'CKEDITOR.config.resize_enabled = "'.$it['resize_enabled'].'";';
        }

        if(!empty($it['bodyClass'])) {
            $input  .= 'CKEDITOR.config.bodyClass = "'.$it['bodyClass'].'";';
        }

        if(!empty($it['bodyId'])) {
            $input  .= 'CKEDITOR.config.bodyId = "'.$it['bodyId'].'";';
        }

        if(!empty($it['contentsCss'])) {
            $input  .= 'CKEDITOR.config.contentsCss = "'.$it['contentsCss'].'";';
        }



/*
        $input  .= 'AjexFileManager.init({';
        $input  .= 'returnTo: "ckeditor",';

            //$input  .= 'path: "'.$it['upload_path'].'",';
        $input  .= 'editor: ckeditor1';
        $input  .= '});';

        if(!empty($it['upload_path']))
            $_SESSION['mform_upload_path'] = $it['upload_path'];


*/
        $input  .= '});';
        $input  .= '</script>';


        $this->item['input'] = $input;

        return $this->template($this->item['tpl'], $this->item );
    }



}