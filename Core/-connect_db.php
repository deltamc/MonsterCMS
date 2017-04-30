<?
use \Monstercms\Lib as Lib;

$DB = new Lib\datebase(DB_NAME, DB_SERVER, DB_USER, DB_PASSWORD);
$DB->debugging = DEBUGGING;

$DB->query("SET NAMES " . DB_CHARSET . "");
$DB->query("set character_set_results=" . DB_CHARSET . ";");
$DB->query("set character_set_connection=" . DB_CHARSET . ";");
$DB->query("set character_set_client=" . DB_CHARSET . ";");
$DB->query("set character_set_database=" . DB_CHARSET . ";");

?>