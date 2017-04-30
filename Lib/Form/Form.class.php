<? namespace  Monstercms\Lib;

use \Monstercms\Lib as Lib;

class Form
{
    /**
     * javascript код
     * @var string
     */
    private $javaScript = array();
    private $js_load = '';
    public  $js;

    /**
     * конфигурация формы
     * @var array
    */

    public $conf = array();

    public $items = array();

    private $wrap_types = array('fieldset', 'inline', 'tab');

    static $form_count;
    private $item_count = 0;
    private $item_add_load_js = array();




    function __construct($conf = "")
    {

        //require_once(dirName(__FILE__).'/valid.class.php');

        if(!is_array($conf)) $conf = array('action'=>$conf);


        $this->conf = array_merge( $this->conf_default(),  $conf);

        $this->conf['errors'] = array_merge(Lib\valid::$errors, $this->conf['errors']);

        self::$form_count ++;

        require_once($this->conf['items_path'].'/form_item.class.php');


    }

    /**
     * Добавление элементов формы
     * @param array $elements
     */


    public function add_items(array $items, $default_conf = null)
    {
        foreach($items as $it)
        {
            if(!is_null($default_conf)) $it = array_merge($default_conf, $it);

            $element_default = $this->item_default();
            $it = array_merge($element_default, $it);


            if(in_array($it['type'], $this->wrap_types))
            {

                $item = $this->include_item($it['type'].'_start', $it);
                $this->items[] = $item;
                $this->add_items($it['items'], $item->default_grup);
                $this->items[] = $this->include_item($it['type'].'_end', $it);
            }
            else
            {

                if(!$this->is_type($it['type'])) continue;

                $item = $this->include_item($it['type'], $it);

                $valid = $item->item['valid'];
                if(!empty($valid)) $item->item['valid'] = $this->format_valid($valid);
                $this->items[] = $item;



            }
        }




    }


    /**
     * Функция заполняет форму
     * @param array $value - занчения формы name=>value
     */

    public function full(array $values)
    {

        foreach ($this->items as $item)
        {
            if (isset($item->item['name']) && isset($values[$item->item['name']]))
            {
                $value = $values[$item->item['name']];


                /*
                if(is_string($value)  && isset($item->item['format']['html']) &&
                    !$item->item['format']['html']) $value = htmlspecialchars($value);
                */
                $value = str_replace('"','&quot;', $value);
                $value = str_replace("'",'&#039;', $value);

                //$item->item['value'] = $value;
                $item->setValue($value);
            }
        }
    }

    public function setFieldValue(array $value, $field)
    {
        foreach ($this->items as $item)
        {
            if(!empty($item->item['name']) && !empty($value[$item->item['name']]) )
                $item->item[$field] = $value[$item->item['name']];
        }


    }

    public function js()
    {
        $out = $this->get_js();

        if(!empty($this->js_load))
        $out .= "<script>$(function (){".$this->js_load."})</script>";
        return $out;
    }

    /**
     * Проверка была ли отправлена форма
     * @return bool
     */
    public function is_submit()
    {
        $c = $this->conf;
        $method = $_POST;
        if($c['method'] == 'get') $method = $_GET;

        //var_dump($c['id']);

        if(!empty($method[$c['id']]))   return true;
        return false;
    }

    /**
     * Проверка формы на валидность
     * @return bool
     */
    public function is_valid()
    {


        foreach($this->items as $it)
        {

            if(!$it->valid()) return false;
        }
        return true;
    }

    /**
     * Выводит форму с ошибками
     */
    public function error()
    {
        $html  = $this->start();
        $html .= $this->generate(true, true);
        $html .= $this->end();

        return $html;
    }

    /**
     * выводим форму
     * @return string
     */
    public function render()
    {

        $html  = $this->start();
        $html .= $this->generate();
        $html .= $this->end();

        return $html;
    }

    private $data = null;

    function data($name)
    {
        if(is_null($this->data))
        {
            foreach($this->items as $it)
            {

                if(empty($it->item['name'])) continue;


                $data[$it->item['name']] = $this->format($it);
            }
        }

        if(!isset($data[$name])) return null;

        return $data[$name];
    }

    /************* private *******************/

