<?php namespace Monstercms\Modules\Admin;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Lib\View;


class Controller extends Core\ControllerAbstract
{
    public function IndexAction()
    {

        View::setBasicTemplate(THEMES_DIR_ADMIN . DS . THEMES_ADMIN .DS . 'Entry.php');
        View::replace('BASE', BASE_ADMIN);
        $this->view->add('TITLE', 'MonsterCMS');
        $form = Core\Module::get('Users')->entryForm();
        $this->view->add('BODY', $form);
    }
}