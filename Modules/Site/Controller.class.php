<?php namespace Monstercms\Modules\Site;

use \Monstercms\Core;
use \Monstercms\Lib;


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
            'window_size' => '800x600'
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

        $varsCell = Core\Events::Cell('Site.menuTplVars', 'array',
            array($menuId, $vars));

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
     * @return string
     * @throws \Exception
     */
    private function getMenuTree($menuId, $parent = 0, $tpl,  $depth = 0)
    {
        $out = '';
        $depth++;
        $items = $this->model('MenuItems')->getMenuItems($menuId, $parent);

        foreach ($items as $id => $item) {

            if ($item['hide'] == 1) {
                continue;
            }

            $subMenu = '';
            if ($item['child_count'] > 0){
                $subMenu = $this->getMenuTree($menuId, $id, $tpl,  $depth);
            }



            $item['sub_menu'] = $subMenu;
            $item['depth']    = $depth;
            $item['id']       = $id;

            $varsCell = Core\Events::Cell('Site.menuItemTplVars', 'array_merge',
                array($menuId, $item));

            if (!empty($varsCell)) {
                $vars = array_merge($item, $varsCell);
            } else {
                $vars = $item;
            }

            $out .= $this->view->get($tpl, $vars);
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






}