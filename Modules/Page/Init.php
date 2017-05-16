<?php
if(empty($moduleName)) return;

use \Monstercms\Core;

/*display form add a menu item (page)*/
Core\Events::subs('Site.addFormTabBaseAfter', $moduleName, 'eventMenuItemAddForm', 0);
Core\Events::subs('Site.editFormTabBaseAfter', $moduleName, 'eventMenuItemEditForm', 0);

Core\Events::subs('Site.menuItemAddSave', $moduleName, 'eventMenuItemAddFormSave', 0);

Core\Events::subs('Site.menuItemEditEnd',      $moduleName, 'eventMenuItemEditFormSaveEnd', 0);
Core\Events::subs('Site.menuItemEditFullForm', $moduleName, 'eventMenuItemEditFullForm', 0);

Core\Events::subs('Site.menuItemDeleteBefore', $moduleName, 'menuItemDelete', 0);