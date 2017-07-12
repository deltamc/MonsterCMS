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
     *  @return string
     */
    public function getView();

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
     * Метод возвращает версию модуля Widget с которой он совместим
     * @return string
     */
    public function compatibility();


}