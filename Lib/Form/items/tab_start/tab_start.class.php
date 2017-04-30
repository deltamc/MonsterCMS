<?php

class tab_start extends  form_item
{
    public $javascript = array
(
    "mcms.tabs.jquery.js"

);

    public $js_load = '$("form").mcms_tabs(
                {
                    tab_page:".tabpag",
                    tab_page_name:"legend"
                }
            );';


    public function __toString()
    {

        $it = $this->item;
        $html = '<fieldset ';
        if(empty($it['class']))  $it['class'] = '';

        $it['class'] .=  ' tabpag';
        $it['class'] = trim($it['class']);

        if(!empty($it['class'])) $html .='class="'.$it['class'].'" ';
        if(!empty($it['style'])) $html .='style="'.$it['style'].'" ';
        if(!empty($it['id']))    $html .='id="'.$it['id'].'" ';

        $html .= '><legend >'.$it['label'].'</legend>';

        return $html;
    }



    /**
     * Настройки элемента формы по умолчанию
     */

    public function item_default()
    {
        return array
        (
            'attributes'   => array(),
            'style'        => '',
            'class'        => ''
        );
    }
}



?>