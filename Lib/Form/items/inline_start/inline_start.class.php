<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class inline_start extends form_item
{


    public $default_grup = array('tpl'=>
                                 '
                                 <td  ><div {item_attr}> {label} {input}
                    <i class="help">{help}</i>
                    <span id="error_{id}">{error}</span></div></td>');

    public function __toString()
    {

        $it = $this->item;
        $html = '<div ';

        if(!empty($it['class'])) $html .='class="'.$it['class'].'" ';
        if(!empty($it['style'])) $html .='style="'.$it['style'].'" ';
        if(!empty($it['id']))    $html .='id="'.$it['id'].'" ';

        $html .= '><label >'.$it['label'].'</label>';
        $html .= '<table> <tr>';


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

/*
<p id="mf1i2" >
    <label for="mf1i1">Отослать сообщение через:</label>
    <table>
        <td valign="top">
            <label for="mf1i3"></label>
        </td>
        <td valign="top" >
            <p >
                <input type="text" id="mf1i3" name="timesend" />
                <i class="help"></i>
                <span id="error_mf1i3"></span></p>
        </td>
        <td valign="top">
            <label for="mf1i4"></label>
        </td>
        <td valign="top" >
            <p >
                <select id="mf1i4" name="type_timesend" ><option value="h" >Часов</option><option value="d" >Дней</option></select>
                <i class="help"></i>
                <span id="error_mf1i4"></span>
            </p>
        </td>
    </table>
</p>
*/

