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


}