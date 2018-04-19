<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Url;
use \Monstercms\Core\Lang;
use \Monstercms\Core\Module;

$catalogArray = Module::get('Site')->treeCatalogSelect('', $this->moduleName, 'articles');

$urlObj = new Url();
if (!isset($urlId)) $urlId = null;


$goTo = (!isset($action) || 'add' === $action) ? 1 : 0;

$form = array(
    array(
        'type'  => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabBaseBefore'
    ),
    array(
        'type'  => 'tab',
        'label' => 'Основные',
        'items' => array(
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseMenuItemBefore'
            ),
            array(
                'name'         => "menuItem",
                'type'         => 'select',
                'label'        => Lang::get('Articles.catalog'),
                'options'      => $catalogArray['items'],
                'options_attr' => $catalogArray['disabled'],
                'first'        => true,
                'valid'        => array (
                    'required'
                ),
            ),
            array(
                'type' => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseMenuItem'
            ),
            array(
                'name'  => "name",
                'type'  => 'text',
                'label' => Lang::get('Articles.articleName'),
                'valid' => array (
                    'required'
                ),
            ),
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseName'
            ),
            array(
                'name'  => 'url_semantic',
                'type'  => 'text',
                'label' => 'URL*:',
                'valid' => array(
                    'required',
                    'pattern' => '[a-z0-9-_]+',
                    'call'    => array(
                        array
                        (
                            array($urlObj, "urlValid"),
                            array
                            (
                                'url_id' => $urlId
                            ),
                        ),
                        Lang::get('Site.urlInvalid')
                    ),
                ),
            ),
        )
    ),
    array(
        'type' => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabBase'
    ),


    array(
        'type' => 'tab',
        'label' => Lang::get('Articles.articlePreview'),
        'items' => array
        (
            array(
                'type' => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabPreviewPreviewBefore'
            ),
            array
            (
                'name' => "preview",
                'type' => 'ckeditor',
                'label' => Lang::get('Articles.articlePreview'),
                'height' => '250',
                'resize_enabled' => false,
            ),
            array(
                'type' => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabPreviewPreview'
            ),
        )
    ),
    array(
        'type' => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabPreview'
    ),


);

$form = array_merge($form, Module::get('PageSemantic')->getSeoForm());

$form[] = array(
        'type' => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabSeo'
    );

$form = array_merge($form, Module::get('PageSemantic')->getThemeForm());

$form[] = array(
        'type' => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabTheme'
);


$form[] = array
(
    'type' => 'inline',
    'items' => array(
        array
        (
            'type' => 'submit',
            'value' => Lang::get('Site.submit')
        ),
        array
        (
            'name' => "article_goto",
            'type' => 'checkbox',
            'check_value' => '1',
            'no_check_value' => '0',
            'label' => Lang::get('Site.goTo'),
            'value' => $goTo,
            'attr' => array('class' => 'goto')
        ),

    )

);

return $form;

