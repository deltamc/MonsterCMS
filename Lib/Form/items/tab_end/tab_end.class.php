<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class tab_end extends form_item
{

    public function __toString()
    {
        $html = '</fieldset>';
        return $html;
    }


}