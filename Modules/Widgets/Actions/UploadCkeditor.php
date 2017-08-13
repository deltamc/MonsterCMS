<?php namespace Monstercms\Modules\Widgets;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * @var $this Controller
 * @var $widget \Monstercms\Core\WidgetInterface.php
 */

use \Monstercms\Core;
use \Monstercms\Lib;

//проверяем, есть ли права доступа
if (!Core\Users::isAdmin()) {
    throw new Core\HttpErrorException(403);
}
$params = $this->getParams();
$pageId = (int) $params['PageId'];

$image = '';
$error = '';
$uploadDir = UPLOAD_DIR . DS . $this->moduleName . DS . $pageId;
$imageFull ='';

$errors = array(
    Core\Lang::get('Widgets.uploadOk'),
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
        $imageFull = '/' . UPLOAD_DIR . '/' . $this->moduleName . '/' . $pageId . '/' . $upload->file;
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