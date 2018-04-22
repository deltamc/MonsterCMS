<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class html extends form_item
{
    public $tpl = '<div {item_attr} id="{id}">{html}</div>';

    public function item_special()
    {
        return array('html'=>'');
    }
}