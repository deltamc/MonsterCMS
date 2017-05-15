<?php namespace Monstercms\Modules\Site;
/**
 * @var $this Core\ControllerAbstract
 */

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\MCMS;
use \Monstercms\Lib;
use \Monstercms\Lib\Request;
use \Monstercms\Core\Module;
use \Monstercms\Core\Url;

//Если не передан Ид объекта (пункта меню)
if ($this->getObjectId() === 0){
    throw new Core\HttpErrorException(404);
}

//Если у пользователя не хватает прав
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

//Назначаем базовый шаблон
Lib\View::setBasicTemplate(THEMES_DIALOG_PATH);

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Site.headingEdit'));

//Ид объекта (пункта меню)
$menuItemId = $this->getObjectId();

//Информация о пукте меню
$menuItemInfo    = $this->model('MenuItems')->menuItemInfo($menuItemId);
//Имя модуля
$moduleName       = $menuItemInfo->module;
//ID родительского пункта меню
$parentId         = $menuItemInfo->parent_id;
//ID сематического URL
$urlId            = $menuItemInfo->url_id;
//Ид объекта
$objectId         = $menuItemInfo->object_id;
//тип пункта меню
$itemType         = $menuItemInfo->item_type;


//Получаем конфигурации модуля
$config = Module::get($moduleName)->config;

//Проверяем тип пункта меню (должен быть прописан в насторйках модуля "menu_items")
if(empty($config['menu_items'][$itemType]))
    throw new \Exception("Item type error");


$moduleAddConfig = $config['menu_items'][$itemType];

//Прификс для событий формы
$action = 'edit';

//Получаем массив с именами элементов формы которые нужно скрыть
$hide = array();
if (is_array($moduleAddConfig['hidden_form_items'])) {
    $hide = $moduleAddConfig['hidden_form_items'];
}
//Получаем данные формы
$formItems = include($this->modulePath . 'Forms' . DS . 'MenuItem.php');

//Скрываем данные формы
$formItems = Mcms::hiddenItemsForm($formItems, array('menu_item_index'));
$formItems = Mcms::hiddenItemsForm($formItems, $hide);


$urlObj = new Url();
$form   = new Lib\Form('');

//Заполняем форму
$form_items1_full = array
(
    'menu_item_name'         => $menuItemInfo->name,
    'menu_item_url_semantic' => $urlObj->getUrl($urlId),
    'menu_item_url'          => $menuItemInfo->url,
    'menu_item_menu'         => $menuItemInfo->menu_id,
    'menu_item_css'          => $menuItemInfo->css_class,
    'menu_item_target'       => $menuItemInfo->target,
    'menu_item_hide'         => $menuItemInfo->hide

);
//Получаем данные формы с других модулей
$formItems = Core\Events::eventsForm(
    $formItems,
    array(
        'moduleName' => $moduleName,
        'itemType'   => $itemType,
        'objectId'   => $objectId
    )
);

//Добавляем элементы формы
$form->add_items($formItems);

//Заполняем элементы форм получив массив из других модулей
$full = Core\Events::cell(
    'Site.menuItemEditFullForm',
    'array_merge',
    array(
        'moduleName' => $moduleName,
        'itemType'   => $itemType,
        'objectId'   => $objectId
    )
);


if (!empty($full)) {

    foreach ($full as $name => $value) {
        $form_items1_full[$name] = $value;
    }

    $form->full($form_items1_full);
}


//Если форма не была заполнена, выводим ее
if (!$form->is_submit()) {
    $this->view->add('BODY', $form->render());
} else if ($form->is_valid()) {
    //Удаляем запрещенные символы из семантического URL
    $url = preg_replace('/[^a-z0-9-_]/', '', Request::getPost('menu_item_url_semantic'));

    $url = strtolower($url);

    Core\Events::cell
    (
        'Site.menuItemEditSave',
        'void',
        array(
            'moduleName' => $moduleName,
            'itemType'   => $itemType,
            'url'        => $url,
            'objectId'   => $objectId
        )
    );

    //Подготавливаем данные к записи в БД
    $data = array
    (
        'name'            => Request::getPost('menu_item_name'),
        'module'          => $moduleName,
        'css_class'       => Request::getPost('menu_item_css'),
        'target'          => Request::getPost('menu_item_target'),
        'hide'            => intval(Request::getPost('menu_item_hide'))

    );

    $menuIdNew = (int) Request::getPost('menu_item_menu');

    //Если пункт меню был перенесен в другую меню
    if ((int) $menuItemInfo->menu_id !== $menuIdNew) {
        $data['parent_id'] = 0;
        $data['pos'] = $this->model('MenuItems')->maxPos(Request::getPost('menu_item_menu'));
        $this->model('MenuItems')->itemMoveInMenu($menuItemId, $menuItemInfo->menu_id, $menuIdNew);
    }

    //Сохраняем данные в БД
    $this->model('MenuItems')->menuItemEdit($data, $menuItemId);

    if ((int) $menuItemInfo->menu_id !== $menuIdNew) {
        //move in other menu
        //$this->model->itemMoveInMenu($id, $menuItemInfo->menu_id, $menuIdNew);

    }
    //Вызываем событие
    Core\Events::cell('Site.menuItemEditEnd', 'void',
        array(
            'moduleName' => $moduleName,
            'itemType'   => $itemType,
            'url'        => $url,
            'objectId'   => $objectId
        )
    );

    //Редирект
    if ((int) Request::getPost('menu_item_goto') === 1 && !empty($url)) {
        $url = '/';

        if ((int) Request::getPost('menu_item_index') !== 1) {
            $url = '/'.$url.URL_SEMANTIC_END;
        }

        Lib\Header::location($url, 'top');
    } else {
        Lib\Header::location($this->structureUrl);
    }

} else {
    //выводим форму с ошибками
    $this->view->add('BODY', $form->error());
}
