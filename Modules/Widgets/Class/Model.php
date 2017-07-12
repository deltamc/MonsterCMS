<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib;
use Monstercms\Core;


class Model extends Core\ModelAbstract
{
    /**
     * экземпляры контроллеров виджетов
     * @var array
     */
    private $widgetsControllers = array();
}