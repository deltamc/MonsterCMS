<?php namespace  Monstercms\Lib;

use Monstercms\Core\Mcms;

class Crud
{
    protected  $dbTable  = 'test';
    protected  $dbColId = 'id';
    protected  $js;
    protected  $db;
    public $formConf = array('action'=>'');



    function __construct($dbTable, $dbColId = 'id')
    {

        $this->db        = Mcms::DB();
        $this->dbTable  = $dbTable;
        $this->dbColId = $dbColId;
    }


    /** Метод генерирует форму и добавляет данные из формы в бд
     * @param $form_items - данные формы
     * @param null $location - на какую страницу перенаправить после сохранения данных
     * @param null $full - массив (ключ->значение) с данными
     * для заполнения полей формы (перекрывают данные из БД)
     * @return string - html код формы
     */
    public function add($form_items, $location = null, $full = null)
    {

        $form = new Form($this->formConf);
        $form->add_items($form_items);

        //Если был передан параметр $full
        if (!empty($full) && is_array($full)) {
            $form->full($full);
        }

        $html = '';

        //если форма не была заполнена, выводим ее
        if(!$form->is_submit()) {
            $html     = $form->render();
            $this->js = $form->js();

        } else if(!$form->is_valid()) {

            $html     = $form->error();
            $this->js = $form->js();

        } else {
            //если форма была заполнена без ошибок
            //1 создаем массив для записи в бд
            $fields =  $this->db->listFields($this->dbTable);
            $list = '';

            foreach ($form_items as $item) {
                if (!empty($item['items'])) {
                    $form_items = array_merge($form_items, $item['items']);
                }
            }

            foreach ($form_items as $item) {
                if (!empty($item['name']) && in_array($item['name'], $fields)) {
                    $list[$item['name']] = $form->data($item['name']);
                }
            }

            //2 Записываем в бд
            $this->db->insert($list, $this->dbTable);
            $this->eventAdd($this->db->lastInsertId(), $form);

            //редирект
            if (!empty($location)) {
                Header("Location: ".$location);
                exit();
            }
        }

        return $html;
    }

    /**
     * Метод удаляет запись из бд
     * @param $id - первичный ключ
     * @param array $form_items - данные формы
     * @throws \Exception
     */
    public function delete($id, $form_items = array(),  $location = null)
    {
        $id     = intval($id);
        $where  = '`'.$this->dbColId.'` = '.$id;

        //$sql = 'DELETE FROM `'.$this->db_table.'` WHERE '.$where;
        //$this->db->query($sql, $this->db_table);
        $this->db->delete($this->dbTable, $where);

        $this->eventDelete($id, $form_items);

        //редирект
        if (!empty($location)) {
            Header("Location: ".$location);
            exit();
        }

    }


    /**
     * Метод генерирует форму и пересохраняет данные в БД
     *
     * @param $id - первичный ключ записи
     * @param $form_items - данные формы
     * @param null $location - на какую страницу перенаправить после сохранения данных
     * @param null $full - массив (ключ->значение) с данными
     * для заполнения полей формы (перекрывают данные из БД)
     * @return string - html код формы
     * @throws \Exception
     */
    public function edit($id, $form_items, $location = null, $full = null)
    {
        $id     = intval($id);
        $html   = '';

        //Достаем данные из БД
        $where  = '`'.$this->dbColId.'` = '.$id;
        $sql    = 'SELECT * FROM `'.$this->dbTable.'` WHERE '.$where;

        $result = $this->db->query($sql);
        $row    = $result->fetch(\PDO::FETCH_ASSOC);


        $form = new Form($this->formConf);
        $form->add_items($form_items);

        //Если был передан параметр $full
        if (!empty($full) && is_array($full)) {
            foreach ($full as $name => $value) {
                $row[$name] = $value;
            }
        }

        $form->full($row);


        //если форма не была заполнена, выводим ее
        if (!$form->is_submit()) {
            $html  = $form->render();

        } else if(!$form->is_valid()) {
            $html = $form->error();
        } else {
            //если форма была заполнена без ошибок
            //1 создаем массив для записи в бд
            $fields =  $this->db->listFields($this->dbTable);
            $list   =  $this->_listItemForBD($form, $form_items, $fields );

            //2 Записываем в бд
            $this->db->update($list, $this->dbTable, $where);

            $this->eventEdit($id, $form);

            //редирект
            if (!empty($location)) {
                Header("Location: ".$location);
                exit();
            }
        }

        $this->js = $form->js();

        return $html;
    }

    /**
     * Метод рекурсивно проходит по данным формы и формирует массив для записи в БД
     * @param $form - экземпляр формы
     * @param $formItems - данные формы
     * @param $fields - список столбцов в таблице БД
     * @return array - массив (ключ->значение) для записи в бд
     */
    private function _listItemForBD($form, $formItems, $fields )
    {

        $out = array();
        foreach ($formItems as  $key => $item) {

            $items = array();
            if (isset($item['items'])){
                $items = $item['items'];
            }

            if (!empty($items) && is_array($items)) {
                $out = array_merge($out, $this->_listItemForBD($form, $items, $fields));
            } else {
                if (!empty($item['name'])
                    && in_array($item['name'], $fields)
                    && $item['type'] != "images_upload") {

                    $out[$item['name']] = $form->data($item['name']);
                }
            }
        }

        return $out;
    }

    /**
     * Метод возвращает скрипты Javascript
     * @return mixed
     */
    public function js()
    {
        return $this->js;
    }

    /**
     * Метод вызывается после добавления данных в бд
     * @param $id - первичный ключ
     * @param $form - данные формы
     */
    protected function eventAdd($id, $form) { }

    /**
     * Метод вызывается после перезаписи данных в БД
     * @param $id - первичный ключ
     * @param $form - данные формы
     */
    protected function eventEdit($id, $form) { }

    /**
     * Метод вызывается после удаления записи из БД
     * @param $id - первичный ключ
     * @param $form - данные формы
     */
    protected function eventDelete($id, $form) { }
}