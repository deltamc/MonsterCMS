<?php namespace  Monstercms\Lib;

use Monstercms\Core\MCMS;

class Crud
{
    protected  $db_table  = 'test';
    protected  $db_row_id = 'id';
    private $js;
    protected  $db;
    public $form_conf = array('action'=>'');



    function __construct($db_table, $db_row_id = 'id')
    {

        $this->db        = Mcms::DB();
        $this->db_table  = $db_table;
        $this->db_row_id = $db_row_id;
    }

    //функция отображает форму добавления данных в бд

    public function add($form_items)//, $location = '')
    {
        $form = new form($this->form_conf);
        $form->add_items($form_items);
        $html = '';

        //если форма не была заполнена, выводим ее
        if(!$form->is_submit())
        {
            //if(empty($location)) $location = $_SERVER['HTTP_REFERER'];

            //@session_start();

            //$_SESSION['coree']['locations'] =   $location;

            $html  = $form->render();
            $this->js = $form->js();
        }
        else if(!$form->is_valid())
        {
            $html = $form->error();
            $this->js = $form->js();
        }

        else
        {
            //если форма была заполнена без ошибок
            //1 создаем массив для записи в бд
            $fields =  $this->db->listFields($this->db_table);

            $list = '';


            foreach($form_items as $item)
            {
                if(!empty($item['items'])) $form_items = array_merge($form_items, $item['items']);
            }

            foreach($form_items as $item)
            {

                if(!empty($item['name']) && in_array($item['name'], $fields))
                {
                    $list[$item['name']] = $form->data($item['name']);
                }
            }


            //2 Записываем в бд
            $this->db->insert($list, $this->db_table);
            $this->eventAdd($this->db->lastInsertId(), $form);
/*
            if(!empty($_SESSION['coree']['locations']))
            {
                session_start();
                Header("Location: ".$_SESSION['coree']['locations']);
                exit();
            }
*/
        }



        return $html;
    }

    //удаление из бд
    public function delete($id, $form_items = array())//,  $location = '')
    {
        $id     = intval($id);

        $where  = '`'.$this->db_row_id.'` = '.$id;

        //$sql = 'DELETE FROM `'.$this->db_table.'` WHERE '.$where;
        //$this->db->query($sql, $this->db_table);
        $this->db->delete($this->db_table, $where);

        $this->eventDelete($id, $form_items);
/*
        if(empty($location)) $location = $_SERVER['HTTP_REFERER'];

        if(!empty($location)) {
            Header("Location: " . $location);
            exit();
        }
*/

    }

    //форма редактирования данных из бд
    public function edit($id, $form_items)//, $location = '')
    {
        $id     = intval($id);
        $where  = '`'.$this->db_row_id.'` = '.$id;

        $sql    = 'SELECT * FROM `'.$this->db_table.'` WHERE '.$where;


        $result = $this->db->query($sql);
        $row    = $result->fetch();

        
        $form_items_value =  $this->_fullFormItem($form_items, $row);

        $form = new form($this->form_conf);

        $form->add_items($form_items_value);

        $html = '';

        //если форма не была заполнена, выводим ее
        if(!$form->is_submit())
        {

            /*
            if(empty($location)) $location = $_SERVER['HTTP_REFERER'];
            @session_start();
            $_SESSION['coree']['locations'] =   $location;
            */

            $html  = $form->render();
        }
        else if(!$form->is_valid()) $html = $form->error();
        else
        {
            //если форма была заполнена без ошибок
            //1 создаем массив для записи в бд
            $fields =  $this->db->listFields($this->db_table);
           


            $list  =  $this->_listItemForBD($form, $form_items, $fields );
            //2 Записываем в бд



            $this->db->update($list, $this->db_table, $where);

            $this->eventEdit($id, $form);

            if(!empty($_SESSION['coree']['locations']))
            {
                @session_start();
                Header("Location: ".$_SESSION['coree']['locations']);
                exit();
            }
        }

        $this->js = $form->js();

        return $html;



    }






    private function _fullFormItem($form_items, $row )
    {


        foreach ($form_items as $key => $item)
        {
            $items = (isset($item['items'])) ? $item['items'] : '';
            $name  = (isset($item['name']))  ? $item['name']  : '';

            if (!empty($items) && is_array($items))
                $form_items[$key]['items'] = $this->_fullFormItem($items, $row);
            else
            {
                if (!empty($name) && !empty($row[$name]))
                {
                    $form_items[$key]['value'] =
                        self::getInputValue($form_items[$key], $row[$name]);
                }

            }
        }

        return $form_items;
    }

    private function _listItemForBD($form, $form_items, $fields )
    {

        //массив [riwDB]->value(POST)

        $out = array();

        foreach ($form_items as $key => $item)
        {
            $items = (isset($item['items'])) ? $item['items'] : array();


            if (!empty($items) && is_array($items))
                $out = array_merge($out, $this->_listItemForBD($form, $items, $fields));

            else
            {
                if(!empty($item['name']) && in_array($item['name'], $fields) && $item['type'] != "images_upload")
                {
                    $out[$item['name']] = $form->data($item['name']);
                }

            }
        }

        return $out;
    }

    private static function getInputValue(&$item, $value)
    {

        if(isset($item['dete_format']) && !is_null($item['dete_format']))
        {

            $unix = null;

            if (!self::isTypeFormat($item, 'timestamp')) $unix = strtotime($value);
            else $unix = $value;

            $value =  date($item['dete_format'], $unix);

        }




        return $value;
    }

    private static function isTypeFormat($item, $type)
    {
        return (isset($item['format']['type']) &&  $item['format']['type'] == $type);
    }

    public function js()
    {
        return $this->js;
    }

    public function eventAdd($id, $form)
    {

    }

    public function eventEdit($id, $form)
    {

    }

    public function eventDelete($id, $form)
    {

    }





}

?>