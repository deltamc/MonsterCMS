<?php
defined('MCMS_ACCESS') or die('No direct script access.');
use \Monstercms\Core\Lang;
return array(
    array(
        'type'  => 'tab',
        'label' => Lang::get('Articles.formTextTable'),
        'items' => array
        (
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.ArticlesTextFormTopBefore'
            ),
            array
            (
                    'name' => "textTop",
                    'type' => 'ckeditor',
                    'label' => Lang::get('Articles.formTextTop'),
                    //'upload_script'=>'/UploadImages/UploadCkeditor/Module/Article/Id/' . $this->pageId.'?',
            ),
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.ArticlesTextFormTopAfter'
            ),
            array
            (
                'name' => "textBottom",
                'type' => 'ckeditor',
                'label' =>  Lang::get('Articles.formTextBottom'),
            ),
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.ArticlesTextFormBottomAfter'
            ),
        )
    )
);