<?php
namespace  Monstercms\Lib;

class catalog
{

    public $tpl_node           = '<li><a href="/?cat={id}">{name}</a></li>';
    public $table_catalog      = 'catalog';
    public $table_catalog_cash = 'catalog_cash';
    public $table_catalog_link = 'catalog_products_link';
    public $catalog_id;
    public $event_function_add  = null;
    public $event_function_edit = null;
    public $event_function_del  = null;
    public $add_fields  = null;
    private $db;

	function __construct($table_catalog = '', $table_catalog_cash = '', $table_catalog_link = '',  $tpl_node  = '')
	{
        global  $fw_catalog_id, $DB, $_catalog_out_multi_select,
                $_catalog_out_admin, $_catalog_out_select2, $_catalog_out_bread_crumbs;



        $this->db = $DB;

        $fw_catalog_id ++;
        $this->catalog_id = $fw_catalog_id;
        $_catalog_out_multi_select[$this->catalog_id] = '';
        $_catalog_out_admin[$this->catalog_id] = '';
        $_catalog_out_select2[$this->catalog_id] = '';
        $_catalog_out_bread_crumbs[$this->catalog_id] = '';

        if(!empty($tpl_node))           $this->tpl_node = $tpl_node;
        if(!empty($table_catalog))      $this->table_catalog      = $table_catalog;
        if(!empty($table_catalog_cash)) $this->table_catalog_cash = $table_catalog_cash;
        if(!empty($table_catalog_link)) $this->table_catalog_link = $table_catalog_link;

        return $this;
    }

	function __toString()
	{
        return $this->getCash();
	}


    /*хлебные крошки*/
    function bread_crumbs($id_cat, $thisLink = false)
    {
        $id_cat = intval($id_cat);
        global $_catalog_out_bread_crumbs;




        $sql = "SELECT id_catalog, name, parent, urls.url  FROM article_catalog JOIN `urls` ON
              `urls`.`id` = `article_catalog`.`url_id`  WHERE id_catalog = '".$id_cat."' ORDER BY pos";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {


            $_catalog_out_bread_crumbs[$this->catalog_id] =
                   '<a href="/'.$row['url'].'.html">'.$row['name']."</a> / ".$_catalog_out_bread_crumbs[$this->catalog_id];

            //$_catalog_out_bread_crumbs[$this->catalog_id][$row['id_catalog']] .= $prefix.$row['name'];
            $this->bread_crumbs( $row['parent'], $thisLink );
        }


