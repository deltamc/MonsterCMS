<?

use \Monstercms\Lib as Lib;

//если скрипт подключен вне класса, блокируем выполнение
if(!isset($this)) exit();
$types = str_replace(" ", "", $conf['widgets']['docfiles']['types']);
$types = explode(",", $types);

$upload = new Lib\upload('fileload', 'files', false, $types);

if($upload->error == 0)
    $parameters['file'] = $conf['widgets']['docfiles']['path_link'].'/'.$upload->file;

//print "<script>alert('".\lib\upload::transliterate($_FILES['fileload']['name'])."')</script>";
if(empty($parameters['file'])) {
    //print "<script>alert('".$upload->error."')</script>";
    //exit();
}

$element_art = $this->save_element_art($widget, $parameters);
$element_id = $element_art['id'];
if($add_in_page) $this->add_element_art_in_page($element_art['id'], $widget, $element_art['cache'] , $element_art['pos']);




?>