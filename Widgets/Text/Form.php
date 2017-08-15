<?php

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
                  'upload_script'=>'/Widgets/UploadCkeditor/PageId/' . $this->pageId.'?',
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
                array
                (
                        'name' => "id",
                        'type' => 'text',
                        'label' => 'Id:',
                        'valid' => array
                        (
                            'pattern' => array(
                                '^[\w\d-_]*$',
                                'Не допустимые символы'
                            ),
                        ),
                ),

              array
              (
                  'name' => "css_class",
                  'type' => 'text',
                  'label' => 'CSS class:',
                  'valid' => array
                  (
                      'pattern' => array(
                          '^[\w\d-_]*$',
                          'Не допустимые символы'
                      ),
                  ),
              ),
          )
        ),


    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);