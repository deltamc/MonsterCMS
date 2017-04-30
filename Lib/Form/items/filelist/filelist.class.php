<?php

class filelist extends form_item
{


    public function item_special()
    {
        return array
        (
            'list'       => array(),
            'row-max'    => null,
            'path'       => null
        );
    }
    public function format_default()
    {
        return array
        (
            'type' => 'array'
        );
    }

    public $js_load = '';

    public function __toString()
    {
        $it = $this->item;
        $input = '<div class="mform-filelist">';

        $input  .= "<table>";
        $input .= "<tr valign='top'>";
        $input .= "<td>";

        if(!$it['path'] || !is_dir($it['path'])) return "";


        $dir = opendir($it['path']);
        $s=0;

        while($file = readdir($dir))
        {
            if($file == "." || $file =="..") continue;

            $input .= '<div><input ';

            if( !empty($it['value']) && $it['path_link'].'/'.$file == $it['value'] )
                $input .= 'checked="checked" ';

            $input .= 'type="radio" ';
            $input .= 'name="'.$it['name'].'" ';
            $input .= 'value="'.$it['path_link'].'/'.$file.'" /> ';
            $input .= '<span><a href="'.$it['path_link'].'/'.$file.'" target="_blank">'.$file.'</a></span>';
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

        $input .= "</table></div>";
        $this->item['input'] = $input;
        return $this->template($it['tpl'], $this->item );
    }
}
?>