<?php

//print $this->pageId;
return array(
    array
    (
            'name' => "text",
            'type' => 'ckeditor',
            'label' => 'Текст*:',
            'valid' => array
            (
                'required'
            ),
            'upload_script'=>'fdsa.php',
            'height'=>'300',
            'resize_enabled' => false,


    ),
    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);