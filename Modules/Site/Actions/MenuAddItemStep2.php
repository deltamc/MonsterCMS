<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\MCMS;
use \Monstercms\Core\Module;
use \Monstercms\Lib;
use \Monstercms\Lib\Request;

if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

//Назначаем базовый шаблон
Lib\View::setBasicTemplate(THEMES_DIALOG_PATH);

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Site.headingAdd'));

//Имя модуля добавляемого пункта меню
$moduleName = $this->getParam('item_module');
//Тип пункта меню
$itemType   = $this->getParam('item_type');

//Проверяем подключен ли модуль
if (empty($moduleName) || !Module::isModule($moduleName)) {
    throw new \Exception("The module can not be found");
}

//Проверяем тип пункта меню (должен быть прописан в насторйках модуля "menu_items")
if (empty(Module::get($moduleName)->config['menu_items'][$itemType])) {
    throw new \Exception("Item type error");
}

//Получаем настройки
$moduleAddConfig  = Module::get($moduleName)->config['menu_items'][$itemType];

//Получаем массив с именами элементов формы которые нужно скрыть
$hide = array();
if (isset($moduleAddConfig['hidden_form_items'])
    && is_array($moduleAddConfig['hidden_form_items'])) {
    $hide = $moduleAddConfig['hidden_form_items'];
}

//Получаем данные формы
$formItems = include($this->modulePath . 'Forms' . DS . 'MenuItem.php');

// Скрываем поля формы ( какие поля скрыть хранятся в настройках модуля hidden_form_items )
$formItems = Mcms::hiddenItemsForm($formItems, $hide);

//Получаем данные формы с других модулей
$formItems = Core\Events::eventsForm($formItems, array($moduleName, $itemType));

$form = new Lib\Form('');
$form->add_items($formItems);

//Если форма не была заполнена, выводим ее
if(!$form->is_submit())
{
    //$this->tag->BODY .= $form->render();
    $this->view->add('BODY', $form->render());
}
else if($form->is_valid())
{
    $url = preg_replace('/[^a-z0-9-_]/', '', Request::getPost('menu_item_url_semantic'));

    $url = strtolower($url);

    /*
     * Вызываем событие Site.menuItemAddSave
     * Передаем параметры: $moduleName, $itemType, $url
     * Модуль должен вернуть ассоциативный массив: url_id, object_id
     */

    $module_param = Core\Events::cell(
        'Site.menuItemAddSave',
        'array_merge',
        array
        (
            'moduleName' => $moduleName,
            'itemType'   => $itemType,
            'url'        => $url
        )
    );

    $urlId    = 0;
    $objectId = 0;

    if (isset($module_param['url_id']))    $urlId    = $module_param['url_id'];
    if (isset($module_param['object_id'])) $objectId = $module_param['object_id'];


    /* Записываем в бд */

    $data = array(

        'name'            => Request::getPost('menu_item_name'),
        'module'          => $moduleName,
        'item_type'       => $itemType,
        'url_id'          => $urlId,
        'url'             => Request::getPost('menu_item_url'),
        'parent_id'       => 0,
        'css_class'       => Request::getPost('menu_item_css'),
        'menu_id'         => intval($_POST['menu_item_menu']),
        'target'          => Request::getPost('menu_item_target'),
        'pos'             => $this->model('MenuItems')->
                                maxPos(Request::getPost('menu_item_menu')),

        'hide'            => intval(Request::getPost('menu_item_hide')),
        'object_id'       => $objectId,
        'child_count'     => 0

    );

    $id = $this->model('MenuItems')->menuItemAdd($data);



    /*Если страница индексная*/
    if ((int) Request::getPost('menu_item_index')  === 1
        || $this->model('MenuItems')->getIndexId() === 0) {
        $this->model('MenuItems')->setIndex ($id);
    }

    /*
     Данное событие вызывается после сохранения данных из формы добавлении пункта меню в менеджере страниц.
     */
    Core\Events::cell(
        'Site.menuItemAddEnd',
        'void',
        array(
            'moduleName' => $moduleName,
            'itemType'   => $itemType,
            'url'        => $url,
            'objectId'   => $objectId
        )
    );

    //редирект
    if ((int) Request::getPost('menu_item_goto') === 1 && !empty($url)) {
        $goto = '/';
        if((int) Request::getPost('menu_item_index') !== 1) {
            $goto = '/'.$url.URL_SEMANTIC_END;
        }

        Lib\Header::location($goto, 'top');
    } else {
        $this->goToStructureUrl();
    }
} else {
    $this->view->add('BODY', $form->error());
}
