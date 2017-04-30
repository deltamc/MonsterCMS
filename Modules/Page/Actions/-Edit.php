<?php

use \Monstercms\Modules\page as This;
use \Monstercms\Core as Core;
use \Monstercms\Lib as Lib;
use \Monstercms\Core\MCMS;

if(!is_admin())       throw new Core\HttpErrorException(403);
if(!isset($id)) throw new Core\HttpErrorException(404);
$id = intval($id);
/*
$page = new This\Page($this->config);
$page_info = $page->info($id);


$form_items = include($this->modulePath . 'forms' . DS . 'edit.php');

$this->tag->BODY .= $page->edit($id, $form_items);
*/

$form_items_base = include($this->modulePath . 'forms' . DS . 'Base.php');



$form = new Lib\Form('');

//@TODO 3 парамтер
$form_items_base = Mcms::eventsForm($form_items_base, $this->moduleName, '', $id);
$form->add_items($form_items_base);

$form_items_seo = Mcms::eventsForm($form_items_seo, $this->moduleName, '', $id);
$form->add_items($form_items_seo);


if (!$form->is_submit())
{
    $this->tag->BODY .= $form->render();
}
elseif ($form->is_valid())
{
    $url = preg_replace('/[^a-z0-9-_]/','',$_POST['menu_item_url_sematic']);

    $url = strtolower($url);
    $this->model->urlUpdate($id, $url);

}
else
{
    $this->tag->BODY .= $form->error();
}
