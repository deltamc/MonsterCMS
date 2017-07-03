<?php namespace Monstercms\Core;
/**
 *
 * Class Theme
 * @package Monstercms\Core
 */
class Theme
{
    private static $themes = null;

    /**
     * Метод возвращает массив с информацией об установленных темах
     * @param $dir - директория с темами
     * @return array([папка/тема] => Array
     *         (
     *               name    => название темы
     *               file    => php файл темы
     *               style   => css файл
     *               preview => файл-превью темы,
     *               dir     => папка
     *         )
     * )
     *
     * @throws \Exception
     */
    public static function getThemes($dir = null)
    {
        if ($dir === null) {
            $dir = THEMES_DIR_MAIN;
        }

        if (isset(self::$themes[$dir])) {
            return self::$themes[$dir];
        }

        $out = array();

        if (!is_dir($dir)) {
            throw new \Exception("Directory with themes not found");
        }

        if (!($dh = opendir($dir))) {
            throw new \Exception("Error reading directory with themes");
        }

        while (($file = readdir($dh)) !== false) {
            if (is_dir($dir . DS . $file) && file_exists($dir . DS . $file . DS . 'Config.php')) {
                $conf = include($dir . DS . $file . DS . 'Config.php');

                if(!is_array($conf) || empty($conf)) continue;

                foreach ($conf as $theme => $value) {
                    $out[$file . '/' . $theme] = $value;
                    $out[$file . '/' . $theme]['dir'] = $file;
                }
            }
        }
        closedir($dh);

        self::$themes[$dir] = $out;

        return $out;
    }

    /**
     * Метод возвращает информацию о теме
     * @param $theme string  - тема, формат: папка/тема
     * @param null $dir папка с темами
     * @return null
     * @throws \Exception
     */
    public static function getTheme($theme, $dir = null)
    {
        $themes = self::getThemes($dir);

        if (isset($themes[$theme])) {
            return $themes[$theme];
        }

        return null;
    }

    /**
     * метод возвращает массив с превью установленных тем (для элемента select)
     * @param null $dir
     * @return array
     * @throws \Exception
     */
    public static function getPreviewsForAttr($dir = null)
    {
        if ($dir === null) {
            $dir = THEMES_DIR_MAIN;
        }

        $themes = self::getThemes($dir);


        $out = array();

        foreach ($themes as $theme => $conf) {
            $patch =  explode('/', $theme);
            $dirTheme = $patch[0];
            $out[$theme] =  'data-preview="'.$dirTheme . '/' . $conf['preview'].'"';
        }

        return $out;
    }

    /**
     * метод возвращает массив с названиями установленных тем
     * @param null $dir
     * @return array
     * @throws \Exception
     */
    public static function getNames($dir = null)
    {
        if ($dir === null) {
            $dir = THEMES_DIR_MAIN;
        }

        $themes = self::getThemes($dir);


        $out = array();

        foreach ($themes as $theme => $conf) {

            $out[$theme] =  $conf['name'];
        }

        return $out;
    }
}