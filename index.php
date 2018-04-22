<?php
use Monstercms\Lib;
use Monstercms\Core;
use Monstercms\Core\Module;
use Monstercms\Lib\View;

ini_set('session.cookie_httponly ', 1);


define('MCMS_VERSION',   '1.3.0');
define('HOST',        $_SERVER["HTTP_HOST"]);
define('DS',          DIRECTORY_SEPARATOR);
define('MCMS_ACCESS', true);
define('ROOT',        rtrim(dirname(__FILE__), '\\/'));

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

try {
    $front = new Core\FrontController();
    $front->route();
} catch (Core\HttpErrorException $e) {

    $e->header();
    Core\Events::cell('Core.HttpError', 'void', array('code' => $e->getCode()));

} catch (PDOException $e) {
    if (DEBUGGING) {
        print_r($e->getMessage());
    }
} catch (Exception $e) {
    if (DEBUGGING) {
        print 'Error: ' . $e->getMessage() .
            ' (code:'   . $e->getCode() . ') <br />' .
            ' File:'    . $e->getFile() . ' <br />' .
            ' Line:'    . $e->getLine() . ' <br />' .
            ' Trace:'   . str_replace("\n", "<br>\n", $e->getTraceAsString());
    }
}


//Выводим меню администратора
View::add('ADMIN_TOOLS','');
if (Core\User::isAuthorization()) {
    Module::get('MenuAdmin')->view();
}




View::add('HEAD',        '');
View::add('SITE_URL',    SITE_URL);
View::add('CSS',         Lib\Css::get());
View::add('JS',          Lib\JavaScript::get());

$pageHead = Core\PageSemantic::init();

if ($pageHead->getLastModified()) {
    Lib\Header::lastModified($pageHead->getLastModified());
}


View::add('NOINDEX',     $pageHead->isNoindex());
View::add('TITLE',       $pageHead->getTitle());
View::add('DISCRIPTION', $pageHead->getDescription());
View::add('CANONICAL',   $pageHead->getCanonical());
View::add('KEYWORDS',    $pageHead->getKeywords());


View::render();