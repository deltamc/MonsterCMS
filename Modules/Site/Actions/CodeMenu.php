<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Core\ControllerAbstract
 */

use \Monstercms\Core;
use \Monstercms\Lib;
use \Monstercms\Core\Lang;


if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}

if ($this->getObjectId() === 0) {
    throw new Core\HttpErrorException(404);
}


$id = $this->getObjectId();


$form = new Lib\form();

$items_form = array(
    array
    (
        'name' => "",
        'type' => 'textarea',
        'label' => Lang::get('site.codeMenu'),
        'value' => "<?=Monstercms\Core\Module::get('Site')->menu(" . $id . ");?>"

    ),
);

$form->add_items($items_form);

$html  = $form->render();

$menu = $this->model('Menu')->menuInfo($id);

Lib\View::setBasicTemplate(THEMES_DIALOG_PATH);


$vars = array('form'=>$html, 'menu_name' => $menu->name);

$this->view->inc('BODY', 'CodeMenu.php', $vars);