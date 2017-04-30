<?
/**
 * Class medit v1.0
 */
class \lib\medit
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

    function __construct($group_id, $conf = array())
    {
        global $DB;

        $this->db = $DB;

        $this->conf = array_merge($this->conf_default, $conf);
        $this->group_id = $group_id;
        $this->controller();


        $this->js('medit.js');
        $this->js('mcms.windows.jquery.js');
        $this->theme($this->conf['theme']);



        $this->html .= $this->tools();

        $this->html .= $this->art();
    }


    /**
     * контроллер. запускает методы в зависимости от get запросов
     * @return null
     */
    private function controller()
    {
        $request = $_GET;
        if(empty($request['medit'])) return null;

        $out = '';

        switch($request['medit'])
        {
            case "widget_form":
                if(!empty($request['widget'])  && isset($request['id']))
                    $out = $this->widget_form($request['widget'], intval($request['widget_id']));
                break;

            case "add_widget":
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

        \lib\JavaScript::add($this->conf['path_js'].'/'.$file);

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

        \lib\Css::add($this->conf['theme_dir'].'/'.$theme.'/style.css');
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

        foreach($widgets as $widget=>$conf)
        {

            $tags = array
            (
                'ico'         => $this->conf['widgets_ico_dir']."/".$widget."/".$conf['ico'],
                'name'        => $conf['name'],
                'widget'      => $widget,
                'window_size' => $conf['window-size']
            );

            $wid_buttons .= $this->template("widget_add_button.php", $tags);
        }

        $html  = $this->template("tools.php", array('widgets' => $wid_buttons));
        $html .= $this->template("widget-window.php");

        //$this->js .= $this->widgets_conf_js($widgets);

        return $html;
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
        include_once ($path.'/'.$widget.'/conf.php');
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
            $sql    = 'SELECT * FROM '.$this->conf['db_table_elements_art'].' WHERE id='.intval($id);
            $result = $this->db->query($sql);
            $row    = $this->db->fetchArray($result);
            $param  = unserialize($row['parameters']);
            //foreach($widget_conf['parameters'] as $k => $value) $param[$k] = '';

        }

        if(is_file($form_dir . '/' . 'form.php' ))
        {
            return  $this->template('form.php', $param, $form_dir);
        }
        else return null;
    }

    /**
     * функция добавляет виджет
     * @param $widget
     * @return null
     */
    private function add_widget($widget)
    {
        if(!$this->is_widget($widget)) return null;

        $widget_conf = $this->get_conf_widget($widget);

        foreach($widget_conf['parameters'] as $key => $value)
        {
            if(isset($_POST[$key])) $parameters[$key] = $_POST[$key];

        }

        $wdir = $this->conf['widgets_dir']. '/' . $widget;

        $element_art_id = 0;

        if(is_file($wdir . '/' . 'add.php' ))  include($wdir . '/' . 'add.php');

        else return null;

        if(intval($element_art_id) == 0) return null;

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

        if(is_file($wdir . '/' . 'edit.php' ))  include_once($wdir . '/' . 'edit.php');

        else return null;

        if(intval($element_art_id) == 0) return null;

    }

    /*

    private function get_element_art_json($item_id)
    {


        $item_id           = intval($item_id);

        $table             = $this->conf['db_table_elements_art'];

        $sql               = 'SELECT * FROM `'.$table.'` WHERE id ='.$item_id;

        $result            = mysql_query($sql);

        $element_art       = mysql_fetchArray($result);

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

        $pos       = $this->db->fetchArray($result);

        return intval($pos[0]);
    }

    /**
     * функция возвращает максимальное значение pos c учетом группы
     * @param $group_id
     * @return int
     */
    private  function  max_pos()
    {
        $table             = $this->conf['db_table_elements_art'];
        $sql               = 'SELECT max(`pos`) FROM `'.$table.'` WHERE group_id ='.$this->group_id;
        $result            = $this->db->query($sql);

        $posmax       = $this->db->fetchArray($result);

        return intval($posmax[0]);

    }

    /**
     * функция обнавляет/добавляет в бд виджет
     * @param $widget
     * @param $parameters
     * @param null $id
     * @return array
     */
    private function save_element_art($widget, $parameters, $id = null)
    {
        $action = "edit";
        if(!$id)
        {
            $id     = "NULL";
            $action = 'add';
            $pos        = $this->max_pos() + 1;
        }
        else
        {
            $pos = $this->get_pos($id);
        }



        $widget         = $this->db->escape_string($widget);
        $cache          = $this->element_art_view($widget, $parameters);
        $cache          = $this->db->escape_string($cache);
        $parameters_ser = serialize($parameters);
        $parameters_ser = $this->db->escape_string($parameters_ser);



        $out = array
        (
            'widget'     => $widget,
            'cache'      => $cache,
            'parameters' => $parameters,
            'pos'        => $pos
        );

        if($action == "add")
        {
            /*
            $sql = 'INSERT INTO '.$this->conf['db_table_elements_art'];
            $sql .= '(';
            $sql .=     '`id`,';
            $sql .=     '`widget`,';
            $sql .=     '`cache`,';
            $sql .=     '`parameters`,';
            $sql .=     '`pos`,';
            $sql .=     '`group_id`';
            $sql .=     ')';
            $sql .= ' VALUE';
            $sql .= '(';
            $sql .=     '' . $id . ',';
            $sql .=     '"' . $widget . '",';
            $sql .=     '"' . $cache . '",';
            $sql .=     '"' . $parameters_ser . '",';
            $sql .=     '' . $pos . ',';
            $sql .=     '' . $this->group_id . '';
            $sql .= ')';

            $this->db->query($sql);
            */

            $list = array
            (
                'id'         => $id,
                'widget'     => $widget,
                'cache'      => $cache,
                'parameters' => $parameters_ser,
                'pos'        => $pos,
                'group_id'   => $this->group_id
            );

            $this->db->insert($list, $this->conf['db_table_elements_art']);

            $id = $this->db->insertId();
        }
        elseif($action == "edit")
        {
            /*
            $sql = 'UPDATE '.$this->conf['db_table_elements_art'];
            $sql .= ' SET ';

            $sql .=     ' `widget` = "' . $widget.'",';
            $sql .=     ' `cache` = "' . $cache.'",';
            $sql .=     ' `parameters` = "' . $parameters_ser . '" ';
            $sql .= 'WHERE';
            $sql .=     ' id =' . $id;


            $this->db->query($sql);
            */


            $list = array
            (

                'widget'     => $widget,
                'cache'      => $cache,
                'parameters' => $parameters_ser
            );

            $this->db->update($list, $this->conf['db_table_elements_art'], ' id =' . $id);
        }
        $out['id'] = $id;

        return $out;

    }


    /**
     * функция возвращает html код виджета
     * @param $widget
     * @param $parameters
     * @return null|string
     */
    private function element_art_view($widget, $parameters)
    {
        if(!$this->is_widget($widget)) return null;
        $wdir = $this->conf['widgets_dir']. '/' . $widget;

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
    private function add_element_art_in_page($id, $widget, $cache, $pos)
    {
        print "<textarea id='cache' style='display: none;' >".$cache."</textarea>";
        print "<script>";
        print "var cache = document.getElementById('cache').value;";
        print "top.app_end_element_art(cache, '".$widget."', ".intval($id).", ".intval($pos).");";
        print "top.close_widget_window();";
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

        while($row = $this->db->fetchArray($result))
        {
            $html .= '<div class="medit-element"';
            $html .= ' date-id="'.$row['id'].'"';
            $html .= ' date-widget="'.$row['widget'].'"';
            $html .= ' date-pos="'.$row['pos'].'"';
            $html .= ' id="medit-element-'.$row['id'].'">';

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

        if(is_file($wdir . '/' . 'delete.php' ))  include_once($wdir . '/' . 'delete.php');

        $sql = 'DELETE FROM  ' . $this->conf['db_table_elements_art'] . ' WHERE id=' . intval($id);

        $this->db->query($sql);
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
        $this->db->query($sql);

        $sql = "UPDATE `" . $tab . "` SET `pos`='".$pos1."' WHERE `id`=" . $id2;
        $this->db->query($sql);

        print $sql;
    }

}
?>