<?php namespace Monstercms\Modules\site;
/**
 * @var $this Core\ControllerAbstract
 */
use \Monstercms\Core;
use \Monstercms\Core\MCMS;
use \Monstercms\Lib;

if ((int) $this->getObjectId() === 0){
    throw new Core\HttpErrorException(404);
}
if ((int) $this->getParam('menu_id') === 0){
    throw new Core\HttpErrorException(404);
}

if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

$menuItemId = (int) $this->getObjectId();
$menuId      = (int) $this->getParam('menu_id');

$out = array(
    'id'      => $menuItemId,
    'delete'  => false,
    'message' => ''
);

if ($this->model('MenuItems')->isChilds($menuId, $menuItemId ))
{
    $out['message'] = Core\Lang::get('site.errorDeleteMenu');
}
else if ($this->model('MenuItems')->getIndexId() === $menuItemId)
{
    $out['message'] = Core\Lang::get('site.indexDeleteError');
}
else
{
    $menuItemInfo = $this->model('MenuItems')->menuItemInfo($menuItemId);
    if(!isset($menuItemInfo->module)){
        print json_encode($out);
        exit();
    }
    $module       = $menuItemInfo->module;
    $objectId    = $menuItemInfo->object_id;
    $itemType    = $menuItemInfo->item_type;

    Core\Events::cell('site.menuItemDeleteBefore', 'void', array($module, $itemType, $objectId));

    $this->model('MenuItems')->menuItemDelete($menuItemId);

    $out['delete'] = true;

    Core\Events::cell('site.menuItemDeleteEnd', 'void',
        array($module, $itemType, $objectId));
}

print json_encode($out);
exit();