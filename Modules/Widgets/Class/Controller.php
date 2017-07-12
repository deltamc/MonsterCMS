<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;

class Controller extends Core\ControllerAbstract
{
    public function getConfig()
    {
        return $this->config;
    }
}