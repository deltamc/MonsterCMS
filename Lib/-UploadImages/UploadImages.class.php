<? namespace  Monstercms\Lib;

use \Monstercms\Lib as Lib;

class UploadImages
{
    private $conf;
    private $id_group;

    private $db;

    public static  $count_new_class = 0;

    private $mUploadImages_id;


    private function conf_default()
    {
        return array
        (
            'width'      => 600,      // ширена
            'height'     => 600,      // высота
            'dbRow'      => 'image',  // имя столбеца в таблице бд с именем изображения
            'dbTable'    => 'images', // таблица в бд
            'dbId_group' => 'group_id',     // имя столбеца в таблице бд с идифекатором группы изображений
            'dbId_image' => 'id',     // имя столбеца в таблице бд с идифекатором изображения
            'dbPos'      => 'pos',    // имя столбеца в таблице бд с позицией
            'serialize'  => false,    // true если информация об изображениях храница в сериализованном виде
            'where'      => null,    // условие выборки фоторграфий, (синтексис where select)
            'path'       => 'images',  // путь для загрузки изображений
            'class_path' => dirName(__FILE__),
            'js_path'    => self::get_js_path(),
            'upload_script' => '',
            'watermark_image' => null,
            'upload_event_start' => null, //функция вызывемая после загрузки и перед обработкой изображения
            'upload_event_end' => null, //функция вызывемая после загрузки и обработкой изображения
            'delete_event' => null, //функция вызывемая до удаления изображения
            'file_name'    => 'img',

        );
    }


    function __construct($conf = array(), $id_group = null)
    {
        global $DB;
        $this->db = $DB;

        $this->mUploadImages_id = self::getid();
        $this->id_group = $id_group;
        $this->conf = array_merge($this->conf_default(), $conf);
        $this->controller();
    }

    static function getid()
    {
        self::$count_new_class++;
        return 'mui'.self::$count_new_class;
    }

    private function controller()
    {

        $REQUEST = $_POST;
        if(!isset($REQUEST['class']) || $REQUEST['class'] != 'muploadimages')
            return ;

        if(isset($REQUEST['uli_upload']))
        {

            $upload = $this->upload();
            if(!empty($upload)) print $upload;
        }
        else if(isset($REQUEST['uli_del']))
        {


            $id = $REQUEST['uli_del'];
            $this->delete($id);
        }
        else if(isset($REQUEST['uli_pos']))
        {
            $pos = trim($REQUEST['uli_pos'], ",");
            $pos = explode(",", $pos);
            $this->save_pos($pos);


        }

        exit();
    }

    private function save_pos($pos)
    {
        if(!is_array($pos)) return ;
        $conf = $this->conf;
        $s = 0;
        foreach($pos as $id)
        {
            $id = intval($id);
            $sql = 'UPDATE '.$conf['dbTable'].' SET `'.$conf['dbPos'].'` = "'.$s.
                   '"  WHERE `'.$conf['dbId_image'].'` = '.$id;

            //print $sql;

            $this->db->query($sql);
            $s++;
        }
    }


    private function delete($id)
    {
        $conf = $this->conf;

        $image = $this->image_info($id);
        $image_path = $conf['path'].'/'.$image[$conf['dbRow']];

        if(!is_null($this->conf['delete_event']))
            call_user_func($this->conf['delete_event'], $image_path, $id, $this->id_group);

        $sql = 'DELETE FROM '.$this->conf['dbTable'].' WHERE `'.$this->conf['dbId_image'].'` ='.intval($id);

        $this->db->query($sql);

        unlink($image_path);

    }

    private function image_info($id)
    {
        $conf = $this->conf;
        $sql = "SELECT ".$conf['dbRow'].", ".$this->conf['dbId_image']." FROM ".$conf['dbTable'];
        $sql .= " WHERE ".$conf['dbId_image']."='".intval($id)."'";

        $result = $this->db->query($sql);
        return $this->db->fetchArray($result);

    }

