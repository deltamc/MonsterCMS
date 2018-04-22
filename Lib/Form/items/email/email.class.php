<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class email extends form_item
{
    public function valid_default()
    {
        return array('email');
    }
}