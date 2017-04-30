<?php namespace Monstercms\Modules\site;

use \Monstercms\Core;
use \Monstercms\Lib;


class Controller extends Core\ControllerAbstract
{
    public $structureUrl = "";

    function __construct($moduleName){

        parent::__construct($moduleName);

        $this->structureUrl = '/' . $this->moduleName .
            '/structure';
    }


    public function eventAddItemAdminMenu()
    {

        return array
        (
            'type'=>'button',
            'action'=>$this->structureUrl,
            'ico' => 'fa-sitemap',
            'text' =>  Core\Lang::get('Site.structure'),
            'align' => 'left',
            'target' => 'dialog',
            'window_size' => '800x600'
        );
    }


    public function goToStructureUrl()
    {
        Lib\header::location($this->structureUrl);
    }


    public function menu($menu_id, $css_class=null, $tpl = 'Menu.php', $tpl_item = 'MenuItems.php')
    {

        $items = $this->getMenuTree($menu_id, 0, $tpl_item);

        $vars = array(
            'css' => $css_class,
            'items' => $items
        );

        return $this->view->get($tpl, $vars);
    }

    private function getMenuTree($menu_id, $parent = 0, $tpl,  $depth=0)
    {
        $out = '';
        $depth++;
        $items = $this->model('MenuItems')->getMenuItems($menu_id, $parent);

        foreach($items as $id=>$item)
        {

            if($item['hide'] == 1) continue;

            $sub_menu = '';
            if($item['child_count'] > 0) $sub_menu = $this->getMenuTree($menu_id, $id, $tpl,  $depth);

            $item['sub_menu'] = $sub_menu;
            $item['depth'] = $depth;
            $out .= $this->view->get($tpl, $item);


        }
        return $out;
    }

    public function getIndexObjectId()
    {
        $index = $this->model('MenuItems')->getIndexObject();

        if(!isset($index->object_id)) throw new \Exception('Index page not found!');
        return $index->object_id;
    }






}