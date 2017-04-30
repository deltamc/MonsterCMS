<?php namespace Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Class Lang
 *
 * TODO Формы множественного числа
 * TODO Резервный язык локализации
 * TODO Spin-текст
 *
 */
class Lang {

    CONST DS = DIRECTORY_SEPARATOR;

    /**
     * The default locale being used by the translator.
     *
     * @var string
     */
    private static $locale      = "ru";

    /**
     * The default locale being used by the translator.
     *
     * @var string
     */
    private static $lang_path   = 'Lang';

    /**
     * The array of loaded translation groups.
     *
     * @var array
     */
    private static $loaded = array();


    public static function get($key, $replace = array(), $locale = null)
    {
        $module = null;
        $index  = null;

        if(is_null($locale)) $locale = self::$locale;

        list($module, $index) = explode(".", $key);

        self::load($module, $locale);

        /* If the line doesn't exist, we will return back the key which was requested as
         * that will be quick to spot in the UI if language keys are wrong or missing
         * from the application's language files. Otherwise we can return the line.
         */

        if (!isset(self::$loaded[$module][$locale][$index])) return $key;

        $line = self::$loaded[$module][$locale][$index];
        $line = self::makeReplacements($line, $replace);

        return $line;
    }

    /**
     * Make the place-holder replacements on a line.
     *
     * @param  string  $line
     * @param  array   $replace
     * @return string
     */
    protected function makeReplacements($line, $replace)
    {
        if (empty($replace) || !is_array($replace)) return $line;

        foreach ($replace as $key => $value)
        {
            $line = str_replace(':'.$key, $value, $line);
        }
        return $line;
    }

    /**
     * Load the specified language group.
     *
     * @param  string  $module
     * @return void
     */
    public static function load($module, $locale)
    {
        if (self::isLoaded($module, $locale)) return;

        $file = self::getLocaleFile($module, $locale);



        if (!is_file($file))
        {
            throw new \Exception('Language file "' . $file . '" is not found');
        }

        self::$loaded[$module][$locale] = include_once($file);
    }

    /**
     * Determine if the given group has been loaded.
     *
     * @param  string  $namespace
     * @param  string  $group
     * @param  string  $locale
     * @return bool
     */
    public static function isLoaded($module, $locale)
    {
        return isset(self::$loaded[$module][$locale]);
    }

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public static function getLocale()
    {
        return self::$locale;
    }

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     * @return void
     */
    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }

    /**
     * Gets the path to the language file
     *
     * @param $module
     * @param $locale
     * @return string
     */
    private static function getLocaleFile($module, $locale)
    {
        $locale = ucfirst($locale);
        return  MODULE_DIR . self::DS . $module . self::DS . self::$lang_path .
            self::DS . $locale . '.php';
    }


    /**
     * Determine if a translation exists.
     *
     * @param  string  $key
     * @param  string  $locale
     * @return bool
     */
    public static function has($key, $locale = null)
    {
        return self::get($key, array(), $locale) !== $key;
    }


}
/*
Lang::setLocale('ru');

print Lang::get('articles.welcome');
print"<br />";
print Lang::get('articles.hello', array('name' => 'Данила'));
*/
