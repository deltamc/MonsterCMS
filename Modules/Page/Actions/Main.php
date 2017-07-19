<?php
use Monstercms\Core\Module;

$indexPageIndex = Module::get('Site')->getIndexObjectId();

$this->actionView($indexPageIndex, array());
