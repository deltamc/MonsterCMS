<?php
use Monstercms\Core\Module;

$index_page_index = Module::get('Site')->getIndexObjectId();

$this->actionView($index_page_index, array());
