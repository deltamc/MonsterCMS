<?php namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class Upload
{

	var $file = false;
	var $error = 0;


    public $rash;

    public $img_ext = array('gif', 'jpg','jpeg', 'png');

    /**
     * @param $files - POST индекс (input type=file)
     * @param $dir - конечный путь для загрузки
     * @param $filename - имя файла
     * @param array $type - расширения допустимых файлов
     * @param $maxsize - максимальный размер файла
     * @return bool
     */
    function __construct($files, $dir, $filename=false, $type=false, $maxsize=false)
	{

        /*
        $files 	 = $param[0];
        $dir 	 = $param[1];
        $filename = $param[2];
        $type    = $param[3];
        $maxsize = $param[4];

        // print_r($param);

        if($filename == "") $filenam = false;
  		if($type    == "") $type    = false;
  		if($maxsize == "") $maxsize = false;

 	 	$this->framework =  $framework;
        */

        if(!preg_match('/\/$/',$dir)) $dir .= '/';



 		if (isset($_FILES[$files]))
 		{
 			$file_name = $_FILES[$files]['name'];
            $file_tmp_name = $_FILES[$files]['tmp_name'];
            $ext = self::ext($file_name);

            if (is_uploaded_file($file_tmp_name))
 			{
           		if($type)
	            {
        	         if(is_array($type) && !in_array($ext,$type))
            	     {
                	 	//Ошибка,  неверный формат
                	 	$this->error = 1;
                 		return false;
                     }
    	             elseif(is_string($type) && $ext!==$type)
        	         {
            	     	//Ошибка,  неверный формат
                	 	$this->error = 1;
                	 	return false;
                     }
    	       }

        	    if(!$maxsize)
                {
                    $maxsize = self::upload_max_size();
                }

                if(filesize($file_tmp_name) > $maxsize)
                {
                    //превышен максимальный размер
                    $this->error = 2;
                    return false;
                }

                if(in_array($ext, $this->img_ext) && !self::is_images($file_tmp_name, $ext))
                {
                    // не является картинкой
                    $this->error = 4;
                    return false;
                }



			    if(!$filename)
			    {
                    $filename = self::transliterate($file_name);
			    }
			    else
			    {
                    $filename .= ".".$ext;
                }

			    $this->file =  $filename;



			    if (@move_uploaded_file($file_tmp_name, $dir.$filename))
			    {


                	 	$this->error = 0;

                	 	return true;


			    }
			    else
			    {

                    $this->error = 3;
                	return false;
                }


	        }
	        else
	        {
                 $this->error = 3;
                 return false;
            }



    	}

	}

    /**
     * функция возвращает максимально возможный размер файла (в байтах) для загрузки,
     * который указан в настройках сервера.
     * @return int
     */
    static function upload_max_size()
    {
        if(get_cfg_var("post_max_size") < get_cfg_var("upload_max_filesize"))
        {
            $maxsize = get_cfg_var("post_max_size") * 1024 * 1024;
        }
        else
        {
            $maxsize = get_cfg_var("upload_max_filesize") * 1024 * 1024;
        }

        return $maxsize;
    }

    /**
     * функия проверяет является ли файл картинкой (gif, jpg, png)
     * для проверки используется библиотека GD
     * @param $file - имя файла
     * @return bool
     */
    static function is_images($file, $ext)
    {
        $image_mime = array
        (
            'image/gif',
            'image/jpeg',
            'image/png'
        );



        /* файла нет
        if(file_exists($file))
        {
            print 'The file '.$file.' does not exist';
            exit();
        }
        */
        /* не поддерживаетя GD */
        if (!function_exists('getimagesize'))
        {
            print 'GD support does not appear to be available in this PHP installation';
            exit();
        }

        $imageinfo = getimagesize($file);

        if(in_array($imageinfo['mime'], $image_mime)) return true;

        return false;
    }

    /**
     * @param $file - имя файла
     * @return string - расширение файла
     */
    static function ext($file)
    {
        return strtolower(substr($file, 1 + strrpos($file, ".")));
    }

	static function transliterate($st)
	{
        $replace=array(
            "'"=>"",
            "`"=>"",
            "а"=>"a","А"=>"a",
            "б"=>"b","Б"=>"b",
            "в"=>"v","В"=>"v",
            "г"=>"g","Г"=>"g",
            "д"=>"d","Д"=>"d",
            "е"=>"e","Е"=>"e",
            "ж"=>"zh","Ж"=>"zh",
            "з"=>"z","З"=>"z",
            "и"=>"i","И"=>"i",
            "й"=>"y","Й"=>"y",
            "к"=>"k","К"=>"k",
            "л"=>"l","Л"=>"l",
            "м"=>"m","М"=>"m",
            "н"=>"n","Н"=>"n",
            "о"=>"o","О"=>"o",
            "п"=>"p","П"=>"p",
            "р"=>"r","Р"=>"r",
            "с"=>"s","С"=>"s",
            "т"=>"t","Т"=>"t",
            "у"=>"u","У"=>"u",
            "ф"=>"f","Ф"=>"f",
            "х"=>"h","Х"=>"h",
            "ц"=>"c","Ц"=>"c",
            "ч"=>"ch","Ч"=>"ch",
            "ш"=>"sh","Ш"=>"sh",
            "щ"=>"sch","Щ"=>"sch",
            "ъ"=>"","Ъ"=>"",
            "ы"=>"y","Ы"=>"y",
            "ь"=>"","Ь"=>"",
            "э"=>"e","Э"=>"e",
            "ю"=>"yu","Ю"=>"yu",
            "я"=>"ya","Я"=>"ya",
            "і"=>"i","І"=>"i",
            "ї"=>"yi","Ї"=>"yi",
            "є"=>"e","Є"=>"e", " "=>"_"
        );
        return $str=iconv("UTF-8","UTF-8//IGNORE",strtr($st,$replace));


	}

}

?>