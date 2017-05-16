<?php defined('MCMS_ACCESS') or die('No direct script access.');


$autoLoad = new  Autoload();

/**
 * Классы пространства имен  Monstercms\Core
 * загружаются с паки core/class/класс.class.php *
 */
$autoLoad->addNamespace(
    'Monstercms\Core',
    ENGINE_DIR . DS . 'Class' . DS . '%class%.php'
);
/**
 * Классы пространства имен  Monstercms\Modules\Название_модуля
 * загружаются с паки modules/Название_модуля/класс.class.php *
 */
$autoLoad->addNamespace(
    'Monstercms\Modules\*',
    MODULE_DIR . DS . '$1' . DS . 'Class' . DS . '%class%.php'
);

/**
 * Классы пространства имен  Monstercms\Lib
 * загружаются с паки lib/класс/класс.class.php *
 */
$autoLoad->addNamespace(
    'Monstercms\Lib',
    LIB_DIR . DS . '%class%' . DS . '%class%.class.php'
);


$autoLoad->register();