<?php
use Monstercms\Core\Module;
return array(

    array(
          'type'=>'tab',
          'label' => 'Текст',
          'items' =>array
         (

              array
              (
                  'name' => "code",
                  'type' => 'textarea',
                  'label' => 'Код*:',
                  'style'  => 'height:400px;',
                  'valid' => array
                  (
                      'required'
                  )
              ),
          )
        ),
    array(
          'type'=>'tab',
          'label' => 'Атрибуты',
          'items' =>array
         (
              Module::get('Widgets')->getCssClassFormElement()
          )
        ),


    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);