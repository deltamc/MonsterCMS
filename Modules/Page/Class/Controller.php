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
    public function actionEmail_send()
    {
        /*TODO убрать в виджет */
        include_once($this->modulePath . 'Actions' . DS . 'EmailSend.php');
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

        $page = $this->model->add($name, $url);



        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $page['id']);

        return array('object_id' => $page['id'], 'url_id' => $page['url_id'] );
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

        if($moduleName != $this->moduleName) return null;

        Module::get('PageSemantic')->saveSeoForm($this->moduleName, $objectId);

        $this->model->urlUpdate($objectId, $url);

        $name = Request::getPost('menu_item_name');

        $this->model->update($name, $objectId);

        return null;
    }

    /**
     * Метод вызывается при событии site.menuItemEditEnd.
     * Данное событие вызывается после сохранении данных из
     * формы редактировании пункта меню в менеджере страниц.
     *
     * @param $moduleName - имя модуля
     * @param $itemType - тип страницы
     * @param $objectId - ид страницы
     *
     * @return null
     */
    public function menuItemDelete(Core\EventParam $ep)
    {
        $moduleName = $ep->getParam('moduleName');
        $itemType   = $ep->getParam('itemType');
        $objectId   = (int) $ep->getParam('objectId');

        if($moduleName != $this->moduleName) return null;

        $id = intval($objectId);
        $this->model->delete($id);

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
            "menuItemIcon"        => "",

        );
    }

    function menuItemAddModuleList2(Core\EventParam $ep)
    {
        return array(
            'module'                => $this->moduleName,
            'itemType'              => 'page_text2',
            "menuItemName"        => Core\Lang::get('Page.menuItemName'),
            /* which fields form hide (form add item menu)*/
            "hiddenFormItems"     => array("menu_item_url"),
            "menuItemIcon"        => "",
        );
    }

}