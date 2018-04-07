<?php

/**
 * Created by PhpStorm.
 * User: Danila
 * Date: 25.02.2016
 * Time: 14:26
 */
namespace  Monstercms\Lib;

class Header
{
    static function location($url, $target = null)
    {
        if($target     == "blank")    print  '<script>window.open("' . $url . '");</script>';
        elseif($target == "top")      print  '<script>top.location.href="' . $url . '";</script>';
        else header("Location: " . $url);

        exit();
    }


    static function reload($target = null)
    {
        if($target     == "blank")    print  '<script>window.open(location.href);</script>';
        elseif($target == "top")      print  '<script>top.location.href= top.location.href;</script>';
        else header("Location: " . path::this_url());

        exit();
    }

    public static function lastModified($time)
    {
        $lastModified = gmdate('D, d M Y H:i:s \G\M\T', $time);

        $ifModifiedSince = false;

        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if ($ifModifiedSince && $ifModifiedSince >= $time) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            header('Last-Modified: '. $lastModified);
            exit();
        }
        header('Last-Modified: '. $lastModified);
    }


}