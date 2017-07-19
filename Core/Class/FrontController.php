<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\HttpErrorException;
use Monstercms\Core\Url;


class FrontController
{
    protected $module;
    protected $action;
    protected $params = array();
    protected $body;
    protected $objectId;

    function __construct()
    {
        $this->handleRequest();
    }

    /**
     * Метод устанавливает свойства класса, значениями зависящими от URI
     */
    protected function handleRequest()
    {
        $this->module = DEFAULT_MODULE;
        $this->action = DEFAULT_ACTION;

        $request = $_SERVER['REQUEST_URI'];

        //удаляем GET
        $request = preg_replace('/\?(.*)$/','',$request);

        $params = array();

        if (preg_match('/' . URL_SEMANTIC_END . '$/i', $request)) {
            $params = $this->getParamsSemanticUrl($request);
        }

        if (!isset($params['module'])) {

            $params = $this->getParamsUrlPatch($request);
        }

        $this->params = $params['params'];

        $reg = '/[^\w-_0-9]/';

        foreach ($_GET as $key => $value) {
            if ($key === 'module' || $key === 'action') {
                $params[$key] = $value;
                continue;
            }

            $this->params[$key] = $value;
        }

        if (!empty($params['module'])) {
            $this->module = preg_replace($reg, '', $params['module']);
            $this->module = ucfirst($this->module);
        }

        if (!empty($params['action'])) {
            $this->action = preg_replace($reg, '', $params['action']);
        }

        if ($params['objectId']) {
            $this->objectId = (int) $params['objectId'];
        }


    }

    /**
     * Метод разбирает URI типа /module/action[/key 1][/value 1]...
     *
     * Возвращает массив c ключами:
     * 'params'    - массив с параметрами [key]=>['value']...
     * 'module'    - имя модуля
     * 'action'    - имя экшена
     * 'objectId'  - ид объекта, например, ид статьи.
     *               Данное свойство берется с параметра
     *               у которого ключь - id
     *
     * @param $request
     * @return array
     */
    protected function getParamsUrlPatch($request)
    {

        $params   = array();
        $module   = null;
        $action   = null;
        $objectId = null;

        $splits = explode('/', trim($request, '/'));



        if (!empty($splits[0])) {
            $module =  $splits[0];
        }
        /*
        else if($_REQUEST['module']) {
            $this->module = preg_replace($reg, '', $_REQUEST['module']);
        }
        */

        if (!empty($splits[1])) {
            $action = $splits[1];
        }/* else if(!empty($_REQUEST['action'])) {
            $this->action = preg_replace($reg, '', $_REQUEST['action']);
        }*/

        //Есть ли параметры и их значения?
        if (!empty($splits[2])) {
            $keys = $values = array();
            for ($i=2, $cnt = count($splits); $i<$cnt; $i++){
                if ($i % 2 == 0){
                    //Чётное = ключ (параметр)
                    $keys[] = $splits[$i];
                } else {
                    //Значение параметра;
                    $values[] = $splits[$i];
                }
            }

            foreach ($keys as $key) {
                $value = each($values);

                if ($value !== false) {
                    $value = $value['value'];
                }
                if ($key === 'id') {
                    $objectId  = (int) $value;
                }
                $params[$key] = $value;
            }
        }

        return array(
            'params'    => $params,
            'module'    => $module,
            'action'    => $action,
            'objectId' =>  $objectId
        );
    }
    /**
     * Метод разбирает псевдоним Url
     *
     * Возвращает массив c ключами:
     * 'params'    - массив с параметрами [key]=>['value']...
     * 'module'    - имя модуля
     * 'action'    - имя экшена
     * 'objectId'  - ид объекта, например, ид статьи.
     *               Данное свойство берется с параметра
     *               у которого ключь - id
     *
     * @param $request
     * @return array
     */
    protected function getParamsSemanticUrl($request)
    {

        $params = array();
        $module = null;
        $action = null;
        $objectId = null;

        $request = preg_replace('/' . URL_SEMANTIC_END . '$/i', '', $request);
        $request = preg_replace('/^\//', '', $request);

        $url = new Url();
        $url->setUrl($request);
        $info = $url->info();

        if (!empty($info)) {
            $module   = $info['module'];
            $action   = $info['action'];
            $objectId = $info['object_id'];

            foreach ($info as $key => $value) {
                if ($key === 'module'
                    || $key === 'action'
                    || $key === 'object_id'
                    || $key === 'id'
                    || $key === 'url'
                ) {
                    continue;
                }
                $params[$key] = $value;
            }
        }

        return array(
            'params'    => $params,
            'module'    => $module,
            'action'    => $action,
            'objectId'  => $objectId,
        );
    }


    /**
     * Метод вызывает методы контроллеров
     * @throws \Exception
     * @throws \Monstercms\Core\HttpErrorException
     */
    public function route()
    {
        Module::initAll();

        if (Module::isModule($this->getModule())) {

            $module = Module::set($this->getModule());

            $module->setParams($this->getParams());
            $module->setObjectId($this->objectId);

            call_user_func(array($module, $this->getAction() . 'Action'));
        } else {
            throw new HttpErrorException(404);
        }
    }

    /**
     * Метод возвращает парметры URL
     * @return array
     */
    private function getParams()
    {
        if (empty($this->params)) {
            return array();
        }
        return $this->params;
    }

    /**
     * Метод возвращает Ид объекта
     * @return mixed
     */
    private function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Метод возвращает имя модуля
     * @return mixed
     */
    private function getModule()
    {
        return $this->module;
    }

    /**
     * Метод возвращает имя экшена
     * @return mixed
     */
    protected function getAction()
    {
        return $this->action;
    }


    public function getBody()
    {
        return $this->body;
    }



}