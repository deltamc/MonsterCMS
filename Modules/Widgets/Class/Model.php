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

    /**
     * получить массив экземпляров контроллеров виджетов
     * @return array
     */
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
     * Метод проверяет существует ли виджет
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
        while ($widgetName = readdir($dir)) {
            if ($widgetName == '.'
                || $widgetName == '..'
                || !is_dir(ROOT . DS . $this->config['widgetDir'] . DS . $widgetName)) {
                continue;
            }

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

    /**
     * Добавление виджета на страницу
     * @param WidgetInterface $widget
     * @param array $data
     * @param null $pageId
     * @return array
     * @throws \Exception
     */
    function add(WidgetInterface $widget, array $data, $pageId = null)
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

        $pos = 0;
        if ($pageId !== null) {
            $pos        = $this->getNextMaxPos($pageId);
        }

        $cssClass   = '';
        if (isset($data['css_class'])) {
            $cssClass = htmlspecialchars($data['css_class']);
        }

        $list = array(
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'page_id'   => $pageId,
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

        $out = array(
            'id'        => $widgetId,
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $pos,
            'page_id'   => $pageId,
            'css_class' => $cssClass,
        );

        //Вызываем событие
        Core\Events::cell(
            'Widgets.addWidget',
            'void',
            $out
        );

        return $out;

    }

    function getNextMaxPos($pageId = null){
        if (is_null($pageId)) return 0;

        $pageId = (int) $pageId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT max(`pos`) FROM {$table}  WHERE page_id=" . $pageId;

        $result = $this->db->query($sql);
        $pos    = $result->fetch();

        return $pos[0] +1;
    }

    public function widgetsList($pageId)
    {
        $pageId = (int) $pageId;

        $table = $this->config['dbTableWidgets'];
        $sql = "SELECT * FROM {$table}  WHERE page_id={$pageId} ORDER BY `pos`";

        $result = $this->db->query($sql);

        $list = $result->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($list as &$item) {
            $widgetObj = $this->get($item['widget']);
            $item['javascript'] = $widgetObj->getJavaScript();
            $item['css']        = $widgetObj->getCSS();
            $item['window_size'] = $widgetObj->getEditFormWindowSize();
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
            'page_id'   => $info['page_id'],
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

        $out = array(
            'id'        => $widgetId,
            'widget'    => $widgetName,
            'cache'     => $cache,
            'pos'       => $info['pos'],
            'page_id'   => $info['page_id'],
            'css_class' => $cssClass,
        );

        //Вызываем событие
        Core\Events::cell(
            'Widgets.editWidget',
            'void',
            $out
        );

        return $out;
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

        //Вызываем событие
        Core\Events::cell(
            'Widgets.deleteBeforeWidget',
            'void',
            $info
        );

        $widget->deleteBefore($info, $info['options']);

        $this->db->delete($this->config['dbTableWidgets'], intval($id));
        $this->db->delete($this->config['dbTableOptions'], 'widget_id =' .intval($id));

        $widget->deleteAfter($info, $info['options']);

        //Вызываем событие
        Core\Events::cell(
            'Widgets.deleteAfterWidget',
            'void',
            $info
        );
    }

    /**
     * Метод удаляем все виджеты на странице
     * @param $pageId
     */
    public function deleteAllWidgetsByPageId($pageId)
    {
        $pageId = (int) $pageId;

        $sql = 'SELECT * FROM '.$this->config['dbTableWidgets'].' WHERE page_id=' . $pageId;

        $result = $this->db->query($sql);

        while ($row = $result->fetch()) {
            $this->delete($row['id']);
        }

    }



}