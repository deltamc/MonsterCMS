<?php namespace Monstercms\Core;


class Tag
{
    static private $_tags = array();

    function __get($name)
    {
        if(!isset(self::$_tags[$name])) return null;

        return self::$_tags[$name];
    }

    function __set($name, $value)
    {
        self::$_tags[$name] = $value;
    }

    public function get()
    {
        return self::$_tags;
    }
}