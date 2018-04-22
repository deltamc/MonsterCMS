<?php
defined('MCMS_ACCESS') or die('No direct script access.');
use Monstercms\Core\Module;
return array(

    array(
          'type'=>'tab',
          'label' => 'Текст',
          'items' =>array
         (
              array
              (
                  'name' => "text",
                  'type' => 'ckeditor',
                  'label' => 'Текст*:',
                  'valid' => array
                  (
                      'required'
                  ),
                  'upload_script'=>'/UploadImages/UploadCkeditor/Module/Widgets/',
                  'height'=>'350',
                  'resize_enabled' => false,


              ),
          )
        ),
    array(
          'type'=>'tab',
          'label' => 'Атрибуты',
          'items' =>array
         (
              Module::get('Widgets')->getCssClassFormElement(),
          )
    ),


    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);