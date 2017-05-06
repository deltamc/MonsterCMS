<?php namespace Monstercms\Modules\site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */
use \Monstercms\Core;
use \Monstercms\Core\MCMS;
use \Monstercms\Lib;

//Проверяем есть ли необходимые данные
if ((int) $this->getObjectId() === 0){
    throw new Core\HttpErrorException(404);
}

if ((int) $this->getParam('menu_id') === 0){
    throw new Core\HttpErrorException(404);
}

//Права доступа
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$menuItemId  = (int) $this->getObjectId();
$menuId      = (int) $this->getParam('menu_id');

$out = array(
    'id'      => $menuItemId,
    'delete'  => false,
    'message' => ''
);

//Если у пункта меню есть подпункты, выводим ошибку
if ($this->model('MenuItems')->isChilds($menuId, $menuItemId )) {
    $out['message'] = Core\Lang::get('Site.errorDeleteMenu');

//Если пункт меню является индексом, выводим ошибку
} else if ($this->model('MenuItems')->getIndexId() === $menuItemId) {
    $out['message'] = Core\Lang::get('Site.indexDeleteError');

//если удаление допустимо, удаляем пункт меню
} else {
    //Получаем информацию об пункте меню
    $menuItemInfo = $this->model('MenuItems')->menuItemInfo($menuItemId);

    //Если информация об пункте меню пуста, выводим сообщение об ошибке
    if(!isset($menuItemInfo->module)){
        print json_encode($out);
        exit();
    }

    $module      = $menuItemInfo->module;
    $objectId    = $menuItemInfo->object_id;
    $itemType    = $menuItemInfo->item_type;

    //Вызываем событие
    Core\Events::cell('Site.menuItemDeleteBefore', 'void', array($module, $itemType, $objectId));

    //Удаляем пункт меню
    $this->model('MenuItems')->menuItemDelete($menuItemId);

    $out['delete'] = true;

    //вызываем событие
    Core\Events::cell('Site.menuItemDeleteEnd', 'void',
        array($module, $itemType, $objectId));
}


/*
 Выводим данные, в формате json

'id'      - ид пункта меню
'delete'  - обвенчалась ли попытка удаления (true, false)
'message' - сообщение об ошибке

 */
print json_encode($out);

exit();