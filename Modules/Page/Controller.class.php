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
     * @param $moduleName - имя модуля
     * @param $itemType - тип страницы
     *
     * @return null|array - элементы формы
     */
    public function eventMenuItemAddForm($moduleName, $itemType)
    {
        if($moduleName != $this->moduleName) return null;

        $form_items =  Module::get('PageHead')->getSeoForm();


        return $form_items;

    }


    /**
     * Метод вызывается при событии site.editFormTabBaseAfter.
     * Данное событие вызывается при генерации формы редактирование
     * пункта меню в менеджере страниц.
     *
     * @param $moduleName - имя модуля
     * @param $itemType - тип страницы
     *
     * @return null|array - элементы формы
     */
    public function eventMenuItemEditForm($moduleName, $itemType, $id)
    {

        if($moduleName != $this->moduleName) return null;

        $form_items =  Module::get('PageHead')->getSeoForm();

        return $form_items;
    }

    /**
     * Метод вызывается при событии site.menuItemEditFullForm.
     * Данное событие вызывается при заполнении
     * формы редактирования пункта меню в менеджере страниц.
     *
     * @param $moduleName - имя модуля
     * @param $itemType - тип страницы
     * @param $id - ид страницы
     * @return  null|array - данные для заполнения формы
     */
    public function eventMenuItemEditFullForm($moduleName, $itemType, $id)
    {
        if($moduleName != $this->moduleName) return null;

        return Module::get('PageHead')->fullSeoForm($moduleName, $id);
    }


    /**
     * Метод вызывается при событии site.menuItemAddSave.
     * Данное событие вызывается при сохранении данных из
     * формы добавлении пункта меню в менеджере страниц.
     *
     * @param $moduleName
     * @param $itemType
     * @param string $url
     * @return null|array('object_id' => page_id, 'url_id' => page_url_id );
     */
    public function eventMenuItemAddFormSave($moduleName, $itemType, $url='')
    {
        if($moduleName != $this->moduleName) return null;


        $name = Request::getPost('menu_item_name');

        $page = $this->model->add($name, $url);

        Module::get('PageHead')->saveSeoForm($this->moduleName, $page['id']);

        return array('object_id' => $page['id'], 'url_id' => $page['url_id'] );
    }

    /**
     * Метод вызывается при событии site.menuItemEditEnd.
     * Данное событие вызывается после сохранении данных из
     * формы редактировании пункта меню в менеджере страниц.
     *
     * @param $moduleName - имя модуля
     * @param $itemType - тип страницы
     * @param $url - чпу
     * @param $objectId - ид страницы
     *
     * @return null
     */
    public function eventMenuItemEditFormSaveEnd($moduleName, $itemType, $url, $objectId)
    {
        if($moduleName != $this->moduleName) return null;

        Module::get('PageHead')->saveSeoForm($this->moduleName, $objectId);

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
    public function menuItemDelete($moduleName, $itemType, $objectId)
    {
        if($moduleName != $this->moduleName) return null;

        $id = intval($objectId);
        $this->model->delete($id);

        return null;
    }

}