        return $_catalog_out_bread_crumbs[$this->catalog_id];
    }


    /* Достаем из кэша */
	function getCash()
	{
        $html = '';

        $sql = 'SELECT `cash` FROM `'.$this->table_catalog_cash.'`';

        $result =  $this->db->query($sql);

        $html = $this->db->fetchArray($result);

       return $html['cash'];
    }

	function edit()
	{
		if(isset($_GET['addcat']))       return $this->addcat($_GET['addcat'],   $this->add_fields, $this->event_function_add);
		else if(isset($_GET['editcat'])) return $this->editcat($_GET['editcat'], $this->add_fields, $this->event_function_edit);
		else if(isset($_GET['delcat'])){
            $this->delcat($_GET['delcat'],   $this->add_fields, $this->event_function_del);

            $this->setCash();

            $this_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            $this_url = preg_replace('/&delcat=\d+/','',$this_url);

            Header('Location: '.$this_url);
            exit();
        }
		else
		{
			//$html .= $this->get_catalog();
			$html = '';
			$html .= '<input type="radio" name="idcat" value="0" checked onclick="mindex=0"> <b>Корень</b><br>';

			$html .= $this->view_admin();

        	$html .= '<br /><br />';
			$html .= '<input type="button" value="Добавить" id="addcat"  />';
        	$html .= '<input type="button" value="Удалить"  id="delcat"  />';
        	$html .= '<input type="button" value="Правка"  id="editcat" />';
        	$html .= '';

        	\lib\JavaScript::add(self::PATH().'/catalog.js');
         }

		return $html;
    }

	function delcat($cat, $inputs = null,  $function = false)
	{
        if(!empty($function)) $function($cat);
        //
        $sql = 'DELETE FROM '.$this->table_catalog.' WHERE id_catalog = '.intval($cat);
        $result = $this->db->query($sql);

        /*
        $sql = 'DELETE FROM '.$this->table_catalog.' WHERE parent = '.intval($cat);
        $result = $this->db->query($sql);
        */

        //рекурсивно удаляем каталоги
        $sql = 'SELECT id_catalog FROM '.$this->table_catalog.' WHERE  parent = ?';
        $result = $this->db->query($sql, $cat);
        if($this->db->numRows($result) > 0)
        {
            while($row = $this->db->fetchArray($result))
            {
                $this->delcat($row['id_catalog'], $inputs,  $function);
            }
        }


	}

	function addcat($parent = 0, $inputs = null, $function = false)
	{
        $form = new \lib\form();


        $pos = $this->nextPos($parent);

		$form->add_items
		(
			$this->form(0,$parent,'',$pos)
		);

		if(!empty($inputs)) $form->add_items($inputs);

        $form->add_items($this->submit());

        if(!$form->is_submit()) $html = $form->render();
		elseif(!$form->is_valid()) 	$html = $form->error();
		else
		{

            $list = array
         	(
         		'id_catalog'  => 'NULL',
         		'name'  	  => $_POST['name'],
         		'parent' 	  => $_POST['parent'],
         		'pos'         => $_POST['pos']

         	);

         	$this->db->insert($list,$this->table_catalog);

            if(!empty($function)) $function($this->db->insertId());

            $this->setCash();

            $this_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
          	$this_url = preg_replace('/&addcat=\d+/','',$this_url);
            Header('Location: '.$this_url);
            exit();
		}

		return $html;
    }


	function editcat($cat = 0, $inputs = false, $function = false)
	{

        $sql = 'SELECT * FROM '.$this->table_catalog.' WHERE id_catalog = '.intval($cat);


        $result = $this->db->query($sql);
        $row    = $this->db->fetchArray($result);


     	$form = new \lib\form();

		$form->add_items
		(
			$this->form
			(

				$cat,
				$row['parent'],
     			$row['name'],
     			$row['pos']
     		)
		);

		if($inputs)
		{
   			$form->add_items($inputs);
		}



        $form->add_items($this->submit());

        if(!$form->is_submit())
		{
			$html = $form->render();
		}
		elseif(!$form->is_valid())
		{

			$html = $form->error();

		}
		else
		{
         	$list = array
         	(
         		'name'  	 => $_POST['name'],
         		'parent' 	 => $_POST['parent'],
         		'pos'        => $_POST['pos'],
         	);

         	$this->db->update($list,$this->table_catalog,'id_catalog = '.intval($cat));

            if(!empty($function))
            {
            	$function($cat);
            }

            $this->setCash();

            $this_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
          	$this_url = preg_replace('/&editcat=\d+/','',$this_url);
            Header('Location: '.$this_url);
            exit();
		}

		return $html;
	}

    function nextPos($parent_id = 0)
    {
     	$query = "SELECT id_catalog FROM ".$this->table_catalog." WHERE parent = '".intval($parent_id)."'";


  		$result = $this->db->query($query);
        $pos = $this->db->numRows($result);

     	return ($pos+1);
    }

	function form
    (
        $id_cat = 0,
        $parent=0,
        $name='',
        $pos=0
    )
	{
        global $_catalog_out_select2;

		$_catalog_out_select2[$this->catalog_id]['0'] = 'Корень';

		$form = array
		(



            array
            (
                'name'    => 'parent',
                'type'   => 'select',
                'label'   => 'Родительский каталог',
                'options' => $this->select_catalog2(0,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$id_cat),
                'value'   => $parent

            ),


            array
            (
                'name'    => 'name',
                'type'    => 'text',
                'label'   => 'Каталог',
                'value'   => $name,
                'valid' => array('required')
            ),

            array
            (
                'name'    => 'pos',
                'type'    => 'text',
                'label'   => 'Позиция',
                'attributes' => array('style'=>'width:20px;'),
                'value'   => $pos,
                'valid' => array('required')
            )

		);

		return $form;
    }

	function submit()
    {
    	return
            array(
            array
            (
                'type' => 'submit',
                'value' => ' Сохранить '
            ) );
    }


	function view_admin($parent_id = 0, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{
        global $_catalog_out_admin;

        $sql = "SELECT id_catalog, name, parent, pos  FROM ".$this->table_catalog." WHERE parent = '$parent_id' ORDER BY pos ";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {

        	$_catalog_out_admin[$this->catalog_id] .= $prefix."
        	<input type='radio' name='idcat' value='".$row['id_catalog']."' />
        	".$row['name']." <i>(поз: ".$row['pos'].")</i><br>";

			$this->view_admin( $row['id_catalog'], $prefix."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
        }

        return $_catalog_out_admin[$this->catalog_id];
	}

	function select_catalog($parent_id = 0, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
	{
        global $_catalog_out_select;

        $sql = "SELECT id_catalog, name, parent  FROM ".$this->table_catalog." WHERE parent = '$parent_id' ORDER BY pos";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {
        	$_catalog_out_select[$this->catalog_id][$row['id_catalog']] .= $prefix.$row['name'];
			$this->select_catalog( $row['id_catalog'], $prefix."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
        }

        return $_catalog_out_select[$this->catalog_id];

	}


	function multi_select_catalog($parent_id = 0, $prefix = "&nbsp;", $values= array())
	{
        global $_catalog_out_multi_select;



        $sql = "SELECT id_catalog, name, parent  FROM ".$this->table_catalog." WHERE parent = '".$parent_id."' ORDER BY pos";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {


            $id_cat = $row['id_catalog'];

            $checked = "";
            if(in_array($id_cat, $values)) $checked='checked="checked"';

        	$_catalog_out_multi_select[$this->catalog_id] .=
        	'<input '.$checked.' type="checkbox" id="c_'.$id_cat.'" name="catid[]" value="'.$id_cat.'"> '.$prefix.$row['name'].'</br>';

			$this->multi_select_catalog( $row['id_catalog'], $prefix."&nbsp;&nbsp;&nbsp;", $values);
        }

        return $_catalog_out_multi_select[$this->catalog_id];

	}

    function save_multi_catalog($id, $row_id = "id_product", $row_id_catalog = "id_cat" )
    {

        $this->db->query('DELETE FROM '.$this->table_catalog_link.' WHERE '.$row_id.' = '.intval($id));

        if(empty($_POST['catid'])) return;

        for($i=0,$s=sizeof($_POST['catid']);$i<$s;$i++)
        {
            $this->db->query('INSERT INTO '.$this->table_catalog_link.
          	' ('.$row_id_catalog.', '.$row_id.') VALUES('.INTVAL($_POST['catid'][$i]).','.intval($id).')');
     	}
    }

      function selectCatCheckbox($id)
      {
          $html = '';
          $sql = 'SELECT * FROM '.$this->table_catalog_link.' where id_product='.intval($id);

            $result = mysql_query($sql);

            while($row = mysql_fetch_array($result))
            {
               $html .= '#c_'.$row['id_cat'].', ';
            }
            $html = trim($html, ', ');
            return $html;
      }

      function selectCatCheckboxJavaScript($id)
      {

       	$html ='<script>$("'.$this->selectCatCheckbox(intval($id)).
       			'")[0].checked = true;</script>';

       	return $html;
      }

	function select_catalog2($parent_id = 0, $prefix = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$this_id = 0)
	{
        global $_catalog_out_select2;

        $sql = "SELECT id_catalog, name, parent  FROM ".$this->table_catalog." WHERE parent = '$parent_id' ORDER BY pos";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {
            $_catalog_out_select2[$this->catalog_id][$row['id_catalog']] = '';

            if($this_id == 0 || ($this_id != $row['id_catalog'] && $this_id != $row['parent']) )
            {
        		$_catalog_out_select2[$this->catalog_id][$row['id_catalog']] .= $prefix.$row['name'];
            }
				$this->select_catalog2( $row['id_catalog'], $prefix."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $this_id );

        }

        return $_catalog_out_select2[$this->catalog_id];

	}



	function get_catalog($parent_id = 0)
	{
        global $_catalog_out;

        $sql = "SELECT id_catalog, name, parent  FROM ".$this->table_catalog." WHERE parent = '$parent_id' ORDER BY pos";

        $result =  $this->db->query($sql);

        while ($row = $this->db->fetchArray($result))
        {

            $sql = "SELECT id_catalog  FROM ".$this->table_catalog." WHERE parent = '".$row['id_catalog']."'";

        	$result2    =  $this->db->query($sql);
        	$size_node =  $this->db->numRows($result2);


            $tpl ='';
            $tpl = str_replace('{id}',$row['id_catalog'],$this->tpl_node);
            $tpl = str_replace('{name}',$row['name'],$tpl);

        	$_catalog_out[$this->catalog_id] .= $tpl."\n";
             if($size_node > 0)      $_catalog_out[$this->catalog_id] .= "<ul>\n";
			if($size_node > 0)  $this->get_catalog( $row['id_catalog']);

			if($size_node > 0)      $_catalog_out[$this->catalog_id] .= "</ul>\n";
        }


        return $_catalog_out[$this->catalog_id];

	}

	function setCash()
	{
        $html = $this->get_catalog();

        $sql = 'SELECT id_cash FROM '.$this->table_catalog_cash;

		$result    =  $this->db->query($sql);

        if($this->db->numRows($result) > 0)
        {
            $this->db->update
         	(
         		array
         		(
         			'cash' => $html
         		),
         		$this->table_catalog_cash
         	);
        }
        else
        {

         	$this->db->insert
         	(
         		array
         		(
         			'cash' => $html,
         			'id_cash' => 'NULL'
         		),
         		$this->table_catalog_cash
         	);        }


	}

	function getNameByID($cat_id)
	{
		global $catalog;

		 $sql = 'SELECT name FROM '.$this->table_catalog.' WHERE id_catalog='.intval($cat_id);
		 $result = $this->db->query($sql);
		 $html =  $this->db->fetchArray($result);

		 return $html['name'];
	}

	function getCatByID($cat_id)
	{
		global $catalog;

		 $sql = 'SELECT * FROM '.$this->table_catalog.' WHERE id_catalog='.intval($cat_id);
		 $result = $this->db->query($sql);
		 return $this->db->fetchObject($result);


	}

	function getCatMultyByID($cat_id)
	{
		global $catalog;

		 $sql = 'SELECT * FROM '.$this->table_catalog.' WHERE id_catalog='.intval($cat_id);
		 $result = $this->db->query($sql);
		 return $this->db->fetchObject($result);


	}

	function forSQL($parent_id = 0)
	{
  		global  $_catalog_out_sql;

  		$query = "SELECT id_catalog FROM ".$this->table_catalog." WHERE parent = '".intval($parent_id)."'";
       // print $query;
  		$result = $this->db->query($query);

    	if($this->db->numRows($result)>0)
    	{
     		while($row=$this->db->fetchArray($result))
     		{
       			$_catalog_out_sql[$this->catalog_id] .= " OR ";
     			$_catalog_out_sql[$this->catalog_id] .= "id_catalog ='".$row['id_catalog']."'";
    			$this->forSQL($row['id_catalog']);
    		}

    		return $_catalog_out_sql[$this->catalog_id];
    	}
    }
    static function PATH()
    {
        $it_path     = dirName(__FILE__);
        $it_path     = str_replace("\\", "/",$it_path);
        $it_path_len = strlen($it_path);

        $root_path     = $_SERVER['DOCUMENT_ROOT'];
        $root_path     = str_replace("\\", "/",$root_path);
        $root_path_len = strlen($root_path);

        $js_path =  substr($it_path, $root_path_len, $it_path_len);

        return $js_path;
    }

}

?>