<?php
use Monstercms\Core\Module;

$indexPageIndex = Module::get('Site')->getIndexObjectId();
$this->setObjectId($indexPageIndex);
$this->viewAction();
