<?
use \Monstercms\Lib as Lib;

$url = $v_url;
$width = $conf['widgets']['video']['width'];
$height = $conf['widgets']['video']['height'];

if(Lib\vkVideo::isVkUrl($url)){
    print Lib\vkVideo::getPlayer($url, $width, $height);
}
elseif(Lib\youtubeVideo::isYoutubeUrl($url))
{
    print Lib\youtubeVideo::getPlayer($url, $width, $height);
}



?>
