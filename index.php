<?php

use Monstercms\Lib;
use Monstercms\Core;
use Monstercms\Core\Module;
use Monstercms\Lib\View;

define('MCMS_VERSION',   '1.3.0');
define('HOST',        $_SERVER["HTTP_HOST"]);
define('DS',          DIRECTORY_SEPARATOR);
define('MCMS_ACCESS', true);
define('ROOT',        rtrim(dirname(__FILE__), '\\/'));
define("SITE_URL",   "//" . HOST);
define('CONFIG_DIR', 'Config');


if (version_compare(PHP_VERSION, "5.3.2", "<")) {
    exit("MonsterCMS requires PHP 5.3.2 or greater.");
}

//Подключаем конфигурационный файл
$configFile  = HOST;
$configFile  = preg_replace('/[^\.\d\w-]/', '', $configFile);
$configFile .= '.php';
$configDir   = ROOT . DS . CONFIG_DIR . DS;

if (is_file($configDir . $configFile)) {
    require_once($configDir . $configFile);
} else {
    require_once($configDir . 'Default.php');
}


require_once(ENGINE_DIR . DS . 'BootStrap.php');

try{
    $front = new Core\FrontController();
    $front->route();
}
catch (Core\HttpErrorException $e)
{
    $e->header();
    Core\Events::cell('ErrorPage.'.$e->getCode());
}
catch (PDOException $e) {
    print_r($e->getMessage());

}
catch (\Exception $e)
{
    if(DEBUGGING)
    {
        print 'Error: ' . $e->getMessage() .
            ' (code:'   . $e->getCode() . ') <br />' .
            ' File:'    . $e->getFile() . ' <br />' .
            ' Line:'    . $e->getLine() . ' <br />' .
            ' Trace:'   . $e->getTraceAsString();
    }
}


//Выводим меню администратора
Module::get('MenuAdmin')->view();


View::add('THEME_PATH',  'themes' . DS . THEME . DS);
View::add('BASE',        SITE_URL. '/' . THEMES_DIR .  '/' . THEME . '/');
View::add('HEAD',        '');
View::add('CSS',         Lib\Css::get());
View::add('JS',          Lib\JavaScript::get());
//View::add('ADMIN_TOOLS', '');

$pageHead = Core\PageHead::init();

View::add('NOINDEX',     $pageHead->isNoindex());
View::add('TITLE',       $pageHead->getTitle());
View::add('DISCRIPTION', $pageHead->getDescription());
View::add('CANONICAL',   $pageHead->getCanonical());
View::add('KEYWORDS',    $pageHead->getKeywords());

View::render();





/*
$MODULE = new Core\Module();



Core\Lang::setLocale(LOCALE);

$TAG = new Core\Tag();

$TAG->BODY = "";
$TAG->HEAD = "";
$TAG->TITLE = "";
$TAG->ADMIN_TOOLS = "";

$TPL = new Lib\littletempl("themes/".THEME, "themes/monster_cms");

//$USER      = new Lib\users(DB_PREFIX."user");
$USER_INFO = false;
//$USER_IS   = $USER->is_user();
//$USER_IS     = false;


$TYPE = (!empty($_GET['type'])) ? $_GET['type'] : 'main';

try{
    require_once("core" . DS . "module_load.php");

    // подкючаем файлы
    require_once("core/route.php");



}
catch (Core\HttpErrorException $e)
{

    $e->header();

    Core\Events::cell('error_pages.'.$e->getCode());

}
catch (\Exception $e)
{
    if(DEBUGGING)
    {
        print 'Error: ' . $e->getMessage() .
            ' (code:'  . $e->getCode() . ') <br />' .
            ' File:'    . $e->getFile() . ' <br />' .
            ' Line:'    . $e->getLine() . ' <br />' .
            ' Trace:'    . $e->getTraceAsString();
    }
}
exit();

if($USER_IS) $USER_INFO = $USER->info();


if(!$USER_IS && isset($_GET['admin'])) {

    $tags = array("form" => $USER->entryForm());

    $TAG->ADMIN_TOOLS = $TPL->get("authorization.php", $tags);

}
elseif (isset($_GET['entry'])) $USER->reload();
elseif (isset($_GET['exit']))  $USER->uexit("/");





//Core\Events::view();


//module_load();

// Downloadable Modules





if($USER_IS)
{
    //$ADMIN_TOOLS = admin_tools($ADMIN_BUTTONS);
    $TAG->ADMIN_TOOLS = Core\Events::cell('menuAdmin.view','string', array());
}



$TAG->JS          = Lib\JavaScript::get();
$TAG->CSS         = Lib\Css::get();
$TAG->THEME_PATH  = "themes/".THEME."/";
$TAG->BASE        = SITE_URL."/themes/".THEME.'/';
$TAG->MODULE        = $MODULE;



switch($TYPE)
{
    case 'ajax':
        print $TAG->BODY;
        break;

    case 'dialog':
        $TAGS = $TAG->get();
        print $TPL->get("dialog.php", $TAGS);
        break;

    default:

        $pageHead = Core\PageHead::init();


        $TAG->TITLE       = $pageHead->getTitle();
        $TAG->DISCRIPTION = $pageHead->getDescription();
        $TAG->KEYWORDS    = $pageHead->getKeywords();
        $TAG->NOINDEX     = $pageHead->isNoindex();
        $TAG->CANONICAL   = $pageHead->getCanonical();

        $TAGS = $TAG->get();
        print $TPL->get("index.php", $TAGS);
        break;
}

*/