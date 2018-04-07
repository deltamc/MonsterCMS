<?php namespace Monstercms\Modules\Page;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;


class Model extends Core\ModelAbstract
{

    /**
     * Метод возвращает информацию об странице
     * @param $pageId
     * @return mixed|null
     */
    public function info($pageId)
    {
        return $this->db->getObject($this->dbTable, $pageId);
    }

    /**
     * Метод удаляет страницу
     * @param $pageId
     * @throws \Exception
     */
    public function delete($pageId)
    {
        $id = (int) $pageId;

        $info = $this->db->getObject($this->dbTable, $pageId);

        if (empty($info)) return;

        $pageHead = Core\PageSemantic::init();
        $pageHead->delete($this->moduleName, $id);

        Core\Module::get('Widgets')->deleteAllWidgetsByPageId($id);

        $url = new Core\Url();
        $url->delete($info->url_id);
        $this->db->delete($this->config['db_table'], $id);





    }

    /**
     * Метод обновляет адрес страницы
     * @param $id
     * @param $newUrl
     */
    public function urlUpdate($id, $newUrl)
    {
        $page = $this->db->getObject($this->dbTable, $id);

        //Core\Mcms::urlUpdate((int) $page->url_id, $url,
        // $this->_module, 'view',$id);

        $url = new Core\Url();

        $url->setId((int) $page->url_id)
            ->setUrl($newUrl)
            ->setModule($this->moduleName)
            ->setAction('View')
            ->setObjectId($id);
        $url->update();

    }


    /**
     * Метод добавляет страницу
     * @param  string $name - название страницы
     * @param null $url - чпу
     * @param $module - принадлежность страницы к модулю
     * @param $objectId - ид связанного объекта, например, каталога статьи
     * @return array
     */
    public function add($name, $url = null, $module = null, $objectId = null)
    {

        $data = array
        (
            'name'        => $name,
            'date_create' => time(),
            'date_update' => time(),
            'url_id'      => 'NULL',
        );

        if ($module   !== null) $data['module']    = $module;
        if ($objectId !== null) $data['object_id'] = $objectId;

        $this->db->insert($data, $this->dbTable);

        $id     =  $this->db->lastInsertId();


        $url_id = null;

        if (!is_null($url)) {
            //$url_id = Core\Mcms::urlAdd($url, $this->_module, 'view', $id);

            $urlObj = new Core\Url();
            $urlObj->setUrl($url)
                    ->setModule($this->moduleName)
                    ->setAction('View')
                    ->setObjectId($id);
            $url_id = $urlObj->add();

            $this->db->update(array('url_id' => $url_id), $this->dbTable, $id);
        }

        /*
        $dir = UPLOAD_ROOT . DS . $this->moduleName . DS . $id;
        mkdir($dir, 0777, true);
        */
        return array('id' => $id,'url_id' => $url_id);


    }

    /**
     * Метод обновляет станицу
     * @param $name
     * @param $id
     * @param $module - принадлежность страницы к модулю
     * @param $objectId - ид связанного объекта, например, каталога статьи
     * @throws \Exception
     */
    public function update($name, $id, $module = null, $objectId = null)
    {
        $id = (int) $id;

        $data = array
        (
            'name'        => $name,
            'date_update' => time(),
            'module'      => $module,
        );

        if ($module   !== null) $data['module']    = $module;
        if ($objectId !== null) $data['object_id'] = $objectId;

        $this->db->update($data, $this->config['db_table'], $id);
    }


}