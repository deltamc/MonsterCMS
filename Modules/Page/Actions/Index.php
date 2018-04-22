<?php

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Module;

$indexPageIndex = Module::get('Site')->getIndexObjectId();
$this->setObjectId($indexPageIndex);
$this->viewAction();
