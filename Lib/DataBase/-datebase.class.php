<? namespace  Monstercms\Lib;

 class datebase
 {

    private $link;
	private $db;
	private $dbType;
	private $host, $user, $pass, $port;
	public $sql; 	/* SQL - Запрос (метод query)*/
    public $debugging;

     /**
      * @param $db - имя базы данных
      * @param $host - сервер бд
      * @param $user - пользователь ДБ
      * @param $pass - пароль
      * @param string $dbType - тип бд (MYSQL|MYSQLI)
      * @param int $port
      */

    function __construct($db, $host, $user, $pass, $dbType = "MYSQL", $port = 5432)
    {

    	$this->db = $db;
    	$this->dbType = $dbType;

        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;

        switch($this->dbType)
        {
              case "MYSQL":

			  	if($this->link= mysql_connect($host, $user, $pass))
			  	{
			  		return mysql_select_db($db, $this->link);
			  	}
			  	else return 0;

              break;

              case "MYSQLI":

                if($this->link = mysqli_connect($host,$user,$pass))
			  	{
			  		return mysqli_select_db($this->link, $db);
			  	}
			  	else return 0;

              break;



        }


    }

    /*
	 *	get_server_info() - Возвращает информацию о сервере
	 */

    function get_server_info()
    {
    	switch($this->dbType)
        {
             case "MYSQL":

    			return mysql_get_server_info($this->link);

    		 break;

             case "MYSQLI":

                 return mysqli_get_server_info($this->link);

             break;
         }
    }


	/*
	 *	info() - Возвращает информацию о последнем запросе
	 */

	function info()
	{
    	switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_info($this->link);

	    	break;

        	case "MYSQLI":

            	return mysqli_info($this->link);

	        break;
        }
	}




	function status()
	{
    	switch($this->dbType)
        {

			case "MYSQL":

				$query = $this->query("SHOW STATUS");
				$status = array();

    			while($row=$this->fetch_row($query))
    			{
    				$status[$row[0]] = $row[1];
    			}

                return $status;

	    	break;

        	case "MYSQLI":

            	$query = $this->query("SHOW STATUS");
				$status = array();

    			while($row=$this->fetch_row($query))
    			{
    				$status[$row[0]] = $row[1];
    			}

                return $status;

	        break;
        }
	}



	function status_table($datebase_name, $table="")
	{


				$query = $this->query("SHOW TABLE STATUS FROM ".$datebase_name." LIKE '".$table."' ");
    			return $this->fetchArray($query,MYSQL_ASSOC);

	}



    /*
	 *  get_client_info() - Возвращает данные о MySQL-клиенте
	 */

	function get_client_info()
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_get_client_info();

	    	break;

        	case "MYSQLI":

            	return mysqli_get_client_info($this->link);

	        break;
        }
	}



	/*
	 *  get_host_info() - Возвращает информацию о соединении
	 */

	function get_host_info()
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_get_host_info($this->link);

	    	break;

        	case "MYSQLI":

            	return mysqli_get_host_info($this->link);

	        break;
        }
	}

	/*
	 *  get_get_proto_info() - Возвращает информацию о протоколе
	 */

	function get_proto_info()
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_get_proto_info($this->link);

	    	break;

        	case "MYSQLI":

            	return mysqli_get_proto_info($this->link);

	        break;
        }
	}

	/*
	 *  query() - посылает запрос активной базе данных сервера, на который ссылается переданный указатель
	 *
	 *  query ( string $query )
	 *
     *
	 */

	function query()
	{

        $args = func_get_args();

        if(sizeof($args) == 0 ) return ;

        if(sizeof($args) == 1 ) $query = $args[0];
        else $query = $this->make_qw($args);


        $this->sql = $query;
        $start_time_sql=microtime();
        //print $query."<br/>";
 		 switch($this->dbType)
        {

			case "MYSQL":

    			$result =  mysql_query($query,$this->link);

	    	break;

        	case "MYSQLI":

            	$result =  mysqli_query($this->link,$query);


	        break;

	        case "POSTGRESQL":

            	$result = pg_query($this->link,$query);

	        break;

        }



        if($this->debugging)
        {
            $query = str_replace(PHP_EOL, "", $query);
            $query = preg_replace("/\s+/", " ", $query);
            $te=explode(' ',$start_time_sql);
    		$now=explode(' ',microtime());
    		$times=$now[0]-$te[0]+$now[1]-$te[1];
    		//print_r($te);
    		$times = number_format($times,4,'.',' ');

    		$count_row=$this->numRows($result);

            //\lib\JavaScript::add("console.log('".$query." ".$count_row." row in set (".$times.")');");


        	//$this->framework->debugging->sql_query($query,$count_row." row in set (".$times.")");
        }

        if(!$result)
        {//print $this->sql;
            $this->error_info();
        }
        return $result;
	}


    private function make_qw($args)
    {
        $tmpl =& $args[0];
        $tmpl = str_replace("%", "%%", $tmpl);
        $tmpl = str_replace("?", "%s", $tmpl);

        foreach($args as $i=>$v)
        {
            if(!$i) continue;
            if(is_int($v)) continue;
            $args[$i] = "'".mysql_real_escape_string($v)."'";
        }

        return call_user_func_array("sprintf",$args);
    }



    /*
	 *  numRows($result) - Возвращает количество рядов результата запроса

	 */

	function numRows($result)
	{
        if(!is_resource($result))
        {
            return 0;
        }

        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_num_rows($result);

	    	break;

        	case "MYSQLI":

            	return mysqli_num_rows($result);

	        break;
        }
	}

	/*
	 *  affected_rows($result) - возвращает количество рядов, затронутых последним INSERT, UPDATE, DELETE запросом к серверу

	 */

	function affected_rows()
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_affected_rows($this->link);

	    	break;

        	case "MYSQLI":

            	return mysqli_affected_rows($this->link);

	        break;
        }
	}


	/*
	 *  fetchArray ( resource $result [, int $result_type ] ) - Возвращает массив с обработанным рядом результата запроса,
	 *  или FALSE, если рядов больше нет.
     *  Второй опциональный аргумент result_type в функции fetchArray() - константа и может
     *  принимать следующие значения: MYSQL_ASSOC, MYSQL_NUM и MYSQL_BOTH.
     *  Значением по умолчанию является: MYSQL_BOTH.
	 */

	function fetchArray($result,$result_type=MYSQL_BOTH)
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_fetch_array($result,$result_type);

	    	break;

        	case "MYSQLI":

            	return mysqli_fetch_array($result,$result_type);

	        break;
        }
	}



	/*
	 *  fetch_row ( resource $result) - Обрабатывает ряд результата запроса и возвращает неассоциативный массив.
	 */

	function fetch_row($result)
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_fetch_row($result);

	    	break;

        	case "MYSQLI":

            	return mysqli_fetch_row($result);

	        break;
        }
	}

	/*
	 *  fetch_assoc ( resource $result) - Обрабатывает ряд результата запроса и возвращает ассоциативный массив.
	 */

	function fetch_assoc($result)
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_fetch_assoc($result);

	    	break;

        	case "MYSQLI":

            	return mysqli_fetch_assoc($result);

	        break;
        }
	}

	/*
	 *  fetchObject ( resource $result) - Обрабатывает ряд результата запроса и возвращает объект
	 */

	function fetchObject($result)
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_fetch_object($result);

	    	break;

        	case "MYSQLI":

            	return mysqli_fetch_object($result);

	        break;
        }
	}


	/*
	 *  mysql_client_encoding — Возвращает кодировку соединения
	 */

	function client_encoding()
	{
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_client_encoding($this->link);

	    	break;

        	case "MYSQLI":

            	return mysqli_client_encoding($this->link);

	        break;
        }
	}





    /*
	 *  list_dbs () - Возвращает список баз данных, доступных на сервере.
	 */

	function list_dbs()
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return  $this->query("SHOW DATEBASE");

	    	break;

        	case "MYSQLI":

                return  $this->query("SHOW DATEBASE");

	        break;
        }
	}

	/*
	 *  list_tables() - Возвращает список таблиц базы данных
	 */

	function list_tables($database="")
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return  $this->query("SHOW TABLES FROM `".$this->escape_string($database)."`");

	    	break;

        	case "MYSQLI":

                return  $this->query("SHOW TABLES FROM `".$this->escape_string($database)."`");

	        break;
        }
	}


	/*
	 *  create_db($database_name) - Создаёт базу данных
	 */

	function  create_db($database_name)
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return $this->query("CREATE DATABASE `".$this->escape_string($database_name)."`");

	    	break;

        	case "MYSQLI":

                return $this->query("CREATE DATABASE `".$this->escape_string($database_name)."`");

	        break;
        }
	}


	/*
	 *  drop_db($database_name) - удаляет базу данных
	 */

	function  drop_db($database_name)
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return $this->query("DROP DATABASE `".$this->escape_string($database_name)."`");

	    	break;

        	case "MYSQLI":

                return $this->query("DROP DATABASE `".$this->escape_string($database_name)."`");

	        break;
        }
	}

	/*
	 *  error() - Возвращает строку ошибки последней операции
	 */


    function  error()
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return $this->errno().": ".mysql_error($this->link);

	    	break;

        	case "MYSQLI":

                return $this->errno().": ".mysqli_error($this->link);

	        break;
        }
	}

    /*
	 *  errno() - Возвращает численный код ошибки выполнения последней операции
	 */


	function  errno()
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_errno($this->link);

	    	break;

        	case "MYSQLI":

                return mysqli_errno($this->link);

	        break;
        }
	}

    /*
	 *  insertId() - Возвращает численный код ошибки выполнения последней операции
	 */


	function  insertId()
    {
        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_insert_id($this->link);

	    	break;

        	case "MYSQLI":

                return mysqli_insert_id($this->link);

	        break;
        }
	}





    /*
	 *  escape_string($query) - Экранирует специальные символы в строках для использования в выражениях
	 */

	function  escape_string($query)
    {

        if(get_magic_quotes_gpc()) $query = stripslashes($query);


        switch($this->dbType)
        {

			case "MYSQL":

    			return mysql_real_escape_string($query, $this->link);

	    	break;

        	case "MYSQLI":

                $expr = Array
                (
                	"'",
                	"\"",
                	'\x00',
                	'\n',
                	'\r',
                	'\x1a'
                );

                $to = Array
                (
                	"\'",
                	'\"',
                	'\\\x00',
                	'\\\n',
                	'\\\r',
                	'\\\x1a'
                );

                return str_replace($expr,$to,$query);

	        break;
        }
	}

     public function real_escape_string($query)
     {
         return $this->escape_string($query);
     }


    private $reg_word = Array
    (
     	   		"NULL", "NOW()","UNIX_TIMESTAMP()"
    );
    /*
	 *  insert($list,$table) - Вставляет в таблицу данные из массива
	 */


    function insert($list,$table)
    {
           $cols="";
           $values="";

		   $s=0;


           $sizeList = sizeof($list);


           foreach($list as $key=>$value)
           {
              $cols   .= "`".$this->escape_string($key)."`";

              if(!in_array(strtoupper($value),$this->reg_word))
              {

              	$values .= "'".$this->escape_string($value)."'";


              }
              else
              {

              	$values .= $value;

              }


              $s++;

              if($s != $sizeList)
              {
                 $cols   .= ", ";
                 $values .= ", ";
              }
           }


           $sql = "INSERT INTO `".$table."` (".$cols.") VALUES(".$values.")";
           //print $sql;

           return $this->query($sql);

    }

	/*
	 *  insert($list,$table) - Вставляет в таблицу данные из массива
	 */


    function update($list,$table, $where="")
    {

           $values="";

		   $s=0;



           foreach($list as $key=>$value)
           {

              if(!in_array(strtoupper($value),$this->reg_word))
              {

              	$values .= "`".$this->escape_string($key)."` = '".$this->escape_string($value)."'";

              }
              else
              {

              	$values .= "`".$this->escape_string($key)."` = ".$value;

              }


              $s++;

              if($s != sizeof($list))
              {
                 $values .= ", ";
              }
           }


           $sql = "UPDATE `".$table."` SET $values";
           if($where!=="")
           {

           		$sql.=" WHERE ".$where;

           }

        //print $sql;
          return $this->query($sql);

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
				 $values .= "'" . $this->escape_string($_values[$i][$j]) . "',";
			 }

			 $values = trim($values, ',');

			 $values .= '),';

		 }
		 $values = trim($values, ',');


		 $sql = 'INSERT INTO  ' . $table . ' (' . $fields . ') VALUES ' .
			 $values . ' ON DUPLICATE KEY UPDATE ' . $update;


		$this->query($sql);
	 }


    function multy_query($sql)
    {
        $sql = trim($sql);

   		if($sql == "")
        {
        	return "true";
      	}

   		$sql = preg_replace("/--|#.*/", "", $sql);

   		$sql = explode(";",$sql);
   		$error = "";

   		for ($i=0;$i<sizeof($sql)-1;$i++)
   		{

	   		if(!@$this->query($sql[$i]))
	   		{
                     $error.="<li>".$this->error()."</li>";
            }
   		}
   		if($error!=="")
   		{
           		return $error;
        }
   		else
   		{
           		return "true";
        }

    }

     /* функция выполняет запрос, переберает полученные строки и считывает шаблон*/
     /**
      * @param $sql - sql запрос
      * @param $tplClass - экземпляр класса littltempl
      * @param $tpl - шаблон
      * @return string
      */

     public function  row_list_tpl($sql, $tplClass, $tpl, $vars = array())
     {
         if(!method_exists($tplClass,'get')) return "";

         if(!is_resource($sql)) $result = $this->query($sql);
		 else 	$result = $sql;

         $html = "";
         while($row = $this->fetchArray($result))
		 {
			 $vars['row'] = $row;
			 $html .= $tplClass->get($tpl, $vars);
		 }
         return $html;
     }

     public function getRow($sql)
     {
         $result = $this->query($sql);
         if(!$this->numRows($result)) return false;

         return $this->fetchArray($result);
     }


     private function error_info()
     {
         if(!$this->debugging) return false;
         $dbt = debug_backtrace();
         print "File ".$dbt[1]['file'].'<br />';
         print "Line ".$dbt[1]['line'].'<br />';
         print "SQL ".$this->sql.'<br />';
         //print_r($dbt);
     }


	 public function list_fields($table)
	 {
		 $table = $this->real_escape_string($table);
		 $sql = 'SHOW COLUMNS FROM '.$table;

		 $result = $this->query($sql);
		 $fields = array();
		 while($field = $this->fetchArray($result))
		 {
			 $fields[] = $field['Field'];
		 }

		 return $fields;
	 }

	 public function getRows($sql)
	 {
		 $result = $this->query($sql);
		 if(!$this->numRows($result)) return false;


		 $out = array();

		 while($row = $this->fetchArray($result))
			 $out[] = $row;

		 return $out;

	 }

 }





?>