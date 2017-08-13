<?php namespace Monstercms\Modules\Widgets;

interface WidgetInterface
{
    /**
     * Метод возвращает массив с формой редактирования

     * @return array
     */
    public function getFormEdit();

    /**
     * Метод возвращает массив с формой добавления
     * @return array
     */
    public function getFormAdd();

    /**
     * Метод возвращает представления виджета
     * @param $vars - массив переманных которые видны в шаблоне
     *  @return string
     */
    public function getView(array $vars);

    /**
     * Метод возвращает url иконки
     *  @return string
     */
    public  function getIco();

    /**
     * Метод возвращает название виджета
     *  @return string
     */
    public function getName();

    /**
     * Размер окна в формате wxh, например: 700x800
     * если метод возвращает пустое заначение или false, то окно не отображается
     * @return string|false
     */
    public function getWindowSize();

    /**
     * Массив с параметрами. ключ=>значение
     * - Данный массив заполнит форму добавления виджета.
     * - После заполнения формы добавления/редактирования виджета
     * пост параметры ($_POST) ключи которых совпадают с ключами параметров виджета,
     * значения параметров виджетов будут сохранены в БД
     * - Параметры доступны в шаблоне (view) виджета виде переменных
     * @return array
     */
    public function getParameters();

    /**
     * Метод возвращает мия виджета (должен совпадать с именем директорией)
     * @return mixed
     */
    public function getWidgetName();





}