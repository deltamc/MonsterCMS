<?php
if(empty($moduleName)) return;

use \Monstercms\Core;

Core\Events::subs('Core.HttpError', $moduleName, 'eventError', 0);

