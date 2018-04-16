<?php namespace Monstercms\Modules\UploadImages;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Core\User;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!User::isAccess(User::ADMIN, User::CONTENT_MANAGER)) {
    throw new Core\HttpErrorException(403);
}
///UploadImages/UploadCkeditor/Module/dasdsa/Id/432

$params = $this->getParams();
$id     = (int) $params['Id'];
$module = $params['Module'];

if (!Core\Module::isModule($module)) {
    throw new \Exception('Module no found');
}
$module = preg_replace('/[^\w-_0-9]/', '', $module);

$image = '';
$error = '';
$uploadDir = UPLOAD_DIR . DS . $module;

if ($id !== 0) {
    $uploadDir .= DS . $id;
}

$imageFull ='';

$errors = array(
    '',
    Core\Lang::get('Widgets.errorNotImage'),
    Core\Lang::get('Widgets.errorMaxSize'),
    Core\Lang::get('Widgets.errorUpload'),
    Core\Lang::get('Widgets.errorNotImage'),
);

$callBack = 0;
if (isset($_REQUEST['CKEditorFuncNum'])){
    $callBack =  (int) $_REQUEST['CKEditorFuncNum'];
}

if(isset($_FILES['upload'])) {
    if(!is_dir(UPLOAD_DIR . DS . $this->moduleName)) {
        mkdir(UPLOAD_DIR . DS . $this->moduleName, 664);
    }
    if(!is_dir($uploadDir)) {
        mkdir($uploadDir, 664);
    }

    $upload = new Lib\Upload('upload', ROOT . DS . $uploadDir, md5(time() . rand(0, 1000)) , $this->config['maxSizeUpload']);

    if ($upload->error === 0) {
        $imageFull = '/' . Lib\Path::dsUrl($uploadDir) . '/' . $upload->file;

        $image =$upload->file;
    }
    $error = $errors[$upload->error];
}

if (isset($_REQUEST['responseType']) && $_REQUEST['responseType'] == 'json') {
    $out = array(
        "uploaded"=> 1,
        "fileName"=> $image,
        "url"=> $imageFull,
        "error"=> array(
            "message" => $error
        )
    );
    print json_encode($out);
    exit();
}


print "<script>
                window.parent.CKEDITOR.tools.callFunction({$callBack}, '{$imageFull}', '{$error}');
            </script>";

exit();