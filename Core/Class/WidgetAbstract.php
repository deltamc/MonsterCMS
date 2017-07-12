<?php namespace Monstercms\Core;


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
     * настойки модуля WIDGET
     * @var array
     */
    protected $widgetsConfig = array();

    function __construct()
    {
        $this->widgets = Module::get('Widgets');
        $this->widgetsConfig = $this->widgets->getConfig();
        $this->db = Mcms::DB();
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
    public function getJs(){
        return array();
    }



}