    private function upload()
    {
        $json = array();

        $mk = microtime();
        $ms = explode(" ", $mk);
        $ms = explode(".", $ms[0]);
        $filr_name = $this->conf['file_name'].'.'.$ms[1];
        $upload = new Lib\upload
        (
            'Filedata',
            $this->conf['path']."/",
            $filr_name,
            array("jpg","jpeg","png","gif")
        );


        if($upload->error == 0 )
        {


            $image_id = $this->insert_image($upload->file);

            $image = $this->conf['path'].'/'.$upload->file;

            if(!is_null($this->conf['upload_event_start']))
                call_user_func($this->conf['upload_event_start'], $image, $image_id, $this->id_group);


            $img = new Image($this->conf['path']."/".$upload->file);

            if(isset($_POST['max_width']) && isset($_POST['max_height']))
            {
                $width = intval($_POST['max_width']);
                $height = intval($_POST['max_height']);
                $img->resize($width,$height,false,true);
            }

            if(!is_null($this->conf['watermark_image']))
            {
                $img->watermark3($this->conf['watermark_image']);
            }

            $img->save($this->conf['path']."/".$upload->file);

            $json = array
            (
                'id'     => $image_id,
                'image'  => $upload->file,
                'ui_id'  => $this->mUploadImages_id,
                'item'   => $this->images_item($image_id, $upload->file)
            );

            if(!is_null($this->conf['upload_event_end']))
                call_user_func($this->conf['upload_event_start'], $image, $image_id, $this->id_group);

            return json_encode($json);

        }
        else
        {

        }
    }

    public function html()
    {
        $this->head();

        $conf = $this->conf;
        $save = self::html_wrap("save...", 'save',
            array('style' => 'displasy:none;'));
        $top = self::upload_button();
        $top .= self::size_input($conf['width'], $conf['height'], 'imgsize-top-tools');
        $top .= $save;
        $top = self::html_wrap($top, "top-tools");
        $images_list = self::html_wrap ( $this->getImagesDB(), "images-list");


        $bottom_tools = self::html_wrap('', 'bottom-tools'  );

        $html = self::html_wrap($top.$images_list.$bottom_tools, 'mUploadImages',
            array('id'=>$this->mUploadImages_id));


        return $html;
    }

    static function html_wrap($node = "", $class = "mUploadImages", $attr = array(), $teg = "div")
    {
        $attr_string = self::attributes($attr);
        $html  = '<'.$teg;
        if(!empty($class)) $html .= ' class="'.$class.'"';
        $html .= ' '.$attr_string;
        $html .= '>';
        $html  .= $node;
        $html .= '</'.$teg.'>';

        return $html;
    }
    static function upload_button()
    {

        return '<div id="Buttons">
    <span id="UploadPhotos">
        <i id="fAddPhotos"></i><input type="button"
            id="AddPhotos" value="Загрузить фото" />
    </span>


</div><div id="Progress" ></div>';

        $attr = array
        (
            'id'    => 'Progress'
        );

        $button_process  = self::input('button', $attr);

        $attr = array
        (
            'id'    => 'AddPhotos',
            'value' => "Загрузить фото"
        );

        $button_add_photo  = self::input('button', $attr);

        $i = self::html_wrap("", "", array('id' => "fAddPhotos"), "i");

        $span = self::html_wrap($button_process.$i.$button_add_photo, "",
                          array('id' => "UploadPhotos"), "span");

        return self::html_wrap($span, "", array('id' => "Buttons"));

    }
    private static function input($type, $attr = array())
    {
        $attr_string = '';
        if(sizeof($attr) > 0) $attr_string = self::attributes($attr);

        return '<input type="'.$type.'" '.$attr_string.'/>';
    }

    private static function attributes($attr = array())
    {
        $out = '';
        foreach($attr as $at=>$val) $out .= $at.'="'.$val.'" ';
        return trim($out);
    }

    private static function size_input($width, $height, $id, $text = 'Мах. размер: ')
    {
        $width  = self::input('number', array('class' => $id.'_width', 'step'  => 10, 'value'=>$width) );
        $height = self::input('number', array('class' => $id.'_height', 'step' => 10, 'value'=>$height) );
        return self::html_wrap($text.$width."x".$height, "", array('class' => $id));
    }


    static function local()
    {
        return array
        (
            'upload_photo' => 'Загрузить фото'
        );
    }

