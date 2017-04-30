<?php

class inline_end extends form_item
{

    public function __toString()
    {
        $it = $this->item;
        $html = '</tr></table></div>';
        return $html;
    }


}
?>