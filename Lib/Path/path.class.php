<?php

namespace  Monstercms\Lib;

class Path
{
    public static function this_url()
    {
        $default_port = 80;
        $result = 'http://';

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on'))
        {
            $result = 'https://';
            $default_port = 443;
        }

        $result .= $_SERVER['SERVER_NAME'];


        if ($_SERVER['SERVER_PORT'] != $default_port) $result .= ':'.$_SERVER['SERVER_PORT'];

        $result .= $_SERVER['REQUEST_URI'];

        return $result;
    }

    public static function add_param($requrest)
    {
        $this_url = self::this_url();

        $out = $this_url;

        if(!preg_match("/\?/", $this_url)) $out .= '?';
        else                               $out .= '&';

        $out .= $requrest;

        return $out;
    }

    public static function replace($params = array(), $url = null)
    {
        if(!$url) $url = self::this_url();
        $out = $url;
        foreach ($params as $key => $value)
        {
            $out = preg_replace('/('.$key.'=[^&]+)/', $key.'='.$value, $out);
        }
        return $out;
    }

}