    private function format($it)
    {
        $name = $it->item['name'];
        $c = $this->conf;

        $method = $_POST;
        if($c['method'] == 'get') $method = $_GET;

        $value = (!empty($method[$name])) ? $method[$name] : "";


        $format = $it->item['format'];

        if(empty($format)) return $value;


        switch($format['type'])
        {
            case "int":
                $value = intval($value);
            break;

            case "float":
                $value = str_replace(',', '.', $value);
                $value = floatval($value);
            break;

            case "timestamp":
                $value =  strtotime($value);

            break;

            case "file":

                $value = $_FILES[$name]['name'];
            break;

            case "date":
                if($format['format'])
                {
                    $value =  strtotime($value);
                    $value = date($format['format'], $value);
                }
            break;
            case "checkbox":


                if(!empty($value)) $value = $it->item['check_value'];
                else $value = $it->item['no_check_value'];


                break;
            case "array":
                if(!empty($value) && !is_array($value)) $value = array($value);
            break;

            default:

                if(!isset($format['html']) || !$format['html'])     $value = htmlspecialchars($value);
                if(isset($format['trim']) && $format['trim'])       $value = trim($value);
                if(isset($format['upper']) && $format['upper'])     $value = strtoupper($value);
                if(isset($format['lower']) && $format['lower'])     $value = strtolower($value);
                if(isset($format['for_sql']) && $format['for_sql']) $value = mysql_real_escape_string($value);

            break;
        }
        //print $name.'('.$format['type'].')'.' - '. $value.'<br>';
        return $value;
    }

    private function conf_default()
    {
        return  array
        (
            'action'     => '',
            'items_path' => dirName(__FILE__).'/items',
            'method'     => 'post',
            'id'         => 'mform'.self::$form_count,
            'errors'     => array(),
            'attr'       => array('class'=>'form2'),
            'js_patch'   => $this->get_js_path(),

        );
    }

    public function generate($valid = false, $full = false)
    {
        $html = '';
        $js = '';
        $js_load = '';

        foreach($this->items as $it)
        {

            $this->add_js($it);
            $this->add_css($it);
            //$this->add_js($it->css);

            $type = get_class($it);

            if(!in_array($type, $this->item_add_load_js))
            {
                //$this->js_load .= $it->js_load;

                Lib\JavaScript::add($it->js_load);
                $this->item_add_load_js[] = $type;
            }

            //$js_load.= $it->js_load;

            if(!$this->is_type($it->item['type'])) continue;

            if($valid) $it->item['error'] = $it->valid_error($this->conf['method']);
            if(!empty($it->item['error'])) $it->item['item_attr'] .= 'class="error"';




            if($full)
            {

                if(!empty($it->item['name']))
                {
                    $value = '';


                    if(isset($_POST[$it->item['name']])) $value = $_POST[$it->item['name']];
                    if($this->conf['method'] == 'get') $value = $_GET[$it->item['name']];

                    $value = str_replace('"','&quot;', $value);
                    $value = str_replace("'",'&#039;', $value);


                    //$it->item['value'] = $value;
                    $it->setValue($value);
                }
            }


            $html .= $it;
        }

        return $html;
    }

    /**
     * метод проверяет существует ли элемент формы с заданным именем
     * @param $type - тип элемента
     * @return bool
     */
    private function is_type($type)
    {
        if(in_array($type, $this->wrap_types))
        {
            if(
                $this->is_type($type.'_start') &&
                $this->is_type($type.'_end')
            ) return true;

            return false;
        }

        $item_dir = $this->conf['items_path'].'/'.$type;

        if($type == ".")  return false;
        if($type == "..") return false;
        $class = $type.'.class.php';

        if(!is_file($item_dir.'/'.$class)) return false;
        return true;
    }

    private function include_item($type, $item_conf)
    {
        //if(!$this->is_type($type)) return null;

        $item_dir = $this->conf['items_path'].'/'.$type;

        $class = $type.'.class.php';

        require_once($item_dir.'/'.$class);

        return new $type($item_conf);
    }

    /**
     * Настройки элемента формы по умолчанию
     */

    private function item_default()
    {
        return array
        (
            'id'           => $this->item_id(),
            'type'         => 'text',
            'label'        => '',
            'help'         => '',
            'items_path'   => $this->conf['items_path'],
            'js_patch'   => $this->get_js_path()

        );
    }

    private function item_id()
    {
        /*
        $sec  = 0;
        $usec = 0;

        list($usec, $sec) = explode(" ", microtime());

        return "i".sprintf("%010u%04u", (float)$sec, round((float)$usec*100000));
        */
        $this->item_count++;

        return 'mf'.self::$form_count.'i'.$this->item_count;

    }


