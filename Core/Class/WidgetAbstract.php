<?php namespace Monstercms\Core;

use Monstercms\Lib;

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

    function __construct($widgetName)
    {
        $this->widgets = Module::get('Widgets');
        $this->widgetsConfig = $this->widgets->getConfig();
        $this->db = Mcms::DB();

        $this->widgetName = $widgetName;

        $this->view = new Lib\View(array(
            THEMES_DIR_MAIN . DS . THEME . DS . 'Widgets' . DS . $this->widgetName,
            WIDGET_DIR  . DS . $this->widgetName . DS . 'Views'
        ));
    }

    /**
     * Вызывается после передачи POST формы добавления виджета на страницу
     */
    public function add() {}

    /**
     * Вызывается после передачи POST формы редактирования виджета на странице
     */
    public function edit() {}

    /**
     * Вызывается перед удалением из бд
     */
    public function deleteBefore(){}

    /**
     * Вызывается после удалением из бд
     */
    public function deleteAfter() {}

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
     */
    public function getJs()
    {
        return array();
    }

    public function getWidgetName()
    {
        return $this->widgetName;
    }




}