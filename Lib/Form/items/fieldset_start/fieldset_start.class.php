<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class fieldset_start extends form_item
{

    public function __toString()
    {

        $it = $this->item;
        $html = '<fieldset ';

        if(!empty($it['class'])) $html .='class="'.$it['class'].'" ';
        if(!empty($it['style'])) $html .='style="'.$it['style'].'" ';
        if(!empty($it['id']))    $html .='id="'.$it['id'].'" ';

        $html .= '><legend>'.$it['label'].'</legend>';

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