<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;



class Mcms
{
    /**
     * @var Lib\DataBase
     */
    private static $db = null;
    public static $db_table_url;



    public static function DB()
    {
        if (self::$db === null) {
            self::$db = new Lib\DataBase(DB_CONNECTION, DB_USER, DB_PASSWORD);
        }
        return self::$db;
    }



    /**
     * The method removes from the array elements that are in $hide
     * @param array $form_items - form elements
     * @param array $hide -  which fields to hide
     * @return array
     */
    public static function hiddenItemsForm(array $form_items, array $hide)
    {
        $out = array();

        foreach ($form_items as $it)
        {

            if (isset($it['items']) && is_array($it['items']))
            {
                $it['items'] = self::hiddenItemsForm($it['items'], $hide);
                $out[] = $it;
            }
            else
            {

                if (!isset($it['name']) || !in_array($it['name'], $hide))
                {
                   $out[] = $it;
                }
           }
        }

        return $out;
    }
    //@TODO Вместо $item_type и $object_id передавать массив параметров для передачи в метод подписчика события

    /*
    public static function eventsForm(array $form_items, $moduleName, $item_type, $object_id = null)
    {
        $out = array();

        foreach ($form_items as $it)
        {

            if (isset($it['items']) && is_array($it['items']))
            {
                $it['items'] = self::eventsForm($it['items'], $moduleName, $item_type);
                $out[] = $it;
            }
            else
            {
                if(isset($it['type']) && $it['type'] == 'event')
                {
                    $vars = array($moduleName, $item_type);
                    if(!is_null($object_id)) $vars['object_id'] = $object_id;

                    $items = Events::cell($it['event'], 'array_merge', $vars);


                    if(is_array($items))
                    {
                        foreach($items as $item)
                        {
                            $out[] = $item;
                        }

                    }
                }
                else
                {
                    $out[] = $it;
                }

            }
        }

        return $out;
    }
*/

    /**
     * Метод вызывает события формы
     * @param array $formItems - Элементы формы
     * @param $moduleName - имя модуля
     * @param array $params - параметры для передачи в методы которые подписаны на событие
     * @return array
     */
    public static function eventsForm(array $formItems, $moduleName, $params = array())
    {
        $out = array();

        foreach ($formItems as $it) {
            if (isset($it['items']) && is_array($it['items'])) {
                $it['items'] = self::eventsForm($it['items'], $moduleName, $params);
                $out[] = $it;
            } else {
                if (isset($it['type']) && $it['type'] == 'event') {

                    if(!is_array($params)) {
                        $params = array();
                    }
                    array_unshift($params, $moduleName);

                    $items = Events::cell($it['event'], 'array_merge', $params);

                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $out[] = $item;
                        }
                    }
                } else {
                    $out[] = $it;
                }
            }
        }

        return $out;
    }

    /**
     *
     * @param $module
     * @return null|string - module name in menu
     */
    static function getMenuItemName($module, $item_type)
    {


        if(!property_exists ( Module::get($module) , 'config' )) return null;

        $config =  Module::get($module)->config;

        if (!isset($config['menu_items']) ||
            !isset($config['menu_items'][$item_type]) ||
            !isset($config['menu_items'][$item_type]['menu_item_name'])) return null;

        return $config['menu_items'][$item_type]['menu_item_name'];
    }


}