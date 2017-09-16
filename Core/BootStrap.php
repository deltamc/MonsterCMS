<?php
defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Lib\View;
use Monstercms\Lib\JavaScript;
use Monstercms\Lib\Css;
use Monstercms\Core;


//Устанавливаем кодировку utf-8
Header('Content-Type: text/html; charset=utf-8');

//Подключаем файлы
require_once(LIB_DIR . DS . 'Autoload' . DS . 'Autoload.class.php');
require_once(ENGINE_DIR . DS . 'Autoload.php');
require_once(ENGINE_DIR . DS . 'Function.php');

//Временная зона
Core\Mcms::setTimeZone(TIMEZONE);

//Режим отладки
Core\Mcms::showError(DEBUGGING);

View::add('BODY',  '');

Core\Mcms::setTheme();

Core\User::int();

JavaScript::add(SITE_URL . '/JavaScript/jquery.min.js');
JavaScript::add(SITE_URL . '/JavaScript/mcms.windows.jquery.js');
Css::add(SITE_URL . '/' . THEMES_DIR_ADMIN . '/' . THEMES_ADMIN . '/css/main.css');
