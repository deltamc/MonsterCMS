<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;


class Model extends Core\ModelAbstract
{
    /**
     * экземпляры контроллеров виджетов
     * @var array
     */
    private static $controllers = array();

    /**
     * Метод возвращает экземпляр контроллера (виджета)
     * @param $widget - модуль
     * @throws \Exception
     * @return Core\WidgetInterface
     */
    public function get($widget)
    {
        if (isset(self::$controllers[$widget])) {
            return self::$controllers[$widget];
        }

        self::set($widget);

        if (isset(self::$controllers[$widget])) {
            return self::$controllers[$widget];
        }

        throw new \Exception('Widget not include');

    }

    public function getAll()
    {
        return self::$controllers;
    }


    /**
     * Метод добавляет экземпляр контроллера (виджета)
     * @param $widget
     * @throws \Exception
     * @return  Core\WidgetInterface
     */
    public function set($widget)
    {

        if (!self::isWidget($widget)) {
            throw new \Exception('Module not include');
        }

        $controllerName = '\\Monstercms\\Widgets\\' . $widget . '\\Controller';

        self::$controllers[$widget] = new $controllerName($widget);

        return  self::$controllers[$widget];
    }

    /**
     * Метод проверяет существует ли модуль
     * @param $widget
     * @return bool
     */
    public function isWidget($widget)
    {
        $controllerName = '\\Monstercms\\Widgets\\'.$widget . '\\Controller';

        if (preg_match('/^-/', $widget)) {
            return false;
        }

        $class = new \ReflectionClass($controllerName);

        if(!$class->implementsInterface('Monstercms\Core\WidgetInterface')) {
            return false;
        }

        return true;
    }


    /**
     * Метод возвращает список Виджетов
     * @return array
     */
    public function widgetList()
    {
        $dir  = opendir(ROOT . DS . $this->config['widgetDir']);

        $list = array();
        while ($widgetName = readdir($dir))
        {
            if ($widgetName == '.' || $widgetName == '..' ||
                !is_dir(ROOT . DS . $this->config['widgetDir'] . DS . $widgetName)) continue;

            if (preg_match('/^-/', $widgetName)) continue;

            $list[] = $widgetName;
        }

        return $list;
    }

    /**
     *
     * @throws \Exception
     */
    public function init()
    {
        $widgetList = $this->widgetList();

        foreach ($widgetList as $widget) {
            $this->set($widget);
        }
    }

    function add(Core\WidgetInterface $widget, array $date, $objectId = null)
    {
        $params = $widget->getParameters();

        $insert = array();

        $widgetId = null;



        foreach ($params as $key => &$value) {
            if (isset($date[$key])) {
                $value = $date[$key];
            }
        }
        unset($value);

        $widgetName = $widget->getWidgetName();
        $cache = $widget->getView($params);
        $pos   = $this->getNextMaxPos($objectId);

        $list = array(
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'object_id' => $objectId
        );



        $this->db->insert($list, $this->config['dbTableWidgets']);
        $widgetId = $this->db->lastInsertId();

        foreach ($params as $key => $value) {

                $insert[] = array(
                    $widgetId,
                    $key,
                    $value
                );
        }
        $fields = array(
            'widget_id',
            'key',
            'value'
        );
        $this->db->insertOrUpdate($fields, $insert, $this->config['dbTableOptions']);

        return array(
            'id'        => $widgetId,
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'object_id' => $objectId
        );


    }

    function getNextMaxPos($objectId = null){
        if (is_null($objectId)) return 0;

        $objectId = (int) $objectId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT `pos` FROM {$table}  WHERE object_id=" . $objectId;

        $result = $this->db->query($sql);
        $pos    = $result->fetch();

        return $pos[0] +1;
    }

    public function widgetsList($objectId)
    {
        $objectId = (int) $objectId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT * FROM {$table}  WHERE object_id=" . $objectId;

        $result = $this->db->query($sql);

        return $result->fetchAll();

    }



}