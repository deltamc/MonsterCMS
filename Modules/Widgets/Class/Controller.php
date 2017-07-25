<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Lib;

class Controller extends Core\ControllerAbstract
{
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Метод выводит панель инструментов
     * @param $pageId - ид страницы
     * @return string
     * @throws \Exception
     */
    public function toolBar($pageId)
    {
        $this->model->init();


        $vars = '';


        foreach ($this->model->getAll() as $key => $widget) {
            /**
             * @var $widget \Monstercms\Core\WidgetInterface
             */
            $vars[] = array
            (
                'ico'         => $widget->getIco(),
                'name'        => $widget->getName(),
                'widget'      => $key,
                'window_size' => $widget->getWindowSize()
            );
        }

        Lib\JavaScript::add('/' . MODULE_DIR.'/'.$this->moduleName.'/JavaScript/widgets.js');
        Lib\JavaScript::add('/JavaScript/scroll.js');

        return $this->view->get("Tools.php", array('widgets'=>$vars, 'pageId' => $pageId));

    }

    public function view($pageId)
    {
        $pageId = (int) $pageId;
        $widgets = $this->model->widgetsList($pageId);

        $vars = array(
           'widgets' =>  $widgets,
            'pageId' => $pageId,
        );

        return $this->view->get('widgetsList.php', $vars);

    }


}