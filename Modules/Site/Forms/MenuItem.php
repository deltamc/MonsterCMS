<?php

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Lang;
use \Monstercms\Core\Mcms;
use \Monstercms\Core\Url;
use \Monstercms\Lib\Request;

if(!isset($parentId))    $parentId = 0;
if(!isset($menuItemId)) $menuItemId = 0;
if(!isset($urlId)) $urlId = null;
if(isset($_POST['menu_item_parent'])) $parentId = (int) Request::getPost('menu_item_parent');


if(!isset($action)) $action = 'add';

$urlObj = new Url();

$checked = '';
if($action == 'add') $checked = 'checked';

$form = array
(
    array(
        'type'  => 'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabBaseBefore'
    ),
    array
    (
        'type'  => 'tab',
        'label' => 'base',
        'items' => array(
            array(
                'type'  => 'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseItemName'
            ),
            array
            (
                'name' => 'menu_item_name',
                'type' => 'text',
                'label' => Lang::get('Site.nameMenuItem') . '*',
                'valid' => array
                (
                    'required'
                ),
            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseUrlSemantic'
            ),
            array
            (
                'name' => 'menu_item_url_semantic',
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
                            //'\Monstercms\Core\Mcms::urlValid',
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
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseItemUrl'
            ),
            array
            (
                'name'  => 'menu_item_url',
                'type'  => 'text',
                'label' =>  Lang::get('Site.link').'*',

                'valid' => array
                (
                    'required'
                ),
            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseItemMenu'
            ),
            array
            (
                'name' => "menu_item_menu",
                'type' => 'select_sql',
                'sql' => 'SELECT * FROM ' . $this->config['db_table_menu'],
                'col_value' => 'id',
                'col_option' => 'name',
                'label' => Lang::get('Site.menu') . '*',
                'valid' => array
                (
                    'required'
                )

            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseItemCss'
            ),
            array
            (
                'name'  => "menu_item_css",
                'type'  => 'text',
                'label' => Lang::get('Site.cssMenuItem'),
                'valid' => array
                (
                    'pattern'=>'^[0-9a-z-_\s]*$'
                ),
                'help' => Lang::get('Site.cssMenuItemHelp')
            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action . 'FormTabBaseItemTarget'
            ),
            array
            (
                'name' => "menu_item_target",
                'type' => 'checkbox',
                'check_value' => '_blank',
                'no_check_value' => '',
                'label' => Lang::get('Site.target') . '',
                'help'=> Lang::get('Site.targetHelp')

            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName.'.'.$action.'FormTabBaseItemIndex'
            ),
            array
            (
                'name' => "menu_item_index",
                'type' => 'checkbox',
                'check_value' => '1',
                'no_check_value' => '0',
                'label' => Lang::get('Site.index') . '',
                'help' => Lang::get('Site.indexHelp')
            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName.'.'.$action.'FormTabBaseItemShow'
            ),
            array
            (
                'name' => "menu_item_hide",
                'type' => 'checkbox',
                'check_value' => '1',
                'no_check_value' => '0',
                'label' => Lang::get('Site.show'),
                'help'  => Lang::get('Site.showHelp'),


            ),
            array(
                'type'=>'event',
                'event' => $this->moduleName . '.' . $action.'FormTabBaseEnd'
            ),

        ),
    ),
    array(
        'type'=>'event',
        'event' => $this->moduleName . '.' . $action . 'FormTabBaseAfter'
    ),



    array
        (
            'type' => 'html',
            'html' => '<input type="submit" value="'.Lang::get('Site.submit').'" />
            <input type="checkbox" value="1" name="menu_item_goto" '.$checked.' /> '.Lang::get('Site.goTo').'
                         '
        )

    );



return  $form;