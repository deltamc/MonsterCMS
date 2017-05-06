<?php
/**
 * @file Menu.php
 * Данные формы Добавления и редактирования меню
 */

defined('MCMS_ACCESS') or die('No direct script access.');

use Monstercms\Core\Lang;

return array
(

    array(
        'type'  => 'event',
        'event' => $this->moduleName . '.' . $action . 'MenuFormNameBefore'
    ),
    array
    (
        'name'  => "name",
        'type'  => 'text',
        'label' => Lang::get('Site.nameMenu'). '*:',
        'valid' => array
        (
            'required'
        )
    ),
    array(
        'type'  => 'event',
        'event' => $this->moduleName . '.' . $action . 'MenuFormNameAfter'
    ),
    array
    (
        'type' => 'submit',
        'value' => Lang::get('Page.save')
    )

);