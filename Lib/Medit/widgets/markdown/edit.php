<?
//если скрипт подключен вне класса, блокируем выполнение
if(!isset($this)) exit();
//провер€ем правельность формы
if(empty($_POST['text']))
{
    $this->form_error("heading","Ќе заполнено поле");
}
else
{
    //$parameters['text'] = str_replace('"','&quot;', $parameters['text']);
    //$parameters['text'] = str_replace("'",'&#039;', $parameters['text']);

    if(self::validBase64($parameters['text'])) $parameters['text'] = base64_decode($parameters['text']);

    $element_art = $this->save_element_art($widget, $parameters, $element_art_id);

    $this->update_element_art_in_page($element_art['id'], $widget, $element_art['cache']);

}


?>