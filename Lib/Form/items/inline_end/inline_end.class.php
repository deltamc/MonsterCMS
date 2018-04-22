<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class inline_end extends form_item
{

    public function __toString()
    {
        $it = $this->item;
        $html = '</tr></table></div>';
        return $html;
    }


}