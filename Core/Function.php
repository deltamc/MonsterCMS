<?php
/*@TODO перенести в класс MCMS*/


/*

function admin_tools($ADMIN_BUTTONS)
{
    global $TPL;

    $out = '';

    $default = array
    (
        'type'        => 'button',
        'action'      => '#',
        'ico'         => '',
        'text'        => 'button',
        'align'       => 'left',
        'target'      => '_top',
        'window_size' => null
    );

    foreach ($ADMIN_BUTTONS as $item)
    {
        $item = array_merge ($default,$item);

        if($item['target'] == 'dialog') $item['action'] .= '&type=dialog';

        $tags = array
        (
            'ACTION'      =>  $item['action'],
            'ICO'         =>  $item['ico'],
            'TEXT'        =>  $item['text'],
            'ALIGN'       =>  $item['align'],
            'TARGET'      =>  $item['target'],
            'WINDOW_SIZE' =>  $item['window_size']

        );



        $out .= $TPL->get("admin_button/". $item['type'].'.php', $tags);
    }
    $tags = array("buttons" => $out);
    return $TPL->get("admin_tools.php", $tags);



}

function admin_tools_modules()
{
    $dir = MODULE_DIR;

    while ($module = readdir($dir))
    {
        $load = MODULE_DIR . DS . $module . DS . 'con';

        if(file_exists($load))
        {
            require_once($load);
        }
    }
}
*/

/*сокращенный вариант htmlspecialchars */
function hc($text)
{
    return htmlspecialchars($text);
}
