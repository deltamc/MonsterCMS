<?php namespace Monstercms\Modules\Articles;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\Lang;
use \Monstercms\Lib;
use \Monstercms\Lib\Request;
use Monstercms\Core\MCMS;
use Monstercms\Core\Module;
use \Monstercms\Core\User;

class Controller extends Core\ControllerAbstract
{
    function menuItemAddModuleList(Core\EventParam $ep)
    {
        return array(
            'module'                => $this->moduleName,
            'itemType'              => 'articles',
            "menuItemName"        => Core\Lang::get('Articles.menuItemName'),
            /* which fields form hide (form add item menu)*/
            "hiddenFormItems"     => array("menu_item_url"),
            "menuItemIcon"        => "fa fa-files-o",

        );
    }


    /**
     * Метод вызывается при событии site.addFormTabBaseAfter.
     * Данное событие вызывается при генерации формы добавление
     * нового пункта меню в менеджере страниц.
     *
     * @param $ep
     *
     * @return null|array - элементы формы
     */
    public function eventMenuItemForm(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');

        if($moduleName != $this->moduleName) return null;

        $formSeo       =  Module::get('PageSemantic')->getSeoForm();
        $formArticle   =  include($this->modulePath . DS . 'Forms' . DS . 'ArticlesText.php');

        //Получаем данные формы с других модулей

        $formArticle = Core\Events::eventsForm(
            $formArticle
        );

        $formTheme     =  Module::get('PageSemantic')->getThemeForm();

        return array_merge($formArticle, $formSeo, $formTheme);
    }


    /**
     * Метод вызывается при событии site.menuItemAddSave.
     * Данное событие вызывается при сохранении данных из
     * формы добавлении пункта меню в менеджере страниц.
     * @param $ep - экземпляр класса Core\EventParam
     * @return null|array('object_id' => page_id, 'url_id' => page_url_id );
     */
    public function eventMenuItemAddFormSave(Core\EventParam $ep)
    {

        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $url        = $ep->getParam('url');

        if($moduleName != $this->moduleName) return null;


        $name       = Request::getPost('menu_item_name');
        $textTop    = Request::getPost('textTop', true);
        $textBottom = Request::getPost('textBottom', true);

        $article = $this->model->addCatalog($name, $url, $textTop, $textBottom);



        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $article['id'], time());

        return array('object_id' => $article['id'], 'url_id' => $article['url_id'] );
    }


    public function eventMenuItemAddFormSaveEnd(Core\EventParam $ep)
    {

        $id         = $ep->getParam('objectId');
        $itemMenuId = $ep->getParam('itemMenuId');

        $this->model->updateCatalog($id, null,  null, null, $itemMenuId);
    }


    /**
     * Метод вызывается при событии site.menuItemEditEnd.
     * Данное событие вызывается после сохранении данных из
     * формы редактировании пункта меню в менеджере страниц.
     *
     * @param $ep
     *
     * @return null
     */
    public function eventMenuItemEditFormSaveEnd(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $url        = $ep->getParam('url');
        $objectId   = (int) $ep->getParam('objectId');
        $itemMenuId = (int) $ep->getParam('itemMenuId');

        if($moduleName != $this->moduleName) return null;

        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $objectId, time());

        $this->model->urlUpdate($objectId, $url);

        $name = Request::getPost('menu_item_name');
        $textTop    = Request::getPost('textTop', true);
        $textBottom = Request::getPost('textBottom', true);

        $this->model->updateCatalog($objectId, $name, $textTop, $textBottom, $itemMenuId);

        return null;
    }

    /**
     * Метод вызывается при событии site.menuItemEditFullForm.
     * Данное событие вызывается при заполнении
     * формы редактирования пункта меню в менеджере страниц.
     *
     * @param $ep
     *
     * @return  null|array - данные для заполнения формы
     */
    public function eventMenuItemEditFullForm(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        $seoForm = Module::get('PageSemantic')->fullSeoForm($moduleName, $objectId);

        $catalog = $this->model->info($objectId);

        $textForm = array(
            'textTop'    => $catalog->text_top,
            'textBottom' => $catalog->text_bottom
        );

        return $seoForm + $textForm;
    }


    public function eventPageBottom(Core\EventParam $ep)
    {
        if($ep->getParam('module') !== $this->moduleName) return '';
        if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) return '';
        $id = (int) $ep->getParam('object_id');

        return $this->view->get(
            'ToolsBar.php',
            array(
                'edit'          => "/Articles/Edit/Id/{$id}/GoTo/Art",
                'editTitle'     => Core\Lang::get('Articles.articleEdit'),
                'delete'        => "/Articles/Delete/Id/{$id}",
                'deleteTitle'   => Core\Lang::get('Articles.articleDelete'),
                'deleteConfirm' => Core\Lang::get('Articles.articleDeleteConfirm'),
                'add' => '/Articles/Add/',
                'addTitle' =>  Core\Lang::get('Articles.articleAdd'),
            )
        );

    }

    public function eventMenuItemDeleteBefore(Core\EventParam $ep)
    {
        if($ep->getParam('moduleName') !== $this->moduleName) return '';
        $id = (int) $ep->getParam('objectId');

        $this->model->deleteCatalog($id);
    }

    public function eventGenerateSiteMapXml(Core\EventParam $ep)
    {
        $articles = $this->model->pageListAll();
        $out = '';
        foreach ($articles as $article) {
            $out[] = SITE_URL . '/' .$article['url'] . URL_SEMANTIC_END;
        }

        return $out;
    }


    /*тест события*/
    public function eventArticlesTextFormTopAfter(Core\EventParam $ep){
        return array(
            array(
                'type'=>'html',
                'html'=> 'hello world',
            ),
        );
    }




}