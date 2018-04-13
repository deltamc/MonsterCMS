<?php namespace Monstercms\Modules\Site;

use \Monstercms\Core;
use \Monstercms\Lib;
use \Monstercms\Core\User;


class Controller extends Core\ControllerAbstract
{
    public $structureUrl = "";

    function __construct($moduleName){

        parent::__construct($moduleName);

        $this->structureUrl = SITE_URL . '/' . $this->moduleName .
            '/Structure';
    }

    /**
     * Метод вызывается при событии MenuAdmin.addItems.
     * Данное событие происходит во время генерации меню администрации
     * @return array
     */
    public function eventAddItemAdminMenu()
    {

        return array
        (
            //Шаблон пункта
            'type'        => 'button',
            // Ссылка
            'action'      => $this->structureUrl,
            // Иконка (FontAwesome)
            'ico'         => 'fa-sitemap',
            // Текст ссылки
            'text'        => Core\Lang::get('Site.structure'),
            // Выравнивание
            'align'       => 'left',
            //Открывать в диалоговом окне
            'target'      => 'dialog',
            //Размер окна
            'window_size' => '800x600',
            'access'      => array(
                Core\User::ADMIN,
                Core\User::CONTENT_MANAGER,
                Core\User::DEMO,
            )
        );
    }


    public function eventPageBottom(Core\EventParam $ep)
    {
        if($ep->getParam('module') !== $this->moduleName) return '';
        if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) return '';
        $id = (int) $ep->getParam('object_id');

        return $this->view->get(
            'ToolsBar.php',
            array(
                'edit'          => "/Site/MenuItemEdit/id/{$id}/GoTo/Page",
                'editTitle'     => Core\Lang::get('Site.config'),
                'delete'        => "/Site/MenuItemDelete/id/{$id}",
                'deleteTitle'   => Core\Lang::get('Site.deletePage'),
                'deleteConfirm' => Core\Lang::get('Site.deletePageConfirm'),
            )
        );

    }

    /**
     * Метод перенаправляет на станицу "Структура сайта"
     */
    public function goToStructureUrl()
    {
        Lib\Header::location($this->structureUrl);
    }

    /**
     * Метод возвращает html код меню
     *
     * @param $menuId - ид меню
     * @param null $cssClass - css класс
     * @param string $tpl - шаблон меню
     * @param string $tplTtem - шаблон пункта меню
     * @return string - html код меню
     * @throws \Exception
     */
    public function menu($menuId, $cssClass=null, $tpl = 'Menu.php', $tplTtem = 'MenuItems.php')
    {

        $items = $this->getMenuTree($menuId, 0, $tplTtem);



        $vars = array(
            'css'   => $cssClass,
            'items' => $items
        );

        $varsCell = Core\Events::Cell(
            'Site.menuTplVars',
            'array',
            array(
                'menuId' => $menuId,
                'menu'   => $vars
            )
        );

        if (!empty($varsCell)) {
            $vars = array_merge($varsCell, $vars);
        }


        return $this->view->get($tpl, $vars);
    }

    /**
     * Метод возвращает деревовидную структура меню
     * @param $menuId - ид меню
     * @param int $parent - ид родителя
     * @param $tpl - шаблон пукта меню
     * @param int $depth - глубина
     * @param bool $hide - обрабатывать скрытые пункты меню
     * @return string
     * @throws \Exception
     */
    public function getMenuTree($menuId, $parent = 0, $tpl,  $depth = 0, $hide = false)
    {
        $out = '';
        $depth++;
        $items = $this->model('MenuItems')->getMenuItems($menuId, $parent);

        foreach ($items as $id => $item) {

            if (!$hide && $item['hide'] == 1) {
                continue;
            }

            $subMenu = '';
            if ($item['child_count'] > 0) {
                $subMenu = $this->getMenuTree($menuId, $id, $tpl,  $depth);
            }

            $item['sub_menu'] = $subMenu;
            $item['depth']    = $depth;
            $item['id']       = $id;

            $varsCell = Core\Events::Cell(
                'Site.menuItemTplVars',
                'array_merge',
                array
                (
                    'menuId' => $menuId,
                    'item'   => $item
                )
            );

            if (!empty($varsCell)) {
                $vars = array_merge($item, $varsCell);
            } else {
                $vars = $item;
            }

            $out .= $this->view->get($tpl, $vars);
        }
        return $out;
    }


    public function treeCatalogSelect($value, $disabledModule = null, $disabledItemType=null)
    {
        $out['items'] = array();
        $out['disabled'] = array();
        $menus = $this->model('Menu')->menuList();


        foreach ($menus  as $menu) {
            $out['items']['m'.$menu['id']] = $menu['name'];
            $out['disabled']['m'.$menu['id']] = 'disabled';
            $itemsMenu = $this->getTreeOptions($menu['id'], $disabledModule, $disabledItemType, $value, 0, 0);
            $out['items'] = $out['items'] + $itemsMenu['items'];
            $out['disabled'] = $out['disabled'] + $itemsMenu['disabled'];

        }

        return $out;
    }


    public function getTreeOptions(
        $menuId,
        $disabledModule = null,
        $disabledItemType = null,
        $value=null,
        $parent = 0,
        $depth = 0)
    {
        $out['items'] = array();
        $out['disabled'] = array();
        $depth++;
        $items = $this->model('MenuItems')->getMenuItems($menuId, $parent);

        foreach ($items as $id => $item) {

            $prf = str_repeat("|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $depth);
            $out['items'][$id] = $prf. ' ' .$item['name'];
            if ($item['module'] !== $disabledModule && $item['item_type'] !== $disabledItemType) {
                $out['disabled'][$id] = 'disabled';
            }

            if ($item['child_count'] > 0) {
                $child = $this->getTreeOptions($menuId, $disabledModule, $disabledItemType, $value, $id, $depth);
                $out['items'] =  $out['items'] + $child['items'];
                $out['disabled'] =  $out['disabled'] + $child['disabled'];
            }
        }
        return $out;
    }


    /**
     * Метод возвращает ID главной страницы (страница которая отрывается по умолчанию)
     * @return mixed
     * @throws \Exception
     */
    public function getIndexObjectId()
    {
        $index = $this->model('MenuItems')->getIndexObject();

        if (!isset($index->object_id)) {
            throw new \Exception('Index page not found!');
        }

        return $index->object_id;
    }


    public function menuItemAddModuleList(Core\EventParam $ep)
    {
        return array(
            'module'                => $this->moduleName,
            'itemType'              => 'link',
            "menuItemName"        => Core\Lang::get('Site.link'),
            /* which fields form hide (form add item menu)*/
            "hiddenFormItems"     => array(
                "menu_item_url_semantic",
                "menu_item_index",
                "menu_item_goto"
            ),
            "menuItemIcon"        => "fa fa-link",

        );
    }

    /**
     * Метод возвращает идентификатор объекта, который указан в свойствах url
     * Поиск по ди пункта меню.
     * @param $itemId
     */
    public function getObjectIdByItemId($itemId) {
        return $this->model('MenuItems')->getObjectIdByItemId($itemId);
    }
    /**
     * Метод возвращает ид пункта меню, поиск оп ид объекта и
     * @param $module
     * @param $objectId
     * @return mixed
     */
    public function getItemIdByObjectId($module, $objectId) {
        return $this->model('MenuItems')->getItemIdByObjectId($module, $objectId);
    }
}