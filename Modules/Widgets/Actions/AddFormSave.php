<?php namespace Monstercms\Modules\Widgets;
use \Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

$params = $this->getParams();
if(!isset($params['widget'])) {
    throw new Core\HttpErrorException(403);
}
$widget     = $params['widget'];
$widgetName = $params['widgetName'];

$this->model->add($widget, $_POST);

exit();