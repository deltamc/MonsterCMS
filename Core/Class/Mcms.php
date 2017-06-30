<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use \Monstercms\Lib\View;


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

    static public function setTimeZone($timezone)
    {
        @ini_set('date.timezone', $timezone);
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set($timezone);
        } else {
            putenv('TZ='.$timezone);
        }
    }

    static public function showError($debugging)
    {
        if ($debugging) {
            @ini_set('error_reporting', E_ALL);
            @ini_set('display_errors', 1);
            @ini_set('display_startup_errors', 1);
        } else {
            @ini_set('error_reporting', 0);
            @ini_set('display_errors', 0);
        }
    }

    static function setDialogTheme()
    {
        View::setBasicTemplate(THEMES_DIALOG_PATH);
        View::replace('BASE', BASE_DIALOG);
    }
}