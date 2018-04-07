<?php namespace Monstercms\Modules\Site;

use \Monstercms\Core\Events;

defined('MCMS_ACCESS') or die('No direct script access.');



/**
 * @var $smx SiteMap
 */
$smx = $this->model('SiteMap');


$smx->generate();

//Получаем ссылки от других модулей
$links = Events::cell(
    'Site.generateSiteMapXml',
    'array_merge'
);

if (!empty($links)){
    $smx->generateFromArray($links);
}

header('Content-type:application/xml; charset=utf-8');
echo $smx;
exit;