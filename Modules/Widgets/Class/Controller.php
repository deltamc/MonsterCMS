<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Lib;

class Controller extends Core\ControllerAbstract
{
    /**
     * Получить настойки
     * @return array|mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Записать значение в настойках
     * @param $key
     * @param $value
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
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
                'window_size' => $widget->getEditFormWindowSize(),
                'order'       => (int) $widget->getOrder(),
            );
        }

        usort($vars, function($a, $b){
            return ($a['order']-$b['order']);
        });

        Lib\JavaScript::add('/' . MODULE_DIR.'/'.$this->moduleName.'/JavaScript/widgets.js');
        Lib\JavaScript::add('/JavaScript/scroll.js');

        return $this->view->get("Tools.php", array('widgets'=>$vars, 'pageId' => $pageId));

    }

    /**
     * Метод отображает на странице виджеты
     * @param $pageId
     * @return string
     * @throws \Exception
     */
    public function view($pageId)
    {
        $pageId = (int) $pageId;
        $widgets = $this->model->widgetsList($pageId);

        foreach ($widgets as &$widget) {

            $varsWrap = array(
                'html'       => $widget['cache'],
                'id'         => $widget['id'],
                'widgetName' => $widget['widget'],
                'pos'        => $widget['pos'],
                'class'      => $widget['css_class'],
                'windowSize' => $widget['window_size']
            );

            //@TODO убрать из цикла
            $widget['cache'] = $this->view->get('Wrap.php', $varsWrap);

            if(!empty($widget['javascript'])) {
                if (is_array($widget['javascript'])) {
                    foreach ($widget['javascript'] as $js) {
                        Lib\JavaScript::add($js);
                    }
                } else {
                    Lib\JavaScript::add($widget['javascript']);
                }
            }

            if(!empty($widget['css'])) {
                if (is_array($widget['css'])) {
                    foreach ($widget['css'] as $css) {
                        Lib\Css::add($css);
                    }
                } else {
                    Lib\Css::add($widget['css']);
                }
            }
        }

        unset($widget);

        $vars = array(
            'widgets' =>  $widgets,
            'pageId'  => $pageId,
        );

        $widgetHtml = $this->view->get('WidgetsList.php', $vars);



        return $widgetHtml;

    }

    /**
     * Метод удаляет все виджеты на странице
     * @param $pageId
     */
    public function deleteAllWidgetsByPageId($pageId)
    {
        $this->model->deleteAllWidgetsByPageId($pageId);
    }


    /**
     * Метод возвращает элемент формы "CSS класс виджета"
     * @return mixed
     */
    public function getCssClassFormElement()
    {
        return include MODULE_DIR . DS . $this->moduleName . DS . 'Forms' .DS . 'CssClass.php';
    }


}