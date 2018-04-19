<?php namespace Monstercms\Modules\Articles;

defined('MCMS_ACCESS') or die('No direct script access.');

use \Monstercms\Core\Module;
use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

$action = 'add';
$menuItemId = ($this->getParam("MenuItem") !== null) ? $this->getParam("MenuItem") : null;

//проверяем права
if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}
//устанавливаем  тему
Core\Mcms::setDialogTheme();

//Заголовок формы
$this->view->add('DIALOG_HEAD', Core\Lang::get('Articles.headingAdd'));

//Получаем данные формы
$formItems = include($this->modulePath . 'Forms' . DS . 'Articles.php');

//Получаем данные формы с других модулей
$formItems = Core\Events::eventsForm(
    $formItems,
    array(
        'menuItemId' => $menuItemId
    )
);

$form = new Lib\Form('');
$form->add_items($formItems);

//Если форма не была заполнена, выводим ее
if(!$form->is_submit())
{
    if($menuItemId !== null) {

        $form->full(array('menuItem'=> $menuItemId));
    }

    $this->view->add('BODY', $form->render());
}
else if($form->is_valid())
{
    $name = Lib\Request::getPost('name');
    $url = Lib\Request::getPost('url_semantic');

    $catalogId = Core\Module::get('Site')->getObjectIdByItemId(Lib\Request::getPost('menuItem'));


    Core\Events::cell(
        $this->moduleName . '.articleAddSave',
        'void'
    );

    //добавляем страницу
    $page = Module::get('Page')->add($name, $url, $this->moduleName);

    //сохраняем SEO данные
    Module::get('PageSemantic')->saveSeoForm('Page', $page['id'], time());



    //сохраняем данные статьи
    $idArt = $this->model->addArticle(
        Lib\Request::getPost('menuItem'),
        $page['id'],
        $name,
        Lib\Request::getPost('preview', true),
        $catalogId
    );

    Module::get('Page')->edit($page['id'], $url,  $name, $this->moduleName, $idArt);

    //добавляем виджеты на станицу

    Module::get('Widgets')->add(
        'Heading',
        array(
            'heading' => $name,
            'level'   => 1
        ),
        $page['id']
    );

    if (Lib\Request::getPost('preview') != '') {

        Module::get('Widgets')->add(
            'Text',
            array(
                'text' => Lib\Request::getPost('preview', true)
            ),
            $page['id']
        );
    }


    Core\Events::cell(
        $this->moduleName . '.articleAddSaveEnd',
        'void',
        array(
            'articleId' => $idArt,
            'pageId'    => $page['id'],

        )
    );

    //редирект
    if ((int) Lib\Request::getPost('article_goto') === 1) {

        Lib\Header::location('/' . $url . URL_SEMANTIC_END, 'top');
    } else {
        Lib\Header::reload('top');
    }


} else {
    $this->view->add('BODY', $form->error());
}




