<?php namespace  Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib as Lib;

abstract class ControllerAbstract
{
    protected $db;
    protected $view;
    protected $user;
    protected $user_is;
    protected $tag;
    public $config = array();
    protected $moduleName;
    protected $modulePath;
    protected $controllers;
    protected $model;
    protected $models = array();
    protected $objectId;

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
     * Метод возвращает ид объекта
     * @return int
     */
    public function getObjectId()
    {
        return (int) $this->objectId;
    }

    /**
     * Метод устанавливает ид объекта
     * @return int
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
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
        //$id         = (!isset($arg[0]) || intval($arg[0]) == 0) ? 0: intval($arg[0]);
        //$url_option = (!isset($arg[1]) || !is_array($arg[1])) ? array() : $arg[1];

        $method = preg_replace('/action$/i', '', $method);

        $file = $this->modulePath . 'Actions' . DS . $method.'.php';

        if (!file_exists($file)) {
            throw new HttpErrorException(404);
        }


        return include($file);
    }

}