<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib\View;
use Monstercms\Lib\JavaScript;
use Monstercms\Lib\Css;
use Monstercms\Core;


//Устанавливаем кодировку utf-8
Header('Content-Type: text/html; charset=utf-8');

//Временная зона
Core\Mcms::setTimeZone(TIMEZONE);

//Режим отладки
Core\Mcms::showError(DEBUGGING);

//Подключаем файлы
require_once(LIB_DIR . DS . 'Autoload' . DS . 'Autoload.class.php');
require_once(ENGINE_DIR . DS . 'Autoload.php');
require_once(ENGINE_DIR . DS . 'Function.php');

View::setBasicTemplate(THEMES_DIR . DS . THEME . DS . 'Base.php');
View::add('BODY',  '');


JavaScript::add(SITE_URL . '/JavaScript/jquery.min.js');
JavaScript::add(SITE_URL . '/JavaScript/mcms.windows.jquery.js');
Css::add(SITE_URL . '/' . THEMES_DIR . '/' . THEMES_ADMIN . '/css/ui.css');
