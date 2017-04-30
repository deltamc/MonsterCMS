<?
use \Monstercms\Lib as Lib;
use \Monstercms\Core as Core;


$MODULE_NAME = (!empty($_REQUEST['module'])) ? $_REQUEST['module'] : DEFAULT_MODULE;
$ACTION = (!empty($_REQUEST['action'])) ? $_REQUEST['action'] : DEFAULT_ACTION;
$OBJECT_ID = 0;
$URL_OPTION = array();

if(!empty($_GET['url']))
{
    $url = new Core\Url();
    $url->setUrl($_GET['url']);
    $URL_OPTION = $url->info();

    if(!is_null($URL_OPTION))
    {

        $MODULE_NAME    = $URL_OPTION['module'];
        $ACTION    = $URL_OPTION['action'];
        $OBJECT_ID = $URL_OPTION['object_id'];

        $URL_OPTION = array_merge($URL_OPTION, $_GET);

    }
    else
    {

        throw new Core\HttpErrorException(404);
    }

}
else
{
    $URL_OPTION = $_GET;
}

$ACTION = preg_replace('/[^\w-_0-9]/', '', $ACTION);
$MODULE_NAME = preg_replace('/[^\w-_0-9]/', '', $MODULE_NAME);

if($OBJECT_ID ==0 && isset($URL_OPTION['id']))  $OBJECT_ID = intval($URL_OPTION['id']);

$actionName = 'action' . ucfirst($ACTION);

$parameters = array(
    $OBJECT_ID,
    $URL_OPTION

);




if ($MODULE->isModule($MODULE_NAME))
{
    call_user_func_array(array($MODULE->$MODULE_NAME, hc($actionName)), $parameters);
}
else
{
    //Core\Events::cell('error_pages.404');

    throw new Core\HttpErrorException(404);
}



?>