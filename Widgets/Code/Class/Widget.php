<?php namespace Monstercms\Widgets\Code;

use Monstercms\Core;
use Monstercms\Modules\Widgets as ModuleWidgets;

class Widget extends ModuleWidgets\WidgetAbstract implements ModuleWidgets\WidgetInterface
{


    /**
     * Метод возвращает массив с формой редактирования
     * @return array
     */
    public function getFormEdit()
    {


        return include(WIDGET_DIR . DS . $this->widgetName . DS . 'Form.php');
    }

    /**
     * Метод возвращает массив с формой добавления
     * @return array
     */
    public function getFormAdd()
    {
        return include(WIDGET_DIR . DS . $this->widgetName . DS . 'Form.php');
    }

    /**
     * Метод возвращает представления виджета
     * @param $vars - массив переменных которые видны в шаблоне
     *  @return string
     */
    public function getView(array $vars){
        return $this->view->get('View.php', $vars);
    }

    /**
     * Метод возвращает url иконки
     *  @return string
     */
    public  function getIco()
    {
        return '/Widgets/' . $this->widgetName . '/ico.png';
    }

    /**
     * Метод возвращает название виджета
     *  @return string
     */
    public function getName()
    {
        return "Код";
    }


    /**
     * Метод возвращает версию модуля Widget с которой он совместим
     * @return string
     */
    public function compatibility()
    {
        return '1.0';
    }

    /**
     * Размер окна в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getAddFormWindowSize()
    {
        return '800x600';
    }

    /**
     * Размер окна с формой редактирования виджета в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getEditFormWindowSize()
    {
        return '800x600';
    }

    /**
     * Массив с параметрами. ключ=>значение
     * Данный массив заполнит форму добавления виджета.
     * После заполнения формы добавления/редактирования виджета
     * пост параметры ($_POST) ключи которых совпадают с ключами параметров виджета,
     * значения параметров виджетов будут сохранены в БД
     * @return array
     */
    public function getParameters()
    {
        return array(
            'code' => '',
            'language' => '',
            'id' => '',
            'css_class' => '',
        );
    }


    /**
     * Массив с файлами JS
     * @return array
     */
    public function getJavaScript(){
        return array(
            '/' . WIDGET_DIR . '/' . $this->widgetName . '/highlight.pack.js'
        );
    }

    /**
     * Массив с файлами CSS
     * @return array
     */
    public function getCSS(){
        return array(
            '/' . WIDGET_DIR . '/' . $this->widgetName . '/zenburn.css'
        );
    }


    /**
     * Вес сортировки, чем больше вес тем ниже будет отображаться иконка виджета в панели
     * @return int
     */
    public function getOrder(){
        return 100;
    }
}