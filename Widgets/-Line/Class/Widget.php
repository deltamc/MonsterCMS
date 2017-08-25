<?php namespace Monstercms\Widgets\Line;

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

    }

    /**
     * Метод возвращает массив с формой добавления
     * @return array
     */
    public function getFormAdd()
    {

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
        return "Линия";
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
        return false;
    }

    /**
     * Размер окна с формой редактирования виджета в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getEditFormWindowSize()
    {
        return false;
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
            'heading' => '',
            'level'  => 1
        );
    }


    /**
     * Вес сортировки, чем больше вес тем ниже будет отображаться иконка виджета в панели
     * @return int
     */
    public function getOrder(){
        return 0;
    }


    /**
     * Массив с файлами JS
     * @return array
     */
    public function getJavaScript(){
        return array();
    }

    /**
     * Массив с файлами CSS
     * @return array
     */
    public function getCSS(){
        return array();
    }

}