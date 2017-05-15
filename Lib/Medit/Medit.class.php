<?php namespace  Monstercms\Lib;


use Monstercms\Core\MCMS;

class Medit
{
    /**
     * настройки по умолчанию
     * @var array
     */

    private $conf_default = array
    (
        'position'              => 'left',
        'db_table_elements_art' => 'elements_art',
        'path_js'               => '/lib/medit/javascript',
        'widgets_dir'           => 'lib/medit/widgets',
        'widgets_ico_dir'       => '/lib/medit/widgets',
        'theme'                 => 'classic',
        'theme_dir'             => '/lib/medit/themes',
        'templates_dir'         => 'lib/medit/templates'
    );

    /**
     * ид группы (страницы)
     * @var int
     */
    private $group_id;

    /**
     * javascipt
     * @var string
     */
    public $js = '';

    /**
     * настройки
     * @var array
     */
    public $conf = array();

    public $html = '';

    private $db;

    private $edit;

    function __construct($group_id, $conf = array(), $edit=false)
    {


        $this->db = Mcms::DB();

        $this->edit = $edit;

        $this->conf = array_merge($this->conf_default, $conf);


        $this->group_id = $group_id;
        $this->controller();

        if($this->edit)
        {
            $this->js('medit.js');
            $this->js('scroll.js');
            $this->js('mcms.windows.jquery.js');
            $this->theme($this->conf['theme']);
            $this->html .= $this->tools();
        }

        $this->html .= $this->art();
    }


    /**
     * контроллер. запускает методы в зависимости от get запросов
     * @return null
     */
    private function controller()
    {


        //if(!$this->edit) return null;


        $request = $_GET;
        if(empty($request['medit'])) return null;


        $out = '';

        switch($request['medit'])
        {
            case "widget_form":
                if(!empty($request['widget'])  && isset($request['widget_id']))
                    $out = $this->widget_form($request['widget'], intval($request['widget_id']));

                break;

            case "add_no_form_widget":

                if(!empty($request['widget'])) $out = $this->add_widget($request['widget']);
                break;

            case "edit_widget":
                if(!empty($request['widget']) && intval($request['widget_id']))
                    $out = $this->edit_widget($request['widget'], intval($request['widget_id']));
                break;

            case "get_element_art_json":
                if(intval($request['widget_id']) != 0)     $out = $this->get_element_art_json($request['widget_id']);
                break;

            case "delete_widget":
                if(!empty($request['widget']) && intval($request['widget_id']))
                    $out = $this->delete_widget($request['widget'], intval($request['widget_id']));
                break;

            case "pos_widget":

                if
                (
                    intval($request['id1'])     &&
                    intval($request['id2'])     &&
                    intval($request['pos1'])    &&
                    intval($request['pos2'])
                )

                    $this->exchange_pos_widget($request['id1'], $request['pos1'],
                        $request['id2'], $request['pos2']);
                break;
        }

        print $out;
        exit();
    }

    /**
     * функция подключает js файлы
     * @param $file
     */
    private function js($file)
    {

        JavaScript::add($this->conf['path_js'].'/'.$file);

        /*
        $tags = array
        (
            'path' => $this->conf['path_js'],
            'file'    => $file
        );
        $this->js .= $this->template("javascript.php", $tags);
        */

    }

    /**
     * функция подключает тему
     * @param $theme
     */
    private function theme($theme)
    {
        $tags = array
        (
            'path'    => $this->conf['theme_dir'].'/'.$theme,
            'file'    => 'style.css'
        );

        //$this->js .= $this->template("css.php", $tags);

       Css::add($this->conf['theme_dir'].'/'.$theme.'/style.css');
    }

    /**
     * функция создает toolbar
     * @return string
     */

    public function tools()
    {
        $widgets = $this->widgets();


        $wid_buttons = '';

        $wdir = $this->conf['widgets_dir'];



        $widgets_sort =  self::array_msort($widgets, array('pos'=>SORT_ASC));




        foreach($widgets_sort as $widget=>$conf)
        {


            $tags = array
            (
                'ico'         => $this->conf['widgets_ico_dir']."/".$widget."/".$conf['ico'],
                'name'        => $conf['name'],
                'widget'      => $widget,
                'window_size' => $conf['window-size'],
                'window_add'  => $conf['window_add'],
            );

            $wid_buttons .= $this->template("widget_add_button.php", $tags);
        }

        $html  = $this->template("tools.php", array('widgets' => $wid_buttons));
        $html .= $this->template("widget-window.php");

        //$this->js .= $this->widgets_conf_js($widgets);

        return $html;
    }

