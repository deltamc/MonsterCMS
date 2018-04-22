<?php

defined('MCMS_ACCESS') or die('No direct script access.');


/**
 * Created by PhpStorm.
 * User: Danila
 * Date: 25.02.2016
 * Time: 11:33
 */
namespace  Monstercms\Lib;

class String
{
    /**
     * @param $string - ������, ��������:
     * fds@fds.ru,,, fdsa@fds.re gfds@fgd.tr;fdsafdsa@fds.er,fdsa@gfd.rt
     * @param array $delimiters - ������ ������������
     * @return array - ������ �������� ������
     *  Array
     *   (
     *       [0] => fds@fds.ru
     *       [1] => fdsa@fds.re
     *       [2] => gfds@fgd.tr
     *       [3] => fdsafdsa@fds.er
     *       [4] => fdsa@gfd.rt
     *   )
     */
    static function toArray($string, $delimiters = array(',', ';') )
    {
        foreach($delimiters as $delimiter) $string  = str_replace($delimiter, ' ', $string);
        $string = preg_replace('/\s{2,}/',' ',$string);
        $array = explode(' ', $string);
        return $array;
    }
}