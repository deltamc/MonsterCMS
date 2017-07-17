<?php namespace Monstercms\Core;

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





}