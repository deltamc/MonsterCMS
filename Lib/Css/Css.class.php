<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class Css
{
    static public $files = array();


    /**
     * функция добавляет файл или код javascript
     * @param $js
     *
     */
    static public function add($css)
    {
        /*если файл, добавляем в массив*/
        if(preg_match("/.css$/", $css))
        {
            if(!self::is($css)) self::$files[] = array($css, 'F');
        }
        else self::$files[] = array($css, 'C');

    }

    static public function is($js)
    {
        foreach(self::$files as $item)
        {
            if($item[0] == $js) return true;
        }
        return false;
    }


    static public function get()
    {
        $out = '';

        foreach(self::$files as $css)
        {
            if($css[1] == 'C') $out .= '<style type="text/css">'.$css[0].'</style>'.PHP_EOL;
            if($css[1] == 'F') $out .= '<link rel="stylesheet" type="text/css" href="'.$css[0].'"/>'.PHP_EOL;
        }
        return $out;
    }
}