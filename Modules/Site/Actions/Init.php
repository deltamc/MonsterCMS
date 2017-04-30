<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;

Core\Events::subs('MenuAdmin.addItems',  $moduleName, 'eventAddItemAdminMenu');
Core\Events::subs('Site.menuAddEnd',     $moduleName, 'goToStructureUrl');
Core\Events::subs('Site.menuEditEnd',    $moduleName, 'goToStructureUrl');
Core\Events::subs('Site.menuDeleteEnd',  $moduleName, 'goToStructureUrl');