    private function getImagesDB()
    {
        $conf = $this->conf;
        $sql = "SELECT ".$conf['dbRow'].", ".$this->conf['dbId_image']." FROM ".$conf['dbTable'];

        $where = '';

        if(is_null($conf['where']) && !is_null($this->id_group)) $where = " WHERE ".$conf['dbId_group'].
                                                                          "='".intval($this->id_group)."'";

        else if(!is_null($conf['where']))
        {
            if(!preg_match("/WHERE/", $sql)) $where = ' WHERE';
            $where .= ' '.$conf['where'];
        }

        $sql .= $where;

        if(!is_null($conf['dbPos']))
        {
            $sql .= ' ORDER BY `'.$conf['dbPos'].'`';
        }

        if(!empty($conf['Limit']) )
        {
            $sql .= ' LIMIT ';
            if(is_array($conf['Limit'])) $sql .= $conf['Limit'][0].', '.$conf['Limit'][1];
            else $sql .= $conf['Limit'];
        }

        $html = '';

        $result = $this->db->query($sql);

            $s=0;
            while($row = $this->db->fetchArray($result))
            {
                $html .= $this->images_item($row[$this->conf['dbId_image']], $row[0]);
                $s++;
            }


        return $html;
      }

      private function images_item($id,$img)
      {
          $html = "<div data-image-id='".$id."'>
          <img src='".$this->conf['path'].'/'.$img."' />".$this->imgTools()."</div>";

          return $html;
      }

    private function imgTools()
    {
        return '<a href="#" class="fa fa-times"></a>';
    }



    public function head()
    {
        Css::add($this->conf['js_path']."/css/style1.css");
        JavaScript::add($this->conf['js_path']."/js/jquery-1.3.2.min.js");
        JavaScript::add($this->conf['js_path']."/js/jquery-ui.min.js");
        JavaScript::add($this->conf['js_path']."/js/swfupload.js");
        JavaScript::add($this->conf['js_path']."/js/swfupload.cookies.js");
        //\lib\JavaScript::add($this->conf['js_path']."/js/jquery-migrate-1.2.1.js");


        JavaScript::add($this->conf['js_path']."/js/script.js");

        JavaScript::add("MFWPath = '".$this->conf['js_path']."'");
        JavaScript::add("$(function(){ BindSWFUpload('".$this->conf['upload_script']."',
        {'id':'".$this->mUploadImages_id."'});})");

        //return $out;
    }

    private function get_js_path()
    {
        $it_path     = dirName(__FILE__).'';
        $it_path     = str_replace("\\", "/",$it_path);
        $it_path_len = strlen($it_path);

        $root_path     = $_SERVER['DOCUMENT_ROOT'];
        $root_path     = str_replace("\\", "/",$root_path);
        $root_path_len = strlen($root_path);

        $js_path =  substr($it_path, $root_path_len, $it_path_len);

        return 'http://'.$_SERVER['SERVER_NAME'].$js_path;
    }

    private function get_js($file)
    {
        if(preg_match("/.js$/", $file))
          return '<script type="text/javascript" src="'.$file.'"></script>';

        if(preg_match("/.css$/", $file))
            return '<link href="'.$file.'" rel="stylesheet" type="text/css">';


        return "<script type=\"text/javascript\">".$file."</script>";


    }

    private function insert_image($image)
    {
        $conf = $this->conf;

        $pos = $this->get_post();

        $image = mysql_real_escape_string($image);

        $sql = 'INSERT INTO '.$conf['dbTable'].'(`'.$conf['dbId_image'].'`, `'.
            $conf['dbRow'].'`, `'.$conf['dbPos'].'`';

        if(!is_null($this->id_group)) $sql .= ', `'.$conf['dbId_group'].'`';

        $sql .= ') VALUE (NULL, "'.$image.'"';

        $sql .= ', "'.$pos.'"';

        if(!is_null($this->id_group)) $sql .= ', '.intval($this->id_group).'';


        $sql .= ')';


        $this->db->query($sql);

        return $this->db->insertId();
    }

    private function get_post()
    {
        $conf = $this->conf;
        $sql = 'SELECT MAX(`'.$conf['dbPos'].'`) FROM `'.$conf['dbTable'].'`';

        if(!is_null($this->id_group))
        {
            $sql .= ' WHERE `'.$conf['dbId_group'].'`='.intval($this->id_group).' LIMIT 1';
        }


        $result = mysql_query($sql);

        $pos = mysql_fetch_array($result);

        return $pos[0]++;
    }



}
?>