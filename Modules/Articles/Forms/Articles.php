<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Url;
use \Monstercms\Core\Lang;
use \Monstercms\Core\Module;

$catalogArray = Module::get('Site')->treeCatalogSelect('', $this->moduleName, 'articles');

$urlObj = new Url();
if(!isset($urlId)) $urlId = null;


$goTo = (!isset($action) || 'add' === $action ) ? 1: 0;

$form = array(
    array(
          'type'=>'tab',
          'label' => 'Основные',
          'items' =>array
         (
              array
              (
                  'name'         => "menuItem",
                  'type'         => 'select',
                  'label'        => 'Каталог',
                  'options'      => $catalogArray['items'],
                  'options_attr' => $catalogArray['disabled'],
                  'first'        => true,
                  'valid' => array
                  (
                      'required'
                  ),
              ),
              array
              (
                  'name' => "name",
                  'type' => 'text',
                  'label' => 'Название статьи',
                  'valid' => array
                  (
                      'required'
                  ),
              ),
              array
              (
                  'name' => 'url_semantic',
                  'type' => 'text',
                  'label' =>  'URL*:',
                  'valid' => array
                  (
                      'required',

                      'pattern' => '[a-z0-9-_]+',
                      'call' => array(
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
          'type'=>'tab',
          'label' => 'Анонс статьи',
          'items' =>array
         (
              array
              (
                  'name'   => "preview",
                  'type'   => 'ckeditor',
                  'label'  => 'Анонс статьи:',
                  'height' => '350',
                  'resize_enabled' => false,
              ),
          )
        ),


);
$form = array_merge($form, Module::get('PageSemantic')->getSeoForm());
$form = array_merge($form, Module::get('PageSemantic')->getThemeForm());
$form[] = array
(
    'name'           => "article_goto",
    'type'           => 'checkbox',
    'check_value'    => '1',
    'no_check_value' => '0',
    'label'          => Lang::get('Site.goTo'),
    'value'          => $goTo
);

$form[] = array
(
    'type' => 'html',
    'html' => '<input type="submit" value="'.Lang::get('Site.submit').'" />
                         '
);

return $form;

