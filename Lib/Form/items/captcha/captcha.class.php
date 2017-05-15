<?php

class captcha extends form_item
{
    public $tpl = '<div {item_attr}><label for="{id}">{label} {captcha}</label>
                    <i class="help">{help}</i>{before} {input} {after}
                    <span class="error" id="error_{id}">{error}</span></div>';
    function __construct($item)
    {
        @session_start();
        parent::__construct($item);
    }
    public function item_special()
    {

        return array
        (
            'captcha'        => "<img src='".$this->item['js_patch']."/captcha/img.php'>"
        );
    }

    public function format_default()
    {
        return array
        (
            'type' => 'int'
        );
    }
}