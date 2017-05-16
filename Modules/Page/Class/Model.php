<?php namespace Monstercms\Modules\Page;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;


class Model extends Core\ModelAbstract
{

    /**
     * Метод возвращает информацию об станице
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

        $url = new Core\Url();
        $url->delete($info->url_id);

        $this->config['medit']['widgets']['images']['path'] =
            $this->config['image_path'] . '/' . $id;

        $medit = new Lib\medit($id, $this->config['medit']);

        $medit->deleteWidgetsByGroupId($id);

        $dir = $this->config['image_path'] . DS . $id;

        if (file_exists($dir)) {
            foreach (glob($dir . DS . '*') as $file) {
                @unlink($file);
            }
            @rmdir($dir);
        }
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
            ->setAction('view')
            ->setObjectId($id);
        $url->update();

    }


    /**
     * Метод добавляет страницу
     * @param  string $name - название страницы
     * @param null $url - чпу
     * @return array
     */
    public function add($name, $url = null)
    {

        $data = array
        (
            'name'        => $name,
            'date_create' => time(),
            'date_update' => time(),
        );

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

        $dir = UPLOAD_ROOT . DS . $this->moduleName . DS . $id;
        mkdir($dir, 0777, true);

        return array('id' => $id,'url_id' => $url_id);


    }

    /**
     * Метод обновляет станицу
     * @param $name
     * @param $id
     * @throws \Exception
     */
    public function update($name, $id)
    {
        $id = (int) $id;

        $data = array
        (
            'name'        => $name,
            'date_update' => time(),
        );

        $this->db->update($data, $this->config['db_table'], $id);
    }


}