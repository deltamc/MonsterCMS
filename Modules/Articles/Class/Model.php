<?php namespace Monstercms\Modules\Articles;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;


class Model extends Core\ModelAbstract
{

    const CREATE_DESC = '`a`.`id` DESC';
    const CREATE_ASC = '`a`.`id`';
    /**
     * Метод добавляет раздел статей
     * @param  string $name - название раздела
     * @param null $url - чпу
     * @return array
     */
    public function addCatalog($name, $url=null, $textTop=null, $textBottom=null, $itemMenuId=0)
    {

        $table = $this->config['db_catalog'];

        $data = array
        (
            'name'   => $name,
            'url_id' => 'NULL',
            'text_top' => $textTop,
            'text_bottom' => $textBottom,
            'menu_item_id' => $itemMenuId,

        );

        $this->db->insert($data, $table);

        $id     =  $this->db->lastInsertId();

        $url_id = null;

        if (!is_null($url)) {

            $urlObj = new Core\Url();
            $urlObj->setUrl($url)
                ->setModule($this->moduleName)
                ->setAction('View')
                ->setObjectId($id);
            $url_id = $urlObj->add();

            $this->db->update(array('url_id' => $url_id), $table, $id);
        }

        return array('id' => $id,'url_id' => $url_id);
    }

    public function updateCatalog($id, $name = null,  $textTop = null, $textBottom = null, $itemMenuId = null)
    {
        $id = (int) $id;
/*
        $data = array
        (
            'name'        => $name,
            'text_top'    => $textTop,
            'text_bottom' => $textBottom,
            'menu_item_id' => $itemMenuId,
        );
*/
        $data = array();
        if ($name !== null)       $data['name'] = $name;
        if ($textTop !== null)    $data['text_top'] = $textTop;
        if ($textBottom !== null) $data['text_bottom'] = $textBottom;
        if ($itemMenuId !== null) $data['menu_item_id'] = $itemMenuId;

        if(empty($data)) return;

        $table = $this->config['db_catalog'];

        $this->db->update($data, $table, $id);
    }

    public function info($id)
    {

        $table = $this->config['db_catalog'];
        return $this->db->getObject($table, $id);
    }

    public function articleInfo($id)
    {
        $table    = $this->config['db_articles'];
        $tableUrl = DB_TABLE_URL;
        $tablePage = Core\Module::get('Page')->getTableDb();

        $id = (int) $id;
        $sql = "SELECT  `p`.`id` AS page_id,
                        `u`.url,
                        `a`.id AS article_id,
                        `u`.id AS url_id,
                        `a`.name,
                        `a`.preview,
                        `a`.menu_item_id
                FROM
                        {$table} `a`
                        INNER JOIN {$tablePage} as `p` ON a.page_id = p.id
                        INNER JOIN {$tableUrl} as `u` ON u.id = p.url_id
                WHERE a.id ={$id}";

        $result = $this->db->query($sql);
        return $result->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Метод обновляет адрес страницы
     * @param $id
     * @param $newUrl
     */
    public function urlUpdate($id, $newUrl)
    {
        $table = $this->config['db_catalog'];

        $catalog = $this->db->getObject($table, $id);

        //Core\Mcms::urlUpdate((int) $page->url_id, $url,
        // $this->_module, 'view',$id);

        $url = new Core\Url();

        $url->setId((int) $catalog->url_id)
            ->setUrl($newUrl)
            ->setModule($this->moduleName)
            ->setAction('View')
            ->setObjectId($id);
        $url->update();

    }

    public function addArticle($menuItemId, $pageId, $name, $preview, $catalogId = null)
    {
        if ($catalogId === null) $catalogId = Core\Module::get('Site')->getObjectIdByItemId($menuItemId);

        $insert = array(
            'name'         => $name,
            'menu_item_id' => $menuItemId,
            'catalog_id'   =>  Core\Module::get('Site')->getObjectIdByItemId($menuItemId),
            'preview'      => $preview,
            'page_id'      => $pageId
        );
        $table = $this->config['db_articles'];

        $this->db->insert($insert, $table);

        return $this->db->lastInsertId();
    }

    public function editArticle($id, $menuItemId, $name, $preview)
    {
        $data = array(
            'name'         => $name,
            'menu_item_id' => $menuItemId,
            'catalog_id'   =>  Core\Module::get('Site')->getObjectIdByItemId($menuItemId),
            'preview'      => $preview
        );
        $table = $this->config['db_articles'];

        $this->db->update($data, $table, $id);


    }

    /**
     * @param $catalogId
     * @return Lib\Pagination
     * @throws \Exception
     */
    public function pageList($catalogId)
    {
        $table    = $this->config['db_articles'];
        $tableUrl = DB_TABLE_URL;
        $tablePage = Core\Module::get('Page')->getTableDb();
        $catalogId = (int) $catalogId;

        $orderBy = $this->config['order_by'];

        $sql = "SELECT  `p`.`id` AS page_id,
                        `u`.url,
                        `a`.id AS article_id,
                        `u`.id AS url_id,
                        `a`.name,
                        `a`.preview
                FROM
                        {$table} `a`
                        INNER JOIN {$tablePage} as `p` ON a.page_id = p.id
                        INNER JOIN {$tableUrl} as `u` ON u.id = p.url_id
                WHERE a.catalog_id = {$catalogId} ORDER BY {$orderBy}
          ";



        $pagination = new Lib\Pagination($this->db, $sql, $this->config['number_on_page']);
        $pagination->setTplLink($this->config['pagination_link_tpl']);
        $pagination->setTplLinkThis($this->config['pagination_link_tpl_this']);
        $items = $pagination->getItems();

        return array(
            'pagination' => $pagination->getNavigation(),
            'items'      => $items,
        );


    }

    public function pageListAll() {

        $table         = $this->config['db_articles'];
        $tableUrl      = DB_TABLE_URL;
        $tableSemantic = DB_TABLE_PAGE_SEMANTIC;
        $tablePage     = Core\Module::get('Page')->getTableDb();


        $orderBy = $this->config['order_by'];

        $sql = "SELECT  `p`.`id` AS page_id,
                        `u`.url,
                        `a`.id AS article_id,
                        `u`.id AS url_id,
                        `a`.name,
                        `a`.preview,
                        `ps`.*
                FROM
                        {$table} `a`
                        INNER JOIN {$tablePage} as `p` ON a.page_id = p.id
                        INNER JOIN {$tableUrl} as `u` ON u.id = p.url_id
                        LEFT JOIN {$tableSemantic} as `ps` ON ps.module = 'Page' AND ps.object_id = p.id
          ";

        $result = $this->db->query($sql);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteArticle($id){
        $id = (int) $id;
        $tArticle  = $this->config['db_articles'];
        //$tCatalog  = $this->config['db_catalog'];
        $articleInfo = $this->articleInfo($id);

        $this->db->delete($tArticle, $id);
        Core\Module::get('Page')->delete($articleInfo->page_id);

        return $articleInfo;

    }

    public function deleteCatalog($id)
    {
        $id = (int) $id;
        $info = $this->info($id);
        $table = $this->config['db_catalog'];

        $url = new Core\Url();
        $url->delete($info->url_id);


        $this->db->delete($table, $id);
    }


}