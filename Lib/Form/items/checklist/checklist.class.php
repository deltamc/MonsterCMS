<?php
defined('MCMS_ACCESS') or die('No direct script access.');

class checklist extends form_item
{


    public function item_special()
    {
        return array
        (
            'list'       => array(),
            'row-max'    => null
        );
    }
    public function format_default()
    {
        return array
        (
            'type' => 'array'
        );
    }

    public $js_load = '
        $(".mform-checkbox span").click(function()
        {
            $input = $(this).parent().find(\'input[type="checkbox"]\');
            $input.prop("checked", function (i, value) {
                return !value;
             });
        });
        ';

    public function __toString()
    {
        $it = $this->item;


        $input  = "<table>";
        $input .= "<tr valign='top'>";
        $input .= "<td>";



        foreach($it['list'] as $value => $label)
        {
            $input .= '<div class="mform-checkbox"><input ';

            if(!empty($it['value']) && in_array($value, $it['value']))
                $input .= 'checked="checked" ';

            $input .= 'type="checkbox" ';
            $input .= 'name="'.$it['name'].'[]" ';
            $input .= 'value="'.$value.'" /> ';
            $input .= '<span>'.$label.'</span>';
            $input .= '</div>';

            $s++;

            if(!is_null($it['row-max']) && $s%$it['row-max'] == 0)
            {

                $input .= '</td><td>';
            }
        }

        $input = preg_replace("/<td>$/", "", $input);
        $input = preg_replace("/<\/td>$/", "", $input);

        // $input = trim($input, '<br />');
        //$input = trim($input, '<td>');
        //$input = trim($input, '</td>');

        $input .= "</td>";
        $input .= "</tr>";

        $input .= "</table>";
        $this->item['input'] = $input;
        return $this->template($it['tpl'], $this->item );
    }
}