<?php
$dir  = opendir(MODULE_DIR);

while ($moduleName = readdir($dir)){
    //$space_name =  $module . '\\';
    if($moduleName == '.' || $moduleName == '..' ||  !is_dir(MODULE_DIR . DS .$moduleName )) continue;

    //исключаем модули которые начинаются с '-'
    if(preg_match('/^-/', $moduleName)) continue;
    $init = MODULE_DIR . DS .$moduleName.DS.'actions'.DS.'Init.php';

    if(file_exists($init)){

        include_once($init);
    }

    //$MODULE->

/*
    $controllerName = '\\Monstercms\\Modules\\'.$module . '\\Controller';

    $CONTROLLERS[$module] = new $controllerName($module);

    if( method_exists($controllerName, 'main'))
    {

        call_user_func(array($CONTROLLERS[$module], 'main'));
    }
*/
}