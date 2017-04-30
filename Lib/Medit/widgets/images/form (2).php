<?
if(!isset($this)) exit();

if($_GET['widget_action'] == "add" && (!isset($_POST['class']) || $_POST['class'] != 'muploadimages'))
{
    $element_art = $this->save_element_art($widget, array());

    /*$this->add_element_art_in_page($element_art['id'], $widget,
        $element_art['cache'] , $element_art['pos'], false);
    */
    $id = $element_art['id'];
    $url = \lib\path::replace(array("widget_action"=>"edit", "widget_id"=>$id));
    $url .= "&widget_save";

    header("Location: ".$url);
    exit();
}
/*
elseif(isset($_GET['widget_save']))
{
    $element_art = $this->save_element_art($widget, array(), $_GET['widget_id']);

    $this->add_element_art_in_page($element_art['id'], $widget,
        $element_art['cache'] , $element_art['pos'], false);

}
*/

$conf = array('upload_script' => \lib\path::this_url());
$widget_conf =
    array_merge($conf, $widget_conf);


$img_uploader = new \lib\mUploadImages($widget_conf, $id);
$html = $img_uploader->html();


$form_items = array
(


);

?>