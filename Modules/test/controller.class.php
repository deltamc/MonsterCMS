<?php namespace Monstercms\Modules\test;

use \Monstercms\Core;
/**
 * Created by PhpStorm.
 * User: bioma_000
 * Date: 03.04.2017
 * Time: 21:22sss
 */
class Controller extends Core\ControllerAbstract
{
    function testAction()
    {
        $this->view->inc('BODY', 'head.php', array('head' => "Заголовок"));

    }
}