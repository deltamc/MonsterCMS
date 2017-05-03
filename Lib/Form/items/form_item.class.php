<?php

use Monstercms\Lib as Lib;

class form_item
{
    public $item;

    public $javascript = array();
    public $js_load    = "";


    public $tpl = '<div {item_attr}><label for="{id}">{label}</label>
                    {before} {input} {after} {help}
                    <span class="error" id="error_{id}">{error}</span></div>';

    public $default_grup = null;

    protected $db;

    function __construct($item)
    {
        global $db;
        $this->db = $db;

        $this->item = array_merge($this->item_default(), $item);
        //if(!empty($this->item_special()))
        $this->item = array_merge($this->item_special(),   $this->item);

        if(empty($this->item['format'])) $this->item['format'] = array();
        $this->item['format'] = array_merge ($this->format_default(), $this->item['format']);

        if(empty($this->item['valid'])) $this->item['valid'] = array();
        $this->item['valid'] = array_merge ($this->valid_default(),   $this->item['valid']);


    }

    public function __toString()
    {
        $it = $this->item;
        $input  = '<input ';
        $input .= 'type="'.$it['type'].'" ';
        $input .= 'id="'.$it['id'].'" ';

        if(!empty($it['name']))       $input .= 'name="'.$it['name'].'" ';
        if(!empty($it['style']))      $input .= 'style="'.$it['style'].'" ';

        $input .= 'value="'.$this->getValue().'" ';

        if(!empty($this->item['attributes'])) $input .= $this->attributes($this->item['attributes']);
        if(!empty($it['max']))        $input .= 'max="'.$it['max'].'" ';
        if(!empty($it['min']))        $input .= 'min="'.$it['min'].'" ';
        if($it['autofocus'])          $input .= 'autofocus ';
        if(!$it['autocomplete'])      $input .= 'autocomplete = "off" ';


        $input  .= '/>';

        if(!empty($it['help'])){
            $it['help'] = htmlspecialchars($it['help']);
            $it['help'] = str_replace('"','&quot;', $it['help']);
            $it['help'] = str_replace("'",'&#039;', $it['help']);

            $this->item['help'] = '<i class="fa fa-question-circle tip" title="'.$it['help'].'"></i>';
        }


        $this->item['input'] = $input;

        return $this->template($it['tpl'], $this->item );
    }

    public function js_get()
    {
        return $this->javascript;
    }

    public function js_set($js_file)
    {
        if(!in_array($js_file, $this->javascript))
        {
            $this->javascript[] = $js_file;
        }
    }

    public function getValue()
    {

        return $this->item['value'];
    }

    public function setValue($value)
    {

        $this->item['value'] = $value;
    }


    /**
     * Настройки элемента формы по умолчанию
     */


    public function item_default()
    {
        return array
        (
            'value'        => '',
            'help'         => '',
            'item_attr'    => '',
            'attributes'   => array(),
            'name'         => null,
            'style'        => '',
            'class'        => '',
            'input'        => '',
            'error'        => '',
            'maxlength'    => '',
            'format'       => array(),
            'tpl'          => $this->tpl,
            'valid' => array(),
            'before' =>"",
            'after'  => "",

            /* HTML 5 */
            'autofocus'    => false,
            'autocomplete' => true,
            'placeholder'  => '',
            'min'  => '',
            'max'  => '',


        );
    }

    public function item_special()
    {
        return array();
    }
    public function format_default()
    {
        return array
        (
            'type' => 'string', /*string|int|float|timestamp*/
            'html' => false, //разрешить html теги
            'trim' => true   //удалить пробелы в начеле и в конце
        );
    }

    public function valid_default()
    {
        return array();
    }

    public function template($tpl, $tags = array() )
    {
        if(sizeof($tags) == 0) return $tpl;

        foreach($tags as $tag=>$val)
        {

            if(is_string($val)) $tpl = str_replace('{'.$tag.'}', $val, $tpl);
        }

        return $tpl;
    }

    public function attributes($attr = array())
    {
        $out = '';
        foreach($attr as $at=>$val) $out .= $at.'="'.$val.'" ';
        return trim($out);
    }

    public function valid($method = "post")
    {

        if(empty($this->item['valid'])) return true;
        if(empty($this->item['name']))  return true;


        $m = $_POST;
        if($method == 'get') $m = $_GET;
        $v = (!empty($m[$this->item['name']])) ? $m[$this->item['name']] : '';


        foreach($this->item['valid'] as $valid_type=>$param)
        {
            if($valid_type == 'file_type' || $valid_type == 'is_file')
                $v = $this->item['name'];
            //if(method_exists ('valid', $valid_type))
                if(!Lib\valid::$valid_type($v, $param[0])) return false;
        }
        return true;
    }

    public function valid_error($method = "post")
    {

        if(empty($this->item['valid'])) return "";
        if(empty($this->item['name']))  return "";


        $m = $_POST;
        if($method == 'get') $m = $_GET;
        $v = '';
        if(isset($m[$this->item['name']])) $v = $m[$this->item['name']];


        foreach($this->item['valid'] as $valid_type=>$param)
        {
            if(method_exists ('\Monstercms\Lib\valid', $valid_type))
            {
                if(!Lib\valid::$valid_type($v, $param[0])) return $param[1];
            }
        }

        return "";
    }
}