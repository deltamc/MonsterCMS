<?php

class date extends form_item
{
    public $javascript = array
    (
        "picker.js",
        "picker.time.js",
        "picker.date.js",
        "legacy.js",
        "translations/ru_RU.js",


    );

    public $css = array
    (

        "themes/classic.date.css",
        "themes/classic.css"
    );
    public function item_special()
    {
        return array
        (
            'dete_format'  => null
        );
    }

    public $js_load = '$("input[type=date]").pickadate();';
/*
    public function getValue()
    {

        $it = $this->item;
        if(!is_null($it['dete_format']))
        {
            $unix = null;

            if (!$it['format']['type'] != "timestamp") $unix = strtotime($it['value']);
            else $unix = $it['value'];

            return date($it['dete_format'], $unix);
        }


        return $this->item['value'];
    }
*/
}
?>