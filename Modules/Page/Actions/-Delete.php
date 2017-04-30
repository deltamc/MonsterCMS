<?php

use \Monstercms\Modules\page as This;
use \Monstercms\Core as Core;
use \Monstercms\Lib as Lib;

if(!is_admin())       throw new Core\HttpErrorException(403);
if(!isset($id)) throw new Core\HttpErrorException(404);

$this->model->delete($id);

