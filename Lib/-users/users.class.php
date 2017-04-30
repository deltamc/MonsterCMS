<?
namespace  Monstercms\Lib;



class users
{    //Прификс таблиц
    public $prifix_db="";

    //Функция завершения регистрации
    public $function_end_reg = false;

   	public $logSessName="login";
    public $passSessName="pass";

    public $uzersConf=false;
    public $userMenu = array();


   	public $lan_input_login  	     = "Логин";
    public $lan_input_login_error    = "Пожалуйста, введите логин";

    public $lan_input_password 	  	 = "Пароль";
    public $lan_input_password_error = "Пожалуйста, введите пароль";


    public $lan_input_confirm_password 	  	 = "Еще раз пароль";
     public $lan_input_confirm_password_error = 'Пароли должны совпадать';

    public $lan_input_signup         = "Войти";
    public $lan_input_signup_error   = "Не правильно введен логин или пароль";
    public $lan_input_is_user_error  = "Данный логин уже занят";

    public $db;

	private $framework;

    function __construct($prifix_db = "")
    {
        global $contForm, $DB;


        $contForm ++;


        $this->db =  $DB;
        $this->logSessName  = $this->logSessName.$contForm;
        $this->passSessName = $this->passSessName.$contForm;

        $this->prifix_db = $prifix_db;
        session_start();



    }
	function user_all_array()
	{

	}

	function info()
		{
			$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_user`
					WHERE
  			    		`login`    = '".$this->db->escape_string($_SESSION[$this->logSessName])."'
    	 				AND
    	 				`password` = '".$this->db->escape_string($_SESSION[$this->passSessName])."'";

			$result = $this->db->query($sql);

			$info = $this->db->fetchArray($result);

			$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_conf`
					WHERE
  			    		id_user='".$info['id_user']."' ";

			$result = $this->db->query($sql);

			while($row=$this->db->fetchArray($result))
			{
				$info[$row['name_conf']] = $row['value'];
			}
            $_SESSION['userid']  =  $info['id_user'];

			return $info;

		}

	function info_id($id)
		{
			global $db;

			$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_user`
					WHERE
  			    		id_user = '".$id."'
						";

			$result = $this->db->query($sql);

			$info = $this->db->fetchArray($result);

			$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_conf`
					WHERE
  			    		id_user='".$info['id_user']."' ";

			$result = $this->db->query($sql);

			while($row=$this->db->fetchArray($result))
			{
				$info[$row['name_conf']] = $row['value'];
			}


			return $info;

		}


    function entryForm($location=false,$tplInput='',$tplSubmit='')
    {
        $l = $this->logSessName;
        $p = $this->passSessName;

        global $$l,$$p;

		$conf = array('attr'       => array('class'=>'authorization'));
     	$form = new form($conf);
        //$form->validAJAX = false;
        $form_body = array
        (
            array
            (
                'name' => "login",
                'type' => 'text',
                'label' => $this->lan_input_login,
                'valid' => array
                (
                    'required' => array(true, $this->lan_input_login_error)
                )
            ),

            array
            (
                'name' => "password",
                'type' => 'password',
                'label' => $this->lan_input_password,
                'valid' => array
                (
                    'required' => array(true, $this->lan_input_password_error),
                    'cell' => array(
                        array
                        (
                            array($this,'is_user_singup')
                        ),
                        $this->lan_input_signup_error ),

                )
            ),
            /*
            'password' => array
            (
            	'type'       => 'password',
            	'displayname' => $this->lan_input_password,
            	'validation' => array
            	(

            		array
                   (
            			'type'  => 'notnull',
	            		'error' => $this->lan_input_password_error,
            		),
                   array
                   (
            			'type'  => 'function',
            			'function' => 'users::is_user_singup',
            			'parameter' => array
            			(
                            'fw' => $this,
            				'prifix' => $this->prifix_db
            			),
	            		'error' => $this->lan_input_signup_error,
            		)
            	)

            )
            */
        );





      	$submit = array
      	(
      	    /*
            'submit1' => Array
      		(
     			 'type'           => 'submit',
				 'value'          => $this->lan_input_signup,
				 'html' => 'class="submit"',
			     'tpl' => '<p>{input}</p>',   //шаблон

            )
      	    */

            array
            (
                'type' => 'submit',
                'value' => ' OK '
            ),
        );

        if($tplInput !=  '')
        {
             $form_body['login']['tpl']    = $tplInput;
        	 $form_body['password']['tpl'] = $tplInput;
        }
        if($tplSubmit != '')
        {
        	 $form_body['submit1']['tpl'] = $tplSubmit;
        }
        //генирируем форму
        $form->add_items($form_body);
        //$form->addElements($form_body);
/*
        if(is_array($uzersConf))
        {        	$form->addElementsForm($uzersConf);        }
*/

        //$form->addElementsForm($submit);

        $form->add_items($submit);





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


              if( $this->is_user_pass( $_POST['login'], md5(md5($_POST['password'])) ) )
              {


                 $$l = $_POST['login'];
				 $$p = md5(md5($_POST['password']));

				 //сохраняем логи и пароль в сессиях
	    		 //session_register($l, $p);

				 $_SESSION[$this->logSessName] = $_POST['login'];
				 $_SESSION[$this->passSessName] = md5(md5($_POST['password']));


				 if(!$location)
				 {
					$this->reload();
				 }
				 else
				 {
					Header("Location: ".$location);
				 }
              }
              else
              {
                  $html  = $form->error();
                   $html  .= "<span style='color:red;' class='error_user_singup'>".$this->lan_input_signup_error."</span>";
              }

  		}

