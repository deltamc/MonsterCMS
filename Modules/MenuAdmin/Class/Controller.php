<?php namespace Monstercms\Modules\MenuAdmin;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\User;

class Controller extends Core\ControllerAbstract
{
    /**
     * Метод отображает меню администратора
     * @return mixed
     */
    public function view()
    {
        $out = '';

        $default = array
        (
            'type'        => 'button',
            'action'      => '#',
            'ico'         => '',
            'text'        => 'button',
            'align'       => 'left',
            'target'      => '_top',
            'access'      => array(
                User::ADMIN
            ),
            'window_size' => null
        );
        //Вызываем событие menuAdmin.addItems

        $buttons = Core\Events::cell('MenuAdmin.addItems', 'array');



        if(empty($buttons)) $buttons = array();

        foreach ($buttons as $item)
        {

            if(!is_array($item)) {
                continue;
            }

            $item = array_merge ($default, $item);

            if(!User::isAccess($item['access'])){
                continue;
            }

            //if($item['target'] == 'dialog') $item['action'] .= '&type=dialog';

            $tags = array
            (
                'ACTION'      =>  $item['action'],
                'ICO'         =>  $item['ico'],
                'TEXT'        =>  $item['text'],
                'ALIGN'       =>  $item['align'],
                'TARGET'      =>  $item['target'],
                'WINDOW_SIZE' =>  $item['window_size']

            );
            $type = ucfirst($item['type']);

            $out .= $this->view->get("Buttons" . DS . $type . '.php', $tags);
        }


        $tags = array("buttons" => $out);

        $this->view->inc('ADMIN_TOOLS', "AdminTools.php", $tags);
        //return
    }
}