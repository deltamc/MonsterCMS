<?php namespace Monstercms\Modules\Page;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\Lang;
use \Monstercms\Lib;
use \Monstercms\Lib\Request;
use Monstercms\Core\MCMS;
use Monstercms\Core\Module;

class Controller extends Core\ControllerAbstract
{

    /**
     * Метод вызывается при событии site.addFormTabBaseAfter.
     * Данное событие вызывается при генерации формы добавление
     * нового пункта меню в менеджере страниц.
     *
     * @param $ep
     *
     * @return null|array - элементы формы
     */
    public function eventMenuItemAddForm(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');

        if($moduleName != $this->moduleName) return null;

        $formSeo   =  Module::get('PageSemantic')->getSeoForm();
        $formTheme =  Module::get('PageSemantic')->getThemeForm();

        return array_merge($formSeo, $formTheme);
    }


    /**
     * Метод вызывается при событии site.editFormTabBaseAfter.
     * Данное событие вызывается при генерации формы редактирование
     * пункта меню в менеджере страниц.
     *
     * @param $ep
     *
     * @return null|array - элементы формы
     */
    public function eventMenuItemEditForm(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        $formSeo   =  Module::get('PageSemantic')->getSeoForm();
        $formTheme =  Module::get('PageSemantic')->getThemeForm();

        return array_merge($formSeo, $formTheme);
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

        return Module::get('PageSemantic')->fullSeoForm($moduleName, $objectId);
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


        $name = Request::getPost('menu_item_name');

        $page = $this->model->add($name, $url, 'Site');



        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $page['id'], time());

        return array('object_id' => $page['id'], 'url_id' => $page['url_id'] );
    }


    public function eventMenuItemAddFormSaveEnd(Core\EventParam $ep) {
        $moduleName = $ep->getParam('moduleName');
        $menuItemId = $ep->getParam('itemMenuId');
        $itemType   = $ep->getParam('itemType');
        $url        = $ep->getParam('url');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        $name = Request::getPost('menu_item_name');

        $this->model->update($name, $objectId, 'Site', $menuItemId);
    }


    /**
     * Метод вызывается при событии menuItemDeleteBefore.
     * Данное событие вызывается перед удалением данных из бд
     * @param $ep
     * @return null
     */
    public function menuItemDelete(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        $this->model->delete($objectId);

        return null;
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
        $menuItemId = $ep->getParam('itemMenuId');
        $itemType   = $ep->getParam('itemType');
        $url        = $ep->getParam('url');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $objectId, time());

        $this->model->urlUpdate($objectId, $url);

        $name = Request::getPost('menu_item_name');

        $this->model->update($name, $objectId, 'Site', $menuItemId);

        return null;
    }

    function menuItemAddModuleList(Core\EventParam $ep)
    {
        return array(
            'module'                => $this->moduleName,
            'itemType'              => 'page_text',
            "menuItemName"        => Core\Lang::get('Page.menuItemName'),
            /* which fields form hide (form add item menu)*/
            "hiddenFormItems"     => array("menu_item_url"),
            "menuItemIcon"        => "fa fa-file-o",

        );
    }
/*
    function menuItemAddModuleList2(Core\EventParam $ep)
    {
        return array(
            'module'                => $this->moduleName,
            'itemType'              => 'page_text2',
            "menuItemName"        => Core\Lang::get('Page.menuItemName'),

            "hiddenFormItems"     => array("menu_item_url"),
            "menuItemIcon"        => "",
        );
    }
*/
    public function lastModifiedUpdate(Core\EventParam $ep)
    {
        $pageHead = Core\PageSemantic::init();
        $pageHead->setLastModified(time());
        $pageHead->save($this->moduleName, $ep->getParam('page_id'));
    }

    /**
     * метод добавляет страницу
     * @param $name   - название страницы
     * @param $url    - семантический адрес страници
     * @param $module - принадлежность страницы к модулю
     * @param $objectId - ид связанного объекта, например каталога статьи
     * @return array
     */
    public function add($name, $url, $module = null, $objectId = null)
    {
        $page = $this->model->add($name, $url, $module, $objectId);
        return array(
            'id'        => $page['id'],
            'url_id'    => $page['url_id'],
            'module'    => $module,
            'object_id' => $objectId
        );
    }


    /**
     * метод обновляет страницу
     * @param $name - название страницы
     * @param $url - семантический адрес страницы
     * @param $module - принадлежность страницы к модулю
     * @param $objectId - ид связанного объекта, например каталога статьи
     * @return array
     */
    public function edit($id, $url, $name, $module = null, $objectId = null)
    {
        $this->model->urlUpdate($id, $url);
        $this->model->update($name, $id, $module, $objectId);
    }

    /**
     * Метод возвращает название таблицы, в которой хранятся информация об страницах
     * @return string
     */
    public function getTableDb(){
        return $this->config['db_table'];
    }


    /**
     * Метод удаляет страницу
     * @param $id - ид страницы
     */
    public function delete($id) {

        $this->model->delete($id);
    }



}