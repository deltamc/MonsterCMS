<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

// /Widgets/SetPos/id1/71/pos1/2/id2/70/pos2/1

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}



if (!$this->isParam('id1')
    || !$this->isParam('pos1')
    || !$this->isParam('pos2')
    || !$this->isParam('id2')
) {
    throw new Core\HttpErrorException(404);
}

$params = $this->getParams();
$id1  = (int) $params['id1'];
$id2  = (int) $params['id2'];
$pos1 = (int) $params['pos1'];
$pos2 = (int) $params['pos2'];

$this->model->exchangePosWidget($id1, $pos1, $id2, $pos2);

exit();