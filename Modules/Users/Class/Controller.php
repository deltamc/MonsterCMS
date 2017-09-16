<?php namespace Monstercms\Modules\Users;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core;
use \Monstercms\Core\Lang;
use \Monstercms\Lib;
use \Monstercms\Lib\Request;
use Monstercms\Core\MCMS;
use Monstercms\Core\Module;

class Controller extends Core\ControllerAbstract
{
    public function eventAddItemAdminMenuLogOut()
    {
        return array
        (
            //Шаблон пункта
            'type'        => 'ButtonIco',
            // Ссылка
            'action'      => '/' . $this->moduleName . '/LogOut',
            // Иконка (FontAwesome)
            'ico'         => 'fa-sign-out',
            // Текст ссылки
            'text'        => Core\Lang::get('Users.logOut'),
            // Выравнивание
            'align'       => 'right',
        );
    }

    public function eventAddItemAdminMenuUsers()
    {
        return array
        (
            //Шаблон пункта
            'type'        => 'Button',
            // Ссылка
            'action'      => '/' . $this->moduleName . '',
            // Иконка (FontAwesome)
            'ico'         => 'fa-users',
            // Текст ссылки
            'text'        => Core\Lang::get('Users.users'),
            // Выравнивание
            'align'       => 'left',
            //Открывать в диалоговом окне
            'target'      => 'dialog',
            //Размер окна
            'window_size' => '800x600'
        );
    }

    public function entryForm(){
        $html = '';
        $form = new Lib\Form();

        if ($form->is_submit()
            && $form->is_valid()
            && Request::getPost('login')
            && Request::getPost('password')
        ) {


            $result = Core\User::authorizationByLoginAndPassword(
                Request::getPost('login'),
                Request::getPost('password')
            );

            if (!$result) {
                $error = Core\Lang::get('Users.wrongLoginOrPassword');

            } else {

                header("Location: " . $this->config['redirectAfterEntry']);
                exit;
            }
        }

        $itemsForm = include($this->modulePath . 'Forms' . DS . 'Entry.php');
        $form->add_items($itemsForm);

        if (!$form->is_submit() || !empty($error)) {
            $html  = $form->render();

        } elseif (!$form->is_valid()) {
            $html .= $form->error();
        }

        return $html;
    }
}