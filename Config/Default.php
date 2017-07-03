<?php
defined('MCMS_ACCESS') or die('No direct script access.');
/**
 * файл конфигурации сайта.
 *
 * Для переопределении настроек для других доменов,
 * нужно скопировать данный файл и переименовать subdomen.mysite.ru.php
 *
 * Если для текущего домена не задан конфигурационный файл,
 * конфигурации берутся из файла default.php
 *
 */

/**
 *
 */
define('DB_CONNECTION', 'mysql:host=localhost;dbname=monstercms;charset=utf8');

/**
 * Имя пользователя БД
 */
define('DB_USER', 'root');

/**
 * Пароль к БД
 */
define('DB_PASSWORD', '');

/*URL сайта*/
define("SITE_URL",   "//" . HOST);


/**
 * Префикс таблиц БД
 */
define('DB_PREFIX', 'mcms_');


/**
 * Имя таблицы с URL
 */
define('DB_TABLE_URL', DB_PREFIX . 'urls');

/**
 * Имя таблицы с заголовками страниц
 */
define('DB_TABLE_PAGE_HEAD',   DB_PREFIX . 'page_head');


define('DB_TABLE_USERS',   DB_PREFIX . 'users');

define('DB_TABLE_USERS_DATA',   DB_PREFIX . 'users_data');

/**
 * Тема сайта по умолчанию
 */
define('THEME', 'Surfhouse/Contact');

/**
 * Директория с темами
 */
define('THEMES_DIR_MAIN', 'Themes' . '/' . 'Main');
define('THEMES_DIR_ADMIN', 'Themes' . '/' .  'Admin');

/**
 * Тема адинки
 */
define('THEMES_ADMIN', 'Classic');

/**
 * Шаблон диалогового окна
 *
 */
define('THEMES_DIALOG_PATH', THEMES_DIR_ADMIN . DS . THEMES_ADMIN .DS . 'Dialog.php');

/*
 * Базовый путь диалогового окна
 */
define('BASE_DIALOG', SITE_URL . '/' . THEMES_DIR_ADMIN .  '/' . THEMES_ADMIN . '/');

/**
 * Временная зона
 */
define('TIMEZONE', 'Europe/Moscow');

/**
 * Модуль по умолчанию
 */
define('DEFAULT_MODULE', 'Page');


/**
 * Экшин по умолчанию
 */
define('DEFAULT_ACTION', 'Main');

/**
 * Директория с библиотеками
 */
define('LIB_DIR', 'Lib');

/**
 * Директория с модулями
 */
define('MODULE_DIR', 'Modules');



/**
 * Системная директория
 */
define('ENGINE_DIR', 'Core');

/**
 * Язык по умолчанию
 */
define('LOCALE', 'ru');

/**
 * окончание URL`а в ЧПУ
 */
define('URL_SEMANTIC_END', '.html');


//define('DOCUMENT_ROOT',   $_SERVER['DOCUMENT_ROOT']);
/**
 * Директория для загрузки файлов
 */
define('UPLOAD_ROOT', 'Upload');

//define('MODULE_LOAD_SCRIPT',   'events' . DS . 'load.php');

/**
 * Режим отладки
 *
 * true - в этом режиме веб-сервер показывает все ошибки,
 * предупреждения и исключения
 * false - в таком режиме веб-сервер не показывает ошибки
 */
define('DEBUGGING',   true);


/**
 * Название сайта
 * @TODO брать из настоек в бд
 */
define('SITE_NAME',   'Монстер CMS');