    /**
     * шаблонизатор
     * @param $tpl_file - файл шаблона
     * @param array $tags - массив (ключ = значение) переменных которые видны в шаблоне
     * @param null $templates_dir - путь к директории с шаблонами
     * @return string -
     */
    private function template_file($tpl_file, $tags = array(), $templates_dir = null)
    {

        if(is_array($tags) && count($tags) > 0) extract($tags);

        if($templates_dir == null)  $templates_dir = $this->conf['templates_dir'];

        $tpl_file = $templates_dir . '/' . $tpl_file;

        if(!is_file($tpl_file )) return "";

        $out = '';

        Ob_start ();
        Ob_implicit_flush (0);

        include ($tpl_file);
        $out = ob_get_contents ();
        Ob_end_clean ();

        return $out;
    }

    private function start()
    {
        $c = $this->conf;
        $html = "<form ";

        if(!empty($c['action'])) $html .= 'action="'.$c['action'].'" ';

        $html .= 'enctype="multipart/form-data" ';
        $html .= 'method="'.$c['method'].'" ';
        if(!empty($c['id'])) $html .= 'id="'.$c['id'].'" ';

        if(sizeof($c['attr']) > 0) $html .= $this->attributes($c['attr']).' ';

        $html .= ">\n";

        //Невидимый элемент формы нужен для того чтобы определить была ли форма отправлена.
        $html .= '<input ';
        $html .= 'type="hidden" ';
        $html .= 'name="'.$c['id'].'" ';
        $html .= 'value="true" ';
        $html .= " />";

        return  $html;
    }

    private function end()
    {
        return "</form>";
    }

    private function format_valid($valid)
    {
        $out = array();
        $value = null;
        $error = '';

        if(empty($valid)) return array();

        foreach($valid as $valid_type => $param)
        {

            /*если тип без параметров */
            //'required'
            if(is_int($valid_type))
            {
                $valid_type = $param;
                $param = true;
                $error = $this->conf['errors'][$valid_type];
            }
            /*если тип с параметрами (массив (параметр,текст ошибки))*/
            /*
            'max' => array(50, "max 50"),
             * */
            elseif(is_array($param))
            {
                $error = $param[1];

                $param = $param[0];
            }
            else
            {
                $error = $this->conf['errors'][$valid_type];
            }

            $out[$valid_type] = array($param, $error);
        }
        return $out;
    }


    private function add_js($it)
    {
        /* проверить! */
        //if(!empty($it->javascript)) \lib\JavaScript::add($it->javascript);


        if (!empty($it->javascript)) {
            foreach ($it->javascript as $jsfile) {

                $file = $this->conf['js_patch'] . '/' . get_class($it) . '/' . $jsfile;
                Lib\JavaScript::add($file);


                /*
                if(!in_array($file, $this->javaScript))
                {
                    $this->javaScript[] = $file;
                }
                */

            }
        }
    }

    private function add_css($it)
    {
        if(!empty($it->css))
        {
            foreach($it->css as $cssfile)
            {

                $file = $this->conf['js_patch'].'/'.get_class($it).'/'.$cssfile;
                Lib\Css::add($file);

            }
        }

        //$this->javaScript = array_merge($this->javaScript, $files_array);

    }
///form/example/example.php
//Z:\home\localhost\www\form\mform\items
    private function get_js_path()
    {
        $it_path     = dirName(__FILE__).'\items';
        $it_path     = str_replace("\\", "/", $it_path);
        $it_path_len = strlen($it_path);

        $root_path     = $_SERVER['DOCUMENT_ROOT'];
        $root_path     = str_replace("\\", "/",$root_path);
        $root_path_len = strlen($root_path);

        $js_path =  substr($it_path, $root_path_len, $it_path_len);

        return $js_path;
    }

    private function get_js()
    {
        $out = '';

        foreach($this->javaScript as $file)
        {
            if(preg_match("/.js$/", $file))
                $out .= '<script type="text/javascript" src="'.$file.'"></script>';

            if(preg_match("/.css$/", $file))
                $out .= '<link href="'.$file.'" rel="stylesheet" type="text/css">';
        }

        return $out;
    }

    private function attributes($attr = array())
    {
        $out = '';
        foreach($attr as $at=>$val) $out .= $at.'="'.$val.'" ';
        return trim($out);
    }
}


