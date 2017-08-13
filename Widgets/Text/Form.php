<?php

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
            'upload_script'=>'/Widgets/UploadCkeditor/PageId/' . $this->pageId.'?',
            'height'=>'300',
            'resize_enabled' => false,


    ),
    array
        (
            'type' => 'submit',
            'value' => ' Сохранить '
        ),
);