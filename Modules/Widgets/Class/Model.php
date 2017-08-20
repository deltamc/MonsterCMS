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
     * @param $widget - виджет
     * @param $pageId - ид страницы
     * @throws \Exception
     * @return Core\WidgetInterface
     */
    public function get($widget, $pageId = null)
    {
        if (isset(self::$controllers[$widget])) {
            return self::$controllers[$widget];
        }

        self::set($widget, $pageId);

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
    public function set($widget, $pageId = null)
    {

        if (!self::isWidget($widget)) {
            throw new \Exception('Module not include');
        }

        $controllerName = '\\Monstercms\\Widgets\\' . $widget . '\\Widget';

        self::$controllers[$widget] = new $controllerName($widget, $pageId);

        return  self::$controllers[$widget];
    }

    /**
     * Метод проверяет существует ли модуль
     * @param $widget
     * @return bool
     */
    public function isWidget($widget)
    {
        $controllerName = '\\Monstercms\\Widgets\\'. $widget . '\\Widget';

        if (preg_match('/^-/', $widget)) {
            return false;
        }

        $class = new \ReflectionClass($controllerName);

        if(!$class->implementsInterface('Monstercms\Modules\Widgets\WidgetInterface')) {
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

    function add(WidgetInterface $widget, array $data, $objectId = null)
    {
        $params = $widget->getParameters();

        $insert = array();

        $widgetId = null;



        foreach ($params as $key => &$value) {
            if (isset($data[$key])) {
                $value = $data[$key];
            }
        }
        unset($value);

        $widgetName = $widget->getWidgetName();
        $cache      = $widget->getView($params);
        $pos        = $this->getNextMaxPos($objectId);

        $cssClass   = '';
        if (isset($data['css_class'])) {
            $cssClass = htmlspecialchars($data['css_class']);
        }

        $list = array(
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'object_id' => $objectId,
            'css_class' => $cssClass
        );


        $widget->addBefore($list, $params);
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

        $widget->addAfter($list, $params);

        return array(
            'id'        => $widgetId,
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'object_id' => $objectId,
            'css_class' => $cssClass,
        );


    }

    function getNextMaxPos($objectId = null){
        if (is_null($objectId)) return 0;

        $objectId = (int) $objectId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT max(`pos`) FROM {$table}  WHERE object_id=" . $objectId;

        $result = $this->db->query($sql);
        $pos    = $result->fetch();

        return $pos[0] +1;
    }

    public function widgetsList($objectId)
    {
        $objectId = (int) $objectId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT * FROM {$table}  WHERE object_id={$objectId} ORDER BY `pos`";

        $result = $this->db->query($sql);

        $list = $result->fetchAll(\PDO::FETCH_ASSOC);


        foreach ($list as &$item) {
            $widgets[$item['widget']] = true;
            $widgetObj = $this->get($item['widget']);
            $item['javascript'] = $widgetObj->getJavaScript();
            $item['css']        = $widgetObj->getCSS();
        }
        unset($item);
        return $list;

    }

    public function getInfoById($widgetId)
    {

        $widgetId = (int) $widgetId;
        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT * FROM {$table} WHERE id={$widgetId}";

        $result = $this->db->query($sql);
        $info    = $result->fetch(\PDO::FETCH_ASSOC);

        $table = $this->config['dbTableOptions'];
        $sql = "SELECT * FROM {$table} WHERE widget_id={$widgetId}";

        $result = $this->db->query($sql);

        $options = $result->fetchAll(\PDO::FETCH_ASSOC);
        foreach($options as $option){
            $info['options'][$option['key']] = $option['value'];

        }

        return $info;
    }

    public function edit(WidgetInterface $widget, $data, $widgetId)
    {
        $params = $widget->getParameters();
        $widgetId = (int) $widgetId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT * FROM {$table} WHERE id={$widgetId}";

        $result = $this->db->query($sql);
        $info    = $result->fetch(\PDO::FETCH_ASSOC);

        $insert = array();

        foreach ($params as $key => &$value) {
            if (isset($data[$key])) {
                $value = $data[$key];
            }
        }
        unset($value);

        $widgetName = $widget->getWidgetName();
        $cache = $widget->getView($params);

        $cssClass   = '';
        if (isset($data['css_class'])) {
            $cssClass = $data['css_class'];
        }


        $list = array(
            'widget'    => $widgetName,
            'pos'       => $info['pos'],
            'object_id' => $info['object_id'],
            'cache'     => $cache,
            'css_class' => $cssClass
        );

        $widget->editBefore($list, $params);

        $this->db->update($list, $table, $widgetId);

        $widget->editAfter($list, $params);

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
            'cache'     => $cache
        );
    }

    /**
     * функция меняет местами я pos1 и pos2
     * @param $id1
     * @param $pos1
     * @param $id2
     * @param $pos2
     */

    public function exchangePosWidget($id1, $pos1, $id2, $pos2)
    {
        $id1  = intval($id1);
        $pos1 = intval($pos1);

        $id2  = intval($id2);
        $pos2 = intval($pos2);

        $tab =  $this->config['dbTableWidgets'];

        $sql = "UPDATE `" . $tab . "` SET `pos`='".$pos2."' WHERE `id`=" . $id1;
        $this->db->query($sql);

        $sql = "UPDATE `" . $tab . "` SET `pos`='".$pos1."' WHERE `id`=" . $id2;
        $this->db->query($sql);


    }

    /**
     * функция удалет виджет с бд.     *
     * @param $id
     * @return null
     */
    public function delete($id)
    {
        $info = $this->getInfoById($id);
        $widget = $this->get($info['widget']);
        $widget->deleteBefore($info, $info['options']);

        $this->db->delete($this->config['dbTableWidgets'], intval($id));
        $this->db->delete($this->config['dbTableOptions'], 'widget_id =' .intval($id));

        $widget->deleteAfter($info, $info['options']);
    }

    public function deleteAllWidgetsByPageId($pageId)
    {
        $pageId = (int) $pageId;

        $sql    = 'SELECT * FROM '.$this->config['dbTableWidgets'].' WHERE object_id='.$pageId;


        $result = $this->db->query($sql);


        while($row = $result->fetch())
        {

            $this->delete($row['id']);
        }

    }



}