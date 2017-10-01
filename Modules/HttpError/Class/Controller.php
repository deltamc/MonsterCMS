<?php namespace Monstercms\Modules\HttpError;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Lib\View;
use \Monstercms\Core\Theme;

use \Monstercms\Lib;


class Controller extends Core\ControllerAbstract
{
    function eventError(Core\EventParam $ep)
    {
        $code = (int) $ep->getParam('code');
        $themeInfo = Theme::getTheme(THEME);
        if (isset($themeInfo['error' . $code])) {

            View::setBasicTemplate(THEMES_DIR_MAIN . DS . $themeInfo['dir'] . DS . $themeInfo['error404']);
        }




    }
}