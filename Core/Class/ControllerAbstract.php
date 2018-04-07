<?php namespace  Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib as Lib;

/**
 * Абстрактный класс ControllerAbstract, данный класс наследуют классы-контроллеры модулей
 */
abstract class ControllerAbstract
{
    /**
     * @var Lib\DataBase
     * Экземпляр класса Lib\DataBase для работы с бд
     */
    protected $db;
    /**
     * @var Lib\View
     * Экземпляр Lib\View - класс для работы с видами
     */
    protected $view;
    //protected $user;
    //protected $user_is;
    //protected $tag;

    /**
     * @var array конфигурация модуля. Данные берутся из файла Modules/Модуль/Config.php
     *
     */
    public $config = array();
    /**
     * @var string - мия модуля
     */
    protected $moduleName;
    /**
     * @var string - путь к папке с модулем
     */
    protected $modulePath;

    //protected $controllers = array();

    /**
     * @var ModelAbstract - экземпляр класса модели. Если у модуля есть класс Model,
     * то автоматические присваивается экземпляр данного класса .
     */
    protected $model;
    /**
     * @var array - массив с экземпляров классов моделей
     */
    protected $models = array();

    /**
     * @var int - ид сущности
     *
     */
    protected $objectId;

    /**
     * @var array параметры, которые берутся из адресной
     * (передается из класса FrontController)
     * строки /Модуль/Экшен/key1/value1/...keyN/valueN/
     */
    protected $params = array();

    function __construct($moduleName)
    {
        $this->moduleName = $moduleName;


        $this->view = new Lib\View(array(
            THEMES_DIR_MAIN . DS . THEME . DS . 'Modules' . DS . $this->moduleName,
            MODULE_DIR  . DS . $this->moduleName . DS . 'Views'
        ));

        $this->db = Mcms::DB();

        $this->modulePath =  MODULE_DIR . DS . $this->moduleName . DS;

        $conf_file = $this->modulePath . 'Config.php';

        if (is_file($conf_file)) {
            $this->config = include($conf_file);
        }

        $modelName = '\\Monstercms\\Modules\\' . $moduleName . '\\Model';

        if (class_exists($modelName)) {
            $this->model = new $modelName($this->config, $this->db);
            $this->models['Model'] = $this->model;
        }
    }

    /**
     * Метод подключает модель
     * @param $modelName
     * @return mixed
     * @throws \Exception
     */
    protected function model($modelName)
    {

        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }

        $modelName = '\\Monstercms\\Modules\\'.$this->moduleName . '\\' . $modelName;

        if (!class_exists($modelName)) {
            throw new \Exception("Class '" . $modelName . "' not found");
        }

        $model = new $modelName($this->config, $this->db);
        $this->models[$modelName] = $model;

        return $model;
    }

    /**
     * Метод устанавливает параметры URL
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Метод возвращает параметры URL
     * @return array $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Метод возвращает параметры URL по ключу
     * @param string - ключ параметра
     * @return array $params
     */
    protected function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }

    /**
     * Метод проверяет на существования параметра
     * @param $key - ключ параметра
     * @param bool|true $empty - может ли параметр быть пустым
     * @return bool
     */
    protected function isParam($key, $empty = true) {
        if (isset($this->params[$key])){
            if (!$empty) {
                return true;
            } else {
                return !empty($this->params[$key]);
            }
        }
        return false;
    }

    /**
     * Метод возвращает ид объекта
     * @return int
     */
    public function getObjectId()
    {
        return (int) $this->objectId;
    }

    /**
     * Метод устанавливает ид объекта
     * @param $objectId - ид объекта
     * @return int
     */
    public function setObjectId($objectId)
    {
        $this->objectId = (int) $objectId;
    }

    /**
     * Магический метод который, в случаи отсутствие
     * метода-контроллера пытается подключить файл modules/Модуль/actions/контроллер
     * @param $method
     * @param $arg
     * @return mixed
     * @throws HttpErrorException
     */
    public function  __call($method, $arg)
    {
        if (!empty($arg) && is_array($arg)) {
            $this->setParams($arg);
        }

        $method = preg_replace('/action$/i', '', $method);

        $method = ucfirst($method);

        $file = $this->modulePath . 'Actions' . DS . $method.'.php';

        if (!file_exists($file)) {
            throw new HttpErrorException(404);
        }

        return include($file);
    }

}