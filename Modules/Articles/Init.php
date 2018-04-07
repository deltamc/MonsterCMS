<?php
defined('MCMS_ACCESS') or die('No direct script access.');

if(empty($moduleName)) return;

use \Monstercms\Core;

Core\Events::subs('Site.menuItemAddModuleList', $moduleName, 'menuItemAddModuleList', 1);
Core\Events::subs('Site.addFormTabBaseAfter', $moduleName, 'eventMenuItemForm');
Core\Events::subs('Site.editFormTabBaseAfter', $moduleName, 'eventMenuItemForm');
Core\Events::subs('Site.menuItemAddSave', $moduleName, 'eventMenuItemAddFormSave');
Core\Events::subs('Site.menuItemAddEnd', $moduleName, 'eventMenuItemAddFormSaveEnd');
Core\Events::subs('Site.menuItemEditFullForm', $moduleName, 'eventMenuItemEditFullForm');
Core\Events::subs('Site.menuItemEditEnd',      $moduleName, 'eventMenuItemEditFormSaveEnd');
Core\Events::subs('Page.bottom', $moduleName, 'eventPageBottom');
Core\Events::subs('Site.menuItemDeleteBefore', $moduleName, 'eventMenuItemDeleteBefore');