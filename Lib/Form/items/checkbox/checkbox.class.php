<?php

class checkbox extends form_item
{
    public $tpl = '<div {item_attr}> <label for="{id}">{input} {label} {help}</label>

                    <span class="error" id="error_{id}">{error}</span></div>';

    public function item_special()
    {
        return array
        (

            'check_value'       => 1,

            'no_check_value'       => 0,

        );
    }

    public function format_default()
    {
        return array
        (
            'type' => 'checkbox'
        );
    }

    public function setValue($value)
    {
        $checked = array("checked"=>"true");
/*
        print $this->item['name'].' :';
        print $value;
        print '<br />';
*/

        if(!empty($value))
        {

            $this->item['attributes'] =
                array_merge($this->item['attributes'], $checked);

            $this->item['value'] = $this->item['check_value'];

        }
        else
        {
            $this->item['value'] = $this->item['no_check_value'];

        }


    }

    public function getValue()
    {
        $checked = array("checked"=>"true");


        $value = $this->item['check_value'];


        //$this->item['value'] = $this->item['check_value'];

        if(!empty($this->item['value']) &&
            (string) $this->item['value'] === (string) $this->item['check_value'])
        {

            $this->item['attributes'] =
                array_merge( $checked);



        }

        return $value;
    }


}