        return $html;
    }



	function is_user_pass($login="",$pass="")
	{

     	$sql = "SELECT
     					`id_user`
     			FROM
     			    	`".$this->prifix_db."_user`
  			    WHERE
  			    		`login`    = '".$this->db->escape_string($login)."'
    	 				AND
    	 				`password` = '".$this->db->escape_string($pass)."'";
       //print $sql;
        $result = $this->db->query($sql);

        if($this->db->numRows($result) >0)
        {        	return true;        }
        else
        {        	return false;        }	}


	function is_user()
	{
		//print $_SESSION[$this->logSessName];

		if
		(
			isset
			(
				$_SESSION[$this->logSessName],
				$_SESSION[$this->passSessName]

			)
			&&
			$this->is_user_pass
			(
				$_SESSION[$this->logSessName],
				$_SESSION[$this->passSessName]
			)


		)
		{
         	return true;		}
		else
		{            return false;		}	}

    public function is_user_singup($value)
    {

        $sql = "SELECT
     					`id_user`
     			FROM
     			    	`".$this->prifix_db."_user`
  			    WHERE
  			    		`login`    = '".$this->db->escape_string($_POST['login'])."'
    	 				AND
    	 				`password` = '".md5(md5($_POST['password']))."'";

        $result = $this->db->query($sql);

        if($this->db->numRows($result) >0)
        {
        	return true;
        }
        else
        {
        	return false;
        }
    }

	function uexit($location=false)
	{		//print  $this->passSessName;
		$_SESSION[$this->logSessName]  = "";
		$_SESSION[$this->passSessName] = "";

		//unset($_SESSION[$this->logSessName]);
		//unset($_SESSION[$this->logSessName]);

  		if(!$location)
		{
			$this->reload();
		}
		else
		{
			 header("Location: ".$location);

		}

		exit();	}

    function reload()
    {
        header("Location: http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
    	exit();
    }

    function regform($location=false,$singup=true,$fuction=false)
    {

    	$l = $this->logSessName;
        $p = $this->passSessName;

        global $db,$$l,$$p;


    	$form = $this->framework->form();
		//$form->validAJAX = false;
		$form->submitAJAX = false;
        $form_body = array
        (
            'login' => array
            (
            	'type'       => 'text',
            	'displayname' => $this->lan_input_login,
            	'help' => "",
            	'html' => "maxlength='25'",
            	'validation' => array
            	(
                   array
                   (
            			'type'  => 'notnull',
            			'error' => $this->lan_input_login_error,

            		),
            		array
            		(
            			'type'  => 'preg',
            			'reg'  => '/^[\d\w]{1,25}$/',
            			'error' => 'Логин содержит запрещенные символы'
            		),
            		array
                   (
            			'type'  => 'sql',
            			'error' => $this->lan_input_is_user_error,
            			'sql'=>"SELECT `id_user` FROM `".$this->prifix_db."_user` WHERE login = '{VALUE}'",
            			'db'=>$db
            		),

            	)

            ),
            'password' => array
            (
            	'type'       => 'password',
            	'displayname' => $this->lan_input_password,
            	'validation' => array
            	(

            		array
                   (
            			'type'  => 'notnull',
	            		'error' => $this->lan_input_password_error,
            		),
            		Array
            		(
          			'type'    => 'function',                         //тип проверки
          			'error'   => 'Пароли должны совпадать',
          			'function' => 'users::__srpass'                          //функция
		            )
            	)

            ),
            'password2' => array
            (
            	'type'       => 'password',
            	'displayname' => $this->lan_input_confirm_password.'*',
            	'validation' => array
            	(

            		array
                   (
            			'type'  => 'notnull',
	            		'error' => 'Пожалуйста, введите пароль'
            		)
            	)

            ),

            'email' => Array
            (
    			'type'           => 'text',      //* тип
			    'displayname'    => 'E-mail*',  //подпись
				//'help' => 'пример: "test@test.ru"',
		        'validation'     => Array
		        (
        			Array
        			(
          				'type'    => 'notnull',
          				'error'   => 'Пожалуйста, заполните поле',
			        ),

			        Array
			        (
          				'type'    => 'email',
          				'error'   => 'Пожалуйста, укажите свой E-mail',
			        ),

			    )
            )

        );


		$captcha = array
      	(
      	    'captcha' => array
            (
            	'type'=>'captcha',
            	'displayname'=>'введите защитный код',
            	'validation' =>array
            	(
            		array
            		(
            			'type'=>'captcha',
            			'error'=>'Не правельно ведне защитный код'
            		)
            	)

            )
        );

		$submit = array
      	(
      	    'submit3' => Array
      		(
     			 'type'           => 'submit',
				 'value'          => "    OK     ",
			     'tpl' => '<p align=center style="text-align:center;">{input}</p>',   //шаблон

            )
        );

        $form->addElementsForm($form_body);

		if(is_array($this->uzersConf))
        {
        	$form->addElementsForm($this->uzersConf);
        }

        $form->addElementsForm($submit);

        if(!$form->is_submit())
		{

			$html = $form->getForm();
		}
		elseif(!$form->valid())
		{

			$html = $form->getForm_error();

		}
		else
		{		   $bata = array
		   (
              'id_user'  => 'NULL',
              'login'    => $_POST['login'],
              'password' => md5(md5($_POST['password'])),
              'email'    => $_POST['email']
		   );
           $this->db->insert($bata, $this->prifix_db."_user");

           $idUser = $this->db->insertId();

           if(is_array($this->uzersConf))
           {
                 $bata = array();

            	 foreach($this->uzersConf as $key => $value)
           		 {                 	 $bata['id_user']   = $idUser;
                     $bata['name_conf'] = $key;
                     $bata['value']     = $_POST[$key];

                     $this->db->insert($bata, $this->prifix_db."_conf");
           		 }
           }
            if($singup)
            {
				$$l = $_POST['login'];
				$$p = md5(md5($_POST['password']));

	  		    //сохраняем логи и пароль в сессиях
		    	//session_register($l, $p);
				$_SESSION[$this->logSessName]  = $_POST['login'];
				$_SESSION[$this->passSessName] = md5(md5($_POST['password']));


			}

            if($this->function_end_reg)
            {            	//$f=$this->function_end_reg;
            	//$f($_POST['login'],$_POST['email']);

             	$function = explode("::", $this->function_end_reg[0]);
                if(sizeof($function) == 2)   $this->function_end_reg[0] = $function;
                call_user_func($this->function_end_reg[0],$this->function_end_reg[1]);            }
            if($fuction)
			{				$fuction($idUser);			}
			if($location)
			{
				header("Location: ".$location);
				exit();
			}

		}
		return 	$html;    }

	function login()
	{       return  $_SESSION[$this->logSessName];	}

	static function __srpass($value)
	{

 	if(isset($_POST['password'],$_POST['password2']))
 	{
 		if($_POST['password']!==$_POST['password2'])
 		{
 			return false;
 		}
 		else
 		{
 			return true;
 		}
 	}
	}

	function autorList($function)
	{    	$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_user`";

			$result = $this->db->query($sql);

   			while($row=$this->db->fetchArray($result))
			{

					$sql2 = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_conf`
					WHERE
  			    		id_user='".$row['id_user']."' ";

                   $result2 = $this->db->query($sql2);

           		   while($conf=$this->db->fetchArray($result2))
				   {
						$row[$conf['name_conf']] = $conf['value'];
				   }


              // $date = @array_merge($row,$confq);
               //print_r($date);
				$html .= $function($row);
			}

			return $html;	}

    //возвращает массив пользователей
	function autorListArray()
	{
    	$sql = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_user`";

			$result = $this->db->query($sql);
            $resultArray = array();
            $i=0;
   			while($row=$this->db->fetchArray($result))
			{

					$sql2 = "SELECT
     					*
					FROM
     			    	`".$this->prifix_db."_conf`
					WHERE
  			    		id_user='".$row['id_user']."' ";

                   $result2 = $this->db->query($sql2);

           		   while($conf=$this->db->fetchArray($result2))
				   {
						$row[$conf['name_conf']] = $conf['value'];
				   }


              	  $resultArray[$i] = $row;
                  $i++;
				//$html .= $function($row);
			}

			return $resultArray;
	}

	function del($id,$location=false,$function=false)
	{		$sql = "DELETE FROM `".$this->prifix_db."_user` WHERE id_user = '".INTVAL($id)."'";
  		$this->db->query($sql);

  		$sql = "DELETE FROM `".$this->prifix_db."_conf` WHERE id_user = '".INTVAL($id)."'";
  		$this->db->query($sql);

  		if($function) $function();
        if($location)
		{
			header("Location: ".$location);
			exit();
		}	}}