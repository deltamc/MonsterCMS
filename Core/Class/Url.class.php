<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;

class Url
{
    /**
     * @var \DataBase
     */
    private $db;
    private $dbTable;

    private $url      = null;
    private $module   = null;
    private $action   = null;
    private $objectId = null;
    private $urlId    = null;
    private $options  = null;


    function __construct()
    {
        $this->db = Mcms::DB();
        $this->dbTable = DB_TABLE_URL;
    }

    /**
     * Метод задает псевдоним URL
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Метод задает имя модуля
     * @param $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Метод задает экшен
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    /**
     * Метод задает Ид объекта, например, ид статьи или ид каталога
     * @param int $objectId
     * @return $this
     */
    public function setObjectId($objectId)
    {
        $this->objectId = (int) $objectId;
        return $this;
    }

    /**
     * Метод задает Ид псевдонима url
     * @param int $urlId
     * @return $this
     */
    public function setId($urlId)
    {
        $this->urlId = (int) $urlId;
        return $this;
    }

    /**
     * Метод задает дополнительные параметры Url
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Метод обновляет псевдоним url
     * Если $this->url пустой и задан $this->urlId, то псевдоним url удаляется из бд.
     * Если задан $this->url и задан $this->urlId, то обновляем URL в бд.
     * Если задан $this->url и НЕ задан $this->urlId, то добавляем URL в бд.
     *
     * @return int|null - ид псевдонима URL
     */
    public function update()
    {
        // Если $this->url пустой и задан $this->urlId,
        // то псевдоним url удаляется из бд
        if (empty($this->url) && (int) $this->urlId !== 0) {
            $this->delete($this->urlId);
            return null;
        }

        // Если задан $this->url и задан $this->urlId,
        // то обновляем URL в бд.
        if (!empty($this->url) && (int) $this->urlId !== 0)
        {

            $list = array
            (
                'url'       => $this->url,
                'action'    => $this->action,
                'module'    => $this->module,
                'object_id' => $this->objectId
            );

            if (!empty($this->options)){
                $options = serialize($this->options);
                $list['options'] = $options;
            }

            $result = $this->db->update($list, $this->dbTable, $this->urlId);

            if ($result !== false) {
                return $this->db->lastInsertId();

            }

            return null;



        }

        // Если задан $this->url и НЕ задан $this->urlId,
        // то добавляем URL в бд
        if (!empty($this->url) && (int) $this->urlId === 0)
        {
            $urlId = $this->add();
            return $urlId;
        }

        return null;
    }


    /**
     * Метод добавляет новый псевдоним url
     * @return null
     */
    public function add()
    {
        if ($this->module    === null
            || $this->action === null
            || $this->url    === null
        ) {
            return false;
        }

        if(!empty($this->options)) {
            $options = serialize($this->options);
        } else {
            $options = '';
        }

        $list = array
        (
            'id'        => 'NULL',
            'options'   => $options,
            'module'    => $this->module,
            'action'    => $this->action,
            'url'       => $this->url,
            'object_id' => $this->objectId
        );


        $result = $this->db->insert($list, $this->dbTable);

        if ($result !== false) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }

    }

    /**
     * Mетод удаляет псевдоним url из бд
     * @param $id - ид
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->db->delete($this->dbTable, $id);

        if($result === false) {
            return false;
        }

        return true;
    }

    /**
     * Информация об виде массива где:
     * id_url - Ид псевдонима URL
     * module - Модуль
     * action - экшен
     * object_id - объект ид
     * url - псевдоним URL
     *
     * @return array|null
     */
    public function info()
    {
        $where = null;

        if ($this->url !== null) {
            $where = "`url` = :url";
        } else if($this->urlId !== null) {
            $where = "`id` = :id";
        } else {
            return null;
        }

        $sql = "SELECT * FROM `{$this->dbTable}` WHERE {$where}";

        $stmt = $this->db->prepare($sql);

        if ($this->url !== null) {
            $stmt->bindParam(':url', $this->url,  \PDO::PARAM_STR);
        } else if($this->urlId !== null) {
            $stmt->bindParam(':id', $this->urlId, \PDO::PARAM_INT);

        }

        if(!$stmt->execute()) {
            return null;
        }

        $urlObj = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($urlObj === false) {
           return false;
        }

        $options = unserialize($urlObj->options);

        $out = array();
        $out['id']    = $urlObj->id;
        $out['module']    = $urlObj->module;
        $out['action']    = $urlObj->action;
        $out['object_id'] = $urlObj->object_id;
        $out['url']       = $urlObj->url;

        if (!empty($options)) {
            $out = array_merge($out, $options);
        }

        return $out;
    }

    /**
     * Метод возвращает псевдоним URL
     * @param $urlId - ид псевдонима
     * @return null|object
     */
    public function getUrl($urlId)
    {

        $sql = "SELECT * FROM `{$this->dbTable}` WHERE `id` = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $urlId, \PDO::PARAM_INT);

        $result = $stmt->execute();
        if(!$result) return null;

        $urlObj = $stmt->fetch(\PDO::FETCH_OBJ);

        return $urlObj->url;
    }

    /**
     * Метод проверяет существует ли заданный псевдоним URL.
     * Если передан Ид псевдонима URL,
     * то исключаем принадлежность данного урл к переданному Ид
     *
     * @param $url
     * @param null $urlId
     * @return bool
     */
    public function urlValid($url, $urlId = null)
    {
        $sql = 'SELECT `id` FROM ' . DB_TABLE_URL . ' WHERE ';

        $url = $this->db->quote($url);

        if($urlId != null)
        {
            $urlId = (int) $urlId;
            $sql .= "`id` <> {$urlId} AND `url`={$url}";

        }
        else
        {
            $sql .= "`url`={$url}";

        }

        $resurl = $this->db->query($sql);


        if (!$resurl->fetch(\PDO::FETCH_NUM)) {
            return true;
        }

        return false;
    }
}

