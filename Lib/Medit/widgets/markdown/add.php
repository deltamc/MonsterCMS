<?
//если скрипт подключен вне класса, блокируем выполнение
if(!isset($this)) exit();


//$parameters['text'] = str_replace('"','&quot;', $parameters['text']);
//$parameters['text'] = str_replace("'",'&#039;', $parameters['text']);

//$parameters['text'] = base64_encode($parameters['text']);

    $element_art = $this->save_element_art($widget, $parameters);
$element_id = $element_art['id'];
if($add_in_page) $this->add_element_art_in_page($element_art['id'], $widget, $element_art['cache'] , $element_art['pos']);




?>