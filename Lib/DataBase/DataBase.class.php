<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');


class DataBase extends \PDO
{
    private $regWords = Array(
        "NULL",
        "NOW()",
        "UNIX_TIMESTAMP()"
    );

    private $idColumn = 'id';

    private $lastSql = '';

    /**
     * Метод устанавливает название столбца с первичным ключом
     * @param $idColumn
     */
    public function setIdColumn($idColumn)
    {
        $this->idColumn = $idColumn;
    }

    /**
     * Метод обновляет данные в бд
     * @param $list - массив (столбец=>значение)
     * @param $table - имя таблицы в бд
     * @param null|string|int $where - условие, если передали целое число,
     * то обновляем данные со значением $this->idColumn = $where
     * @return \PDOStatement - результат обновления
     * @throws \Exception
     */
    function update($list, $table, $where = null)
    {


        $values = "";
        $s      = 0;
        foreach ($list as $key => $value) {
            if (!$this->isRegWord($value)) {
                $values .= "`{$key}` = ".$this->quote($value);
            } else {
                $values .= "`{$key}` = {$value}";
            }

            $s++;
            $values .= ", ";
        }

        $values = trim($values, ", ");

        $sql = "UPDATE `{$table}` SET {$values}";

        $where =  $this->where($where);
        $sql .= $where;

        if(strpos($where, '=') === false) {
            throw new \Exception("Empty where -".$where);
        }

        $this->lastSql = $sql;

        return $this->exec($sql);
    }

    /**
     * Метод вставляет новые записи в таблицу
     * @param $list - массив (столбец => значение)
     * @param $table - таблица
     * @return \PDOStatement - результат
     */
    function insert($list, $table)
    {

        $cols   = '';
        $values = '';
        $s      = 0;

        foreach($list as $key => $value) {
            $cols .= "`{$key}`, ";

            if(!$this->isRegWord($value)) {
                $values .= $this->quote($value). ', ';
            } else {
                $values .= $value . ', ';
            }
        }

        $cols   = trim($cols,   ', ');
        $values = trim($values, ', ');


        $sql = "INSERT INTO `{$table}` ({$cols}) VALUES({$values})";

        $this->lastSql =  $sql;

        return $this->exec($sql);

        /*
        $stmt = $this->prepare($sql);
        $s = 1;


        foreach ($list as &$value) {
            if($this->isRegWord($value)) {
               continue;
            }
            $stmt->bindParam($s, $value);
            $s++;
        }

        unset ($value);

        return $stmt->execute();
        */
    }

    /**
     * Метод удаляет данные из таблицы
     * @param $table - таблица
     * @param null|string|int $where - условие, если передали целое число,
     * то удаляем данные со значением $this->idColumn = $where
     * @return \PDOStatement - результат
     * @throws \Exception
     */
    public function delete($table, $where = null)
    {
        $sql = "DELETE FROM {$table}";
        $where =  $this->where($where);
        $sql .= $where;

        if(strpos($where, '=') === false) {
            throw new \Exception("Empty $where");
        }

        $this->lastSql = $sql;

        return $this->exec($sql);
    }

    /**
     * Метод проверяет является ли переданное слово зарегистрированным в SQL
     * (см. массив $this->regWords)
     *
     * @param $word
     * @return bool
     */
    private function isRegWord($word)
    {


        $word = strtoupper($word);
        return in_array($word, $this->regWords);
    }

    /**
     * Метод возвращает WHERE запроса, $where
     * @param null $where  - если передали целое число,
     * то метод вернет условие $this->idColumn = $where
     * @return string
     */
    private function where($where = null)
    {

        if(preg_match('/^\d+$/', $where)) {
            return " WHERE `{$this->idColumn}` = {$where}";
        }

        return " WHERE {$where}";


    }

    /**
     * Метод возрващает последний sql запрос
     * В данный момент работает только с методами:
     * update, delete, insert
     * @return string
     */
    public function getLastSql()
    {
        return $this->lastSql;
    }


    public function getObject($dbTable, $where = null)
    {
        $sql = "SELECT * FROM `{$dbTable}` ";
        $where =  $this->where($where);
        $sql .= $where;

        $stmt = $this->query($sql);
        $obj = $stmt->fetch(self::FETCH_OBJ);

        if(is_object($obj)) {
            return $obj;
        }

        return null;
    }


    public function listFields($table)
    {
        $sql = 'DESCRIBE '.$table;
        $query = $this->prepare($sql);
        $query->execute();

        $fields = $query->fetchAll(self::FETCH_COLUMN);

        return $fields;
    }


    /***
     *
     * $fields = array('id', 'parent_id', 'pos');
     *	$values = array( array (1,2,3), array (4,5,6),array (7,8,9) );
     * db->insertOrUpdate($fields, $values, 'table');
     *
     *
     * @param array $_fields
     * @param array $_values
     * @param $table
     * @throws \Exception
     */

    function insertOrUpdate(array $_fields,  array $_values, $table)
    {
        $values = '';
        $fields = '';
        $update = '';

        $size_fields=sizeof($_fields);

        for($i=0, $s=$size_fields; $i < $s; $i++)
        {

            $fields .= '`' . $_fields[$i] . '`,';
            $update .= '`' . $_fields[$i] . '` = VALUES(`' . $_fields[$i] . '`),';

        }
        $fields = trim($fields, ',');
        $update = trim($update, ',');


        for ($i=0, $si = sizeof($_values); $i < $si; $i++)
        {
            $size_values = sizeof($_values[$i]);

            if ($size_fields != $size_values)
            {
                throw new \Exception('Incorrect structure $_values');
            }
            $values .= '(';

            for ($j=0; $j < $size_values; $j++)
            {
                $values .=  $this->quote($_values[$i][$j]) . ",";
            }

            $values = trim($values, ',');

            $values .= '),';

        }
        $values = trim($values, ',');


        $sql = 'INSERT INTO  ' . $table . ' (' . $fields . ') VALUES ' .
            $values . ' ON DUPLICATE KEY UPDATE ' . $update;



        $this->exec($sql);


    }

}