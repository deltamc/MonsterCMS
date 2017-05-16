<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');


class EventParam
{
    private $eventName;
    private $params = array();

    /**
     * @param $eventName - имя события
     */
    function __construct($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * Метод устанавливает параметры
     * @param $array - массив параметров
     */
    public function setParamArray(array $array)
    {
        $this->params = $array + $this->params;

    }

    /**
     * Метод устанавливает параметр
     * @param $key -ключ
     * @param $value - значение
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Метод возвращает массив параметров
     * @return array
     */
    public function getAllParams()
    {
        return $this->params;
    }

    /**
     * Метод возвращает значение параметра
     * @param $key
     * @return null
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return null;
    }

    /**
     * Метод возвращает имя события
     * @return string
     */
    public function getEventName(){
        return $this->eventName;
    }
}