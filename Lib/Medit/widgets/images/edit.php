<?
//если скрипт подключен вне класса, блокируем выполнение
if(!isset($this)) exit();

$element_art = $this->save_element_art($widget, $parameters, $element_art_id);

$this->update_element_art_in_page($element_art['id'], $widget, $element_art['cache']);



?>