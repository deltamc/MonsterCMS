<?php namespace Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Class View - класс для управления видами
 * @package Monstercms\Lib
 */
class View
{
    protected static $placeholder = array();
    protected static $basicTemplate;
    protected  $includePath = array();

    /**
     * Метод регистрирует пути к файлам-шаблонам
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        $this->includePath = $paths;
    }
    /**
     * Метод Устанавливает файл основного шаблона
     * @param array $file
     */
    public static function setBasicTemplate($file)
    {
        self::$basicTemplate = $file;
    }

    /**
     * Метод возвращает имя файла основного шаблона
     * @return string
     */
    public static function getBasicTemplate()
    {
        return self::$basicTemplate;
    }

    /**
     * Метод добавляет плейсхолдер
     * @param $placeholder
     * @param $html
     */
    public static function add($placeholder, $html)
    {
        if(isset(self::$placeholder[$placeholder])) {
            self::$placeholder[$placeholder] .= $html;
        } else {
            self::$placeholder[$placeholder] = $html;
        }

    }

    /**
     * Метод заменяет содержимое плейсхолдера
     * @param $placeholder
     * @param $html
     */
    public static function replace($placeholder, $html)
    {
        self::$placeholder[$placeholder] = $html;
    }

    /**
     * Метод удаляет плейсхолдер
     * @param $placeholder
     */
    public static function clear($placeholder)
    {
        unset (self::$placeholder[$placeholder]);
    }

    /**
     * Метод считывает шаблон с файла и добавляет в плейсхолдер
     * @param $placeholder
     * @param $file
     * @param $vars
     * @throws \Exception
     */
    public function inc($placeholder, $file, $vars = array())
    {
        if (empty($this->includePath)) {
            throw new \Exception("The paths templates is not defined");
        }

        $inc = $this->getPath($file);

        if(isset(self::$placeholder[$placeholder])) {
            self::$placeholder[$placeholder] .= self::readFile($inc, $vars);
        } else {
            self::$placeholder[$placeholder] = self::readFile($inc, $vars);
        }


    }

    /**
     * Метод возвращает сгенерированный шаблон
     * @param $file
     * @param array $vars
     * @return string
     * @throws \Exception
     */
    public function get($file, $vars = array())
    {
        if (empty($this->includePath)) {
            throw new \Exception("The paths templates is not defined");
        }

        $inc = $this->getPath($file);

        return self::readFile($inc, $vars);
    }

    /**
     * Метод возвращает путь к шаблонам
     * @param $file
     * @return null|string
     */
    protected function getPath($file) {
        $inc = null;

        foreach($this->includePath as $path) {

            $path = trim($path, DIRECTORY_SEPARATOR);
            $inc = $path . DIRECTORY_SEPARATOR . $file;
            if (is_file($inc )) {
              return $inc;

            }
        }

        return $inc;
    }

    /**
     * Метод выводит на экран сгенерированный шаблон
     * @throws \Exception
     */
    public static function render()
    {
        if (!self::$basicTemplate) {
            throw new \Exception("The base template is not defined");
        }

        echo self::readFile(self::$basicTemplate, self::$placeholder);
    }

    /**
     * Метод читает файл шаблона
     * @param $file
     * @param $vars array - массив переменных которые видны в файле шаблона
     * @return string
     * @throws \Exception
     */
    protected static function readFile($file, array $vars = array())
    {

        if (!is_file($file)) {
            //$pathParts = pathinfo($file);
            $file = htmlspecialchars($file);
            throw new \Exception("Include file '{$file}' not found");
        }

        if(is_array($vars) && count($vars) > 0) extract($vars);

        Ob_start ();
        Ob_implicit_flush(0);

        include($file);

        $out = ob_get_contents ();
        Ob_end_clean ();

        return $out;
    }

}


