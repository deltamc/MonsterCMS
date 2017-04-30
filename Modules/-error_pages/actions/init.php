<?php
if(empty($moduleName)) return;

use \Monstercms\Core as Core;

Core\Events::subs('error_pages.404', $moduleName,'action404', 1);
Core\Events::subs('error_pages.403', $moduleName,'action403', 1);