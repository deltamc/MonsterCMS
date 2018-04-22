<?php
defined('MCMS_ACCESS') or die('No direct script access.');

return array(
    'version' => '1.0',
    'dbTableWidgets' => DB_PREFIX . 'module_widgets',
    'dbTableOptions' => DB_PREFIX . 'module_widgets_options',
    'widgetDir'      => 'Widgets',
    'maxSizeUpload'  => 5*1024*1024

);