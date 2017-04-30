<?
//если скрипт подключен вне класса, блокируем выполнение
if(!isset($this)) exit();
//проверяем правельность формы
if(!empty($_POST['date']))

{


    $element_art = $this->save_element_art($widget, $parameters, $element_art_id);

    $this->update_element_art_in_page($element_art['id'], $widget, $element_art['cache']);

}


?>