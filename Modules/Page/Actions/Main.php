<?php
use Monstercms\Core\Module;

$indexPageIndex = Module::get('Site')->getIndexObjectId();
print $indexPageIndex;
$this->actionView($indexPageIndex, array());
