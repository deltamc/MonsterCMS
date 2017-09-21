<?php namespace Monstercms\Modules\Site;

defined('MCMS_ACCESS') or die('No direct script access.');

header('Content-type:application/xml; charset=utf-8');

/**
 * @var $smx SiteMap
 */
$smx = $this->model('SiteMap');
$smx->generate();

echo $smx;
exit;