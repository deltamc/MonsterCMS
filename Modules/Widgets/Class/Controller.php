<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;

class Controller extends Core\ControllerAbstract
{
    public function getConfig()
    {
        return $this->config;
    }

    public function toolBar()
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

        return $this->view->get("Tools.php", array('widgets'=>$vars));

    }
}