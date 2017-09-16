<?php

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core;
use Monstercms\Core\Module;

if(empty($moduleName)) return;

Core\Events::subs('MenuAdmin.addItems',  $moduleName, 'eventAddItemAdminMenuLogOut');
Core\Events::subs('MenuAdmin.addItems',  $moduleName, 'eventAddItemAdminMenuUsers',2);

