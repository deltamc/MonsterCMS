<?php
use Monstercms\Core\Lang;

if(empty($url_id)) $url_id = 0;

$form = array(
    array
    (
        'type'  => 'tab',
        'label' => 'base',
        'items' => array(
            array
            (
                'name' => 'menu_item_url_sematic',
                'type' => 'text',
                'label' =>  'URL*:',
                //'style' => 'width:200px;',
                //'after' => '.html',
                //'before' => SITE_URL.'/',
                'valid' => array
                (
                    'required',

                    'pattern' => '[a-z0-9-_]+',
                    'cell' => array(
                        array
                        (
                            '\Monstercms\Core\Mcms::urlValid',
                            array
                            (
                                'url_id' => $url_id
                            ),
                        ),
                        Lang::get('site.urlInvalid')
                    ),
                ),
            )

        )

    ),
);

return $form;