    static function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }

    /**
     * функция возвращает массив с настройками виджетов
     *
     * @return array
     */

    public function widgets()
    {
        $widgets = array();

        $path = $this->conf['widgets_dir'];
        $dir  = opendir($path);

        while ($widget = readdir($dir))
        {
            if ($this->is_widget($widget) )
            {
                $widgets[$widget] = $this->get_conf_widget($widget);
            }
        }

        closedir($dir);

        return $widgets;
    }

    /**
     * функция проверяет существует ли виджет с заданным именем
     * @param $widget - имя виджета
     * @return bool
     */
    private function is_widget($widget)
    {
        $widget_dir = $this->conf['widgets_dir'].'/'.$widget;

        if($widget == ".")  return false;
        if($widget == "..") return false;
        if(!is_file($widget_dir.'/conf.php')) return false;
        return true;

    }

    /**
     * функция возвращает массив с настройками виджета
     * @param $widget - имя виджета
     * @return array
     */
    private function get_conf_widget($widget)
    {
        $conf = array();
        $path = $this->conf['widgets_dir'];

        include ($path.'/'.$widget.'/conf.php');

        if(!empty($this->conf['widgets'][$widget]))
        {
            $conf = array_merge($conf,$this->conf['widgets'][$widget]);
        }

        return $conf;
    }

    /**
     * шаблонизатор
     * @param $tpl_file - файл шаблона
     * @param array $tags - массив (ключ = значение) переменных которые видны в шаблоне
     * @param null $templates_dir - путь к директории с шаблонами
     * @return string -
     */
    private function template($tpl_file, $tags = array(), $templates_dir = null)
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


    /**
     * функция отображает форму редактирования/добавления виджета
     * @param $widget
     * @param null $id
     * @return null|string
     */
    private function widget_form($widget, $id = null)
    {

        $conf = $this->conf;
        if(!$this->is_widget($widget)) return null;

        $wdir = $this->conf['widgets_dir'];

        $form_dir = $wdir . '/' . $widget;

        $widget_conf = $this->get_conf_widget($widget);

        $param = array();

        if(!$id)
        {

            foreach($widget_conf['parameters'] as $k => $value) $param[$k] = $value['value'];
        }
        else
        {

            $sql    = 'SELECT * FROM '.$this->conf['db_table_elements_art'].' WHERE id=' . intval($id);

            $result = $this->db->query($sql);

            $row    = $result->fetchAll();

            $param  = unserialize($row['parameters']);


            //foreach($widget_conf['parameters'] as $k => $value) $param[$k] = '';

        }
        //$this->js('jquery-1.3.2.min.js');
        $this->js('jquery.min.js');
        Css::add($conf['theme_dir'].'/'.$conf['theme']."/form.css");
        $html  = '';
        $form_items = array();
        $form_file = $form_dir . '/' . 'form.php';

        if(is_file($form_file )) include_once($form_file);

        if(empty($form_items)) $form_items = array();

        $form = new Form();

        $form->add_items($form_items);

        /* Заполняем форму данными */
        if(is_array($param)) $form->full($param);

/*
        $form_item_full = array();
        $s = 0;

        foreach ($form_items as $form_item)
        {
            $form_item_full[$s] = $form_item;

            if(isset($param[$form_item['name']])) $form_item_full[$s]['value'] = $param[$form_item['name']];
            $s++;
        }
*/

        //выводим форму



        if(!$form->is_submit()) $html  .= $form->render();
        else
        {
            if($form->is_valid())
            {


                $request = $_GET;

                switch($_GET['widget_action'])
                {
                    case "add":
                        if(!empty($request['widget']))
                            $this->add_widget($request['widget'], $_POST);
                    break;

                    case "edit":
                        if(!empty($request['widget']) && intval($request['widget_id']))
                            $this->edit_widget($request['widget'], intval($request['widget_id']));
                    break;
                }

            }
            else
            {
                $html .= $form->error();
            }
        }


        $vars = array
        (
            'body' => $html,
            'head' => Css::get() ,
            'js' =>JavaScript::get()

        );

       return $this->template("html.php", $vars);


    }




    /**
     * функция добавляет виджет
     * @param $widget
     * @return null
     */
    public function add_widget($widget, $value = array(), $add_in_page = true)
    {
        //if(empty($value)) return null;
        if(!$this->is_widget($widget)) return null;

        $widget_conf = $this->get_conf_widget($widget);

        $conf = $this->conf;

        foreach($widget_conf['parameters'] as $key => $v)
        {
            if(isset($value[$key])) $parameters[$key] = $value[$key];

        }

        $wdir = $this->conf['widgets_dir']. '/' . $widget;


        $element_art = array();
        if(is_file($wdir . '/' . 'add.php' ))  include($wdir . '/' . 'add.php');


        //if(intval($element_art['id']) == 0) return null;

        $callback = $this->conf['callback']['add'];
        if(!empty($callback) && function_exists($callback))
        {
            $callback($element_art, $widget_conf, $this->group_id);
        }

        return $element_art['id'];


    }

    /**
     * функция редактирует виджет
     * @param $widget
     * @param $element_art_id
     * @return null
     */
    private function edit_widget($widget, $element_art_id)
    {
        if(!$this->is_widget($widget)) return null;

        $widget_conf = $this->get_conf_widget($widget);

        foreach($widget_conf['parameters'] as $key => $value)
        {
            if(isset($_POST[$key])) $parameters[$key] = $_POST[$key];

        }

        $wdir = $this->conf['widgets_dir']. '/' . $widget;
        $element_art = array();
        $conf = $this->conf;
        if(is_file($wdir . '/' . 'edit.php' ))  include_once($wdir . '/' . 'edit.php');

        $callback = $this->conf['callback']['edit'];
        if(!empty($callback) && function_exists($callback))
        {
            $callback($element_art, $widget_conf, $this->group_id);
        }





    }

    /*

    private function get_element_art_json($item_id)
    {


        $item_id           = intval($item_id);

        $table             = $this->conf['db_table_elements_art'];

        $sql               = 'SELECT * FROM `'.$table.'` WHERE id ='.$item_id;

        $result            = mysql_query($sql);

        $element_art       = mysql_fetch_array($result);

        $out               = array();

        $out['widget']     = $element_art['widget'];

        $out['cache']      = $element_art['cache'];

        $out['parameters'] = unserialize($element_art['parameters']);

        $out['pos']        = $element_art['pos'];

        $out['group_id']   = $element_art['group_id'];

        return json_encode($out);

    }
*/
    /**
     * функция возвращает номер позиции вижита
     * @param $id - ид виджета на странице
     * @return int
     */
    private function get_pos($id)
    {
        $table             = $this->conf['db_table_elements_art'];
        $sql               = 'SELECT `pos` FROM `'.$table.'` WHERE id ='.intval($id);
        $result            = $this->db->query($sql);

        $pos       = $result->fetch($result);

        return intval($pos[0]);
    }

    /**
     * функция возвращает максимальное значение pos c учетом группы
     * @param $group_id
     * @return int
     */
    public  function  max_pos()
    {
        $table             = $this->conf['db_table_elements_art'];
        $sql               = 'SELECT max(`pos`) FROM `'.$table.'` WHERE group_id ='.$this->group_id;
        $result            = $this->db->query($sql);

        $posmax       = $result->fetch($result);

        return intval($posmax[0]);

    }

    /**
     * функция обнавляет/добавляет в бд виджет
     * @param $widget
     * @param $parameters
     * @param null $id
     * @return array
     */
    public function save_element_art($widget, $parameters, $id = null)
    {


        if(!$id)
        {
            $id     = "NULL";

            $pos        = $this->max_pos() + 1;


                $list = array
                (
                    'id'         => $id,
                    'widget'     => $widget,
                    'pos'        => $pos,
                    'group_id'   => $this->group_id
                );

                $this->db->insert($list, $this->conf['db_table_elements_art']);


                $id = $this->db->lastInsertId();

        }
        else
        {
            $pos = $this->get_pos($id);
        }




        $cache          = $this->element_art_view($widget, $parameters, $id);


        $parameters_ser = serialize($parameters);




        $out = array
        (
            'widget'     => $widget,
            'cache'      => $cache,
            'parameters' => $parameters,
            'pos'        => $pos,

        );



            $list = array
            (

                'widget'     => $widget,
                'cache'      => $cache,
                'parameters' => $parameters_ser
            );

            $this->db->update($list, $this->conf['db_table_elements_art'], $id);

        $out['id'] = $id;

        return $out;

    }


    /**
     * функция возвращает html код виджета
     * @param $widget
     * @param $parameters
     * @return null|string
     */
    private function element_art_view($widget, $parameters, $id)
    {

        if(!$this->is_widget($widget)) return null;
        $wdir = $this->conf['widgets_dir']. '/' . $widget;


        $parameters['conf'] = $this->conf;
        $parameters['id'] = $id;
        $parameters['db'] = $this->db;

        if(is_file($wdir . '/' . 'view.php' ))
            return  $this->template('view.php', $parameters, $wdir);

        return null;
    }

    /**
     * функция отображает в родительском окне ошибку при заполнении формы
     * @param string $input_name
     * @param string $error
     */
    private function form_error($input_name='', $error = '')
    {
        print "<script>";
        print "top.widget_form_error('".$input_name."', '".$error."')";
        print "</script>";
    }

    /**
     * функция отображает виджет в родительском окне
     *
     * @param $id
     * @param $widget
     * @param $cache
     * @param $pos
     */
    private function add_element_art_in_page($id, $widget, $cache, $pos, $close_window = true)
    {
        print "<div id='cache' style='display: none;' >".$cache."</div>";
        print "<script>";
        print "var cache = document.getElementById('cache').innerHTML;";
        print "top.app_end_element_art(cache, '".$widget."', ".intval($id).", ".intval($pos).");";
        if($close_window) print "top.close_widget_window();";
        print "</script>";
    }

    /**
     * функция обновляет виджет в родительском окне
     * @param $id
     * @param $widget
     * @param $cache
     */
    private function update_element_art_in_page($id, $widget, $cache)
    {
        print "<textarea id='cache' style='display: none;' >".$cache."</textarea>";
        print "<script>";
        print "var cache = document.getElementById('cache').value;";
        print "top.update_element_art(".intval($id).", cache);";
        print "top.close_widget_window();";
        print "</script>";
    }

    /**
     * функция возвращает html код страницы
     * @param bool $edit
     * @return string11
     */
    public function art($edit = false)
    {
        $table             = $this->conf['db_table_elements_art'];
        $sql               = 'SELECT * FROM `'.$table.'` WHERE group_id ='.intval($this->group_id).
                             ' ORDER BY `pos`';
        $result            = $this->db->query($sql);

        $html = '<div id="medit-art">';
        $rows = $result->fetchAll();
        foreach ($rows as $row)
        {
            $html .= '<div class="medit-element '.$row['widget'].'"';
            $html .= ' date-id="'.$row['id'].'"';
            $html .= ' date-widget="'.$row['widget'].'"';
            $html .= ' date-pos="'.$row['pos'].'"';

            $html .= ' id="medit-element-'.$row['id'].'" >';

            $html .= $row['cache'];
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }
/*
    private function widgets_conf_js($widgets)
    {
        if(!is_array($widgets)) return ;

        $widgets_size = '';
        foreach($widgets as $widget => $conf)
        {
            $widgets_size = "'".$widget."': '".$conf['window-size']."',";
        }

        $widgets_size = trim($widgets_size, ",");

        $js = '';

        $js .= 'var medit_widgets_window_size = {'.$widgets_size.'}';

        return "<script>".$js."</script>";

    }
*/
    /**
     * функция удалет виджет с бд.
     * @param $widget
     * @param $id
     * @return null
     */
    private function delete_widget( $widget, $id )
    {


        if(!$this->is_widget($widget)) return null;

        $widget_conf = $this->get_conf_widget($widget);

        $wdir = $this->conf['widgets_dir']. '/' . $widget;
        $element_art = array();
        if(is_file($wdir . '/' . 'delete.php' ))  include_once($wdir . '/' . 'delete.php');

        $callback = $this->conf['callback']['delete'];
        if(!empty($callback) && function_exists($callback))
        {
            $callback($element_art, $widget_conf, $this->group_id);
        }

        //$sql = 'DELETE FROM  ' . $this->conf['db_table_elements_art'] . ' WHERE id=' . intval($id);

        $this->db->delete($this->conf['db_table_elements_art'], intval($id));
    }

    public function deleteWidgetsByGroupId($group_id)
    {
        $group_id = (int) $group_id;

        $sql    = 'SELECT * FROM '.$this->conf['db_table_elements_art'].' WHERE group_id='.$group_id;
        $result = $this->db->query($sql);

        while($row = $result->fetchAll())
        {
            $this->delete_widget( $row['widget'], $row['id'] );
        }
    }

    /**
     * функция меняет местами я pos1 и pos2
     * @param $id1
     * @param $pos1
     * @param $id2
     * @param $pos2
     */

    private function exchange_pos_widget($id1, $pos1, $id2, $pos2)
    {
        $id1  = intval($id1);
        $pos1 = intval($pos1);

        $id2  = intval($id2);
        $pos2 = intval($pos2);

        $tab =  $this->conf['db_table_elements_art'];

        $sql = "UPDATE `" . $tab . "` SET `pos`='".$pos2."' WHERE `id`=" . $id1;
        $this->db->exec($sql);

        $sql = "UPDATE `" . $tab . "` SET `pos`='".$pos1."' WHERE `id`=" . $id2;
        $this->db->exec($sql);


    }

    public function maxId()
    {
        $tab =  $this->conf['db_table_elements_art'];
        $sql = 'SELECT MAX(`id`) FROM `'.$tab.'`';
        $result = $this->db->query($sql);
        $id = $result->fetch();

        return $id[0];
    }


    public static function validBase64($string){
        $decoded = base64_decode($string, true);
        // Check if there is no invalid character in strin
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

        // Decode the string in strict mode and send the responce
        if(!base64_decode($string, true)) return false;

        // Encode and compare it to origional one
        if(base64_encode($decoded) != $string) return false;

        return true;
    }

}
?>