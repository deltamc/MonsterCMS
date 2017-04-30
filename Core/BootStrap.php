<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib\View;
use Monstercms\Lib\javascript;
use Monstercms\Lib\css;


//Устанавливаем кодировку utf-8
Header('Content-Type: text/html; charset=utf-8');

//Временная зона
@ini_set('date.timezone', TIMEZONE);
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set(TIMEZONE);
} else {
    putenv('TZ='.TIMEZONE);
}

//Режим отладки
if (DEBUGGING) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    ini_set('error_reporting', 0);
    ini_set('display_errors', 0);
}

//Подключаем файлы
require_once(LIB_DIR . DS . 'Autoload' . DS . 'Autoload.class.php');
require_once(ENGINE_DIR . DS . 'Autoload.php');
require_once(ENGINE_DIR . DS . 'Function.php');

View::setBasicTemplate(THEMES_DIR . DS . THEME . DS . 'Base.php');
View::add('BODY',  '');


JavaScript::add('/JavaScript/jquery.min.js');
JavaScript::add('/JavaScript/mcms.windows.jquery.js');
Css::add('/' . THEMES_DIR . '/' . THEMES_ADMIN . '/css/ui.css');
