<?php namespace Monstercms\Modules\Articles;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Module;
use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}

Core\Mcms::setDialogTheme();
//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Articles.headingEdit'));

$id = $this->getObjectId();
$articleInfo = $this->model->articleInfo($id);
$urlId = $articleInfo->url_id;
$action = 'edit';
//Получаем данные формы
$formItems = include($this->modulePath . 'Forms' . DS . 'Articles.php');

//если есть параметр GoToArt, убираем галочку "Перейти на страницу"
if($this->getParam('GoTo') === 'Art') {
    $formItems = Core\Mcms::hiddenItemsForm($formItems, array('article_goto'));
}
//
$form = new Lib\Form('');
$form->add_items($formItems);



if(!$form->is_submit())
{
    $full = array(
        'menuItem' => $articleInfo->menu_item_id,
        'name'     => $articleInfo->name,
        'url_semantic' => $articleInfo->url,
        'preview' => $articleInfo->preview,
    );

    $form->full($full);
    $form->full(Module::get('PageSemantic')->fullSeoForm('Page', $articleInfo->page_id));
    $this->view->add('BODY', $form->render());

} elseif ($form->is_valid()) {

    $url = Lib\Request::getPost('url_semantic');
    $this->model->editArticle(
        $id,
        Lib\Request::getPost('menuItem'),
        Lib\Request::getPost('name'),
        Lib\Request::getPost('preview', true)
    );
    Module::get('Page')->edit(
        $articleInfo->page_id,
        $url,
        Lib\Request::getPost('name'),
        $this->moduleName,
        $id

    );

    //сохраняем SEO данные
    Module::get('PageSemantic')->saveSeoForm('Page', $articleInfo->page_id, time());


    if ((int) Lib\Request::getPost('article_goto') === 1 || $this->getParam('GoTo') === 'Art') {

        Lib\Header::location('/' . $url . URL_SEMANTIC_END, 'top');
    } else {
        Lib\Header::reload('top');
    }


} else {
    $this->view->add('BODY', $form->error());
}


