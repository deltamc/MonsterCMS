<?php

class submit extends form_item
{
    public $tpl = '<p class="submit"><label for="{id}"></label> {input}</p>';
    public function item_special()
    {
        return array
        (
            'class'           => 'addbut'
        );
    }
}
?>