<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

class Module
{
    private static $controllers;

    /**
     * Метод возвращает экземпляр контроллера (модуля)
     * @param $module - модуль
     * @throws \Exception
     * @return ControllerAbstract
     */
    public static function get($module)
    {
        if (isset(self::$controllers[$module])) {
            return self::$controllers[$module];
        }

        self::set($module);

        if (isset(self::$controllers[$module])) {
            return self::$controllers[$module];
        }

        throw new \Exception('Module not include');

    }

    /**
     * Метод добавляет экземпляр контроллера (модуля)
     * @param $module
     * @throws \Exception
     * @return  ControllerAbstract
     */
    public static function set($module)
    {

        if (!self::isModule($module)) {
            throw new \Exception('Module not include');
        }

        $controllerName = '\\Monstercms\\Modules\\' . $module . '\\Controller';

        self::$controllers[$module] = new $controllerName($module);

        return  self::$controllers[$module];
    }

    /**
     * Метод проверяет существует ли модуль
     * @param $module
     * @return bool
     */
    public static function isModule($module)
    {
        $controllerName = '\\Monstercms\\Modules\\'.$module . '\\Controller';

        //if before module "-" then disabled module
        if (preg_match('/^-/', $module)) {

            $class = new \ReflectionClass($controllerName);
            $extension = $class->getExtensionName();

            if($extension !== 'ControllerAbstract') {
                return false;
            }
            return false;
        }

        $module = preg_replace('/[^\w-_0-9]/', '', $module);

        $moduleFile = ROOT . DS . MODULE_DIR . DS .
            $module . DS . 'Class' . DS . 'Controller.php';


        if (!is_file($moduleFile)) {
            return false;
        }

        return true;
    }


    /**
     * Метод возвращает список модулей
     * @return array
     */
    public static function moduleList()
    {
        $dir  = opendir(ROOT . DS .MODULE_DIR);

        $list = array();
        while ($moduleName = readdir($dir))
        {
            if ($moduleName == '.' || $moduleName == '..' ||
                !is_dir(ROOT . DS . MODULE_DIR . DS . $moduleName)) continue;

            if (preg_match('/^-/', $moduleName)) continue;

            $list[] = $moduleName;
        }

        return $list;
    }

    /**
     * Метод инциализирует все доступные модули
     * (подключает файлы: modules/Модуль/actions/init.php)
     */
    public static function initAll()
    {
        $dir  = opendir(MODULE_DIR);

        while ($moduleName = readdir($dir)) {

            //исключаем модули которые начинаются с '-'
            if(preg_match('/^-/', $moduleName)) continue;

            if(!is_dir(MODULE_DIR . DS .$moduleName)
                || !self::isModule($moduleName)
            ) {
                continue;
            }

            $init = MODULE_DIR . DS .$moduleName . DS . 'Init.php';

            if (file_exists($init)) {
                include_once($init);
            }
        }
    }
}

