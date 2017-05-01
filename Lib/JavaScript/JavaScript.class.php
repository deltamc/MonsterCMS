<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class JavaScript
{
    static public $files = array();


    /**
     * функция добавляет файл или код javascript
     * @param $js
     *
     */
    static public function add($js)
    {
        /*если файл, добавляем в массив*/


        if(preg_match("/.js$/", $js))
        {
            if(!self::is($js))
                self::$files[] = array($js, 'F');

        }
        else if(!self::is($js))
            self::$files[] = array($js, 'C');

    }
    static public function is($js)
    {
        foreach(self::$files as $js_item)
        {
            if($js_item[0] == $js) return true;
        }
        return false;
    }


    static public function get()
    {
        $out = '';




        foreach(self::$files as $js)
        {
            if(empty($js[0])) continue;
            if($js[1] == 'C') $out .= '<script>jQuery(function(){'.$js[0].'});</script>'.PHP_EOL;
            if($js[1] == 'F') $out .= '<script src="'.$js[0].'"></script>'.PHP_EOL;
        }


        return $out;
    }
}