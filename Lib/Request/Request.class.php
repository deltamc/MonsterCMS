<? namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class Request
{
    /**
     * Метод возвращает значение $_POST[name]
     * @param $name
     * @param bool|false $html
     * @return string
     */
    static function getPost($name, $html = false)
    {
        if (!empty($_POST[$name]))
        {
            if(!$html) return htmlspecialchars($_POST[$name]);
            else       return $_POST[$name];
        }
        else
        {
            return '';
        }

    }

}