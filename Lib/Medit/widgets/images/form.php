<?
use \Monstercms\Lib as Lib;

if(!isset($this)) exit();


$conf = array('upload_script' => Lib\path::this_url()."&class=muploadimages&uli_upload");
$widget_conf =
    array_merge($conf, $widget_conf);




$img_uploader = new Lib\mUploadImages($widget_conf, $id);
$html = $img_uploader->html();


$form_items = array
(


);

?>