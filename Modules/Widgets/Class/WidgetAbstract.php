<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;

abstract class WidgetAbstract
{
    /**
     * Экземпляр контроллера модуля Widget
     * @var ControllerAbstract
     */
    protected $widgets;
    /**
     * @var \Monstercms\Lib\DataBase
     */
    protected $db;

    /**
     * @var Lib\View
     */
    protected $view;

    /**
     * настойки модуля WIDGET
     * @var array
     */
    protected $widgetsConfig = array();

    protected $pageId;

    function __construct($widgetName, $pageId = null)
    {

        $this->pageId = $pageId;
        $this->widgets = Core\Module::get('Widgets');
        $this->widgetsConfig = $this->widgets->getConfig();
        $this->db = Core\Mcms::DB();

        $this->widgetName = $widgetName;

        $this->view = new Lib\View(array(
            THEMES_DIR_MAIN . DS . THEME . DS . 'Widgets' . DS . $this->widgetName,
            WIDGET_DIR  . DS . $this->widgetName . DS
        ));
    }

    /**
     * Вызывается перед добвлением виджета в бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function addBefore(array $data, array $params) {}

    /**
     * Вызывается после добавления виджета в бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function addAfter(array $data, array $params) {

    }

    /**
     * Вызывается перед внесением изменений в бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function editBefore(array $data, array $params) {}

    /**
     * Вызывается после внесения изменений в бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function editAfter(array $data, array $params) {}



    /**
     * Вызывается перед удалением из бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function deleteBefore(array $data, array $params){}

    /**
     * Вызывается после удалением из бд
     * @param array $data - массив с информацией об виджете
     * [
     *  'widget' - имя виджета
     *  'cache'  - html код виджета
     *  'pos'    - позиция
     *  'object_id' - ид станицы
     * ]
     * @param array $params - параметры виджета (ключ=>значения)
     */
    public function deleteAfter(array $data, array $params) {}

    /**
     * Метод возвращает массив настроек виджета
     * @return array
     */
    public function getConfig()
    {
        return array();
    }

    /**
     * Метод возвращает список файлов-скриптов js, которые нужно подключить при
     * отображении виджетов на странице
     * @return array

    public function getJs()
    {
        return array();
    }
*/



    public function getWidgetName()
    {
        return $this->widgetName;
    }


    /**
     * Массив с файлами JS
     * @return array
     */
    public function getJavaScript(){}

    /**
     * Массив с файлами CSS
     * @return array
     */
    public function getCSS(){}






}