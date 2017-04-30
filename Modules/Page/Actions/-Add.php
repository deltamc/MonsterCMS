<?php

use \Monstercms\Modules\page as This;
use \Monstercms\Core as Core;
use \Monstercms\Core\MCMS;
use \Monstercms\Lib as Lib;

if(!is_admin()) throw new Core\HttpErrorException(403);



$date = array
(
    //'name'              => (!empty($_POST['name'])) ? hc($_POST['name']) : '',
    'date_create'     => time(),
    'date_update'     => time(),
);

$page = $this->model->add($date, $url);

$this->modules->McmsPageHead->saveSeoForm($this->moduleName, $page['id']);

return array('object_id' => $page['id'], 'url_id' => $page['url_id'] );







