<?php
if(empty($moduleName)) return;

use \Monstercms\Core;

/*display form add a menu item (page)*/
Core\Events::subs('Site.addFormTabBaseAfter', $moduleName, 'eventMenuItemAddForm');
Core\Events::subs('Site.editFormTabBaseAfter', $moduleName, 'eventMenuItemEditForm');

Core\Events::subs('Site.menuItemAddSave', $moduleName, 'eventMenuItemAddFormSave');
Core\Events::subs('Site.menuItemAddEnd', $moduleName, 'eventMenuItemAddFormSaveEnd');

Core\Events::subs('Site.menuItemEditEnd',      $moduleName, 'eventMenuItemEditFormSaveEnd');
Core\Events::subs('Site.menuItemEditFullForm', $moduleName, 'eventMenuItemEditFullForm');

Core\Events::subs('Site.menuItemDeleteBefore', $moduleName, 'menuItemDelete');

Core\Events::subs('Site.menuItemAddModuleList', $moduleName, 'menuItemAddModuleList', 2);
Core\Events::subs('Site.menuItemAddModuleList', $moduleName, 'menuItemAddModuleList2', 1);


Core\Events::subs('Widgets.addWidget', $moduleName, 'lastModifiedUpdate');
Core\Events::subs('Widgets.editWidget', $moduleName, 'lastModifiedUpdate');
Core\Events::subs('Widgets.deleteAfterWidget', $moduleName, 'lastModifiedUpdate');
