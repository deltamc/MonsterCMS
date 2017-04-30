<?
/**
 * @param $class_name
 * Aвтоматическое подключение классов библиотеки
 * классы библиотеки вызываются с префиксом mfw_
 */
/*
function __autoload($class_name)
{
    $prefix = 'lib';


    $parts = explode("_", $class_name);



    if(!isset($parts[0]) || !isset($parts[1]) || $parts[0] != $prefix) return;

    $file = \lib\DIR.'/'.$parts[1].'/'.$parts[1].'.class.php';



    if(file_exists($file))
        include_once($file);
    else throw new Exception('Файл "'.strip_tags($file).'" библиотеки не найден');

}
*/
?>