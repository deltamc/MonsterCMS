<?
namespace  Monstercms\Lib;
 	class Image
 	{
        public $framework;
        public $inputFile;
        public $gd_info;
        public $type_img;
        public $img_ouput;
        public $img;
        public $width;
        public $height;

   		function __construct($file)
		{

			@ini_set('memory_limit', '256M');

			$this->framework  =  false;
            $this->inputFile  =  $file; //������ �������� �������� �����������

            $this->type_img   = $this->imageType( $this->inputFile );
			$this->gd_info 	  = gd_info();

			$size_img = getimagesize($this->inputFile);

            $this->width  =  $size_img[0];
            $this->height =  $size_img[1];

			$this->img 		  = $this->create();

            $this->img_ouput = imagecreatetruecolor($this->width, $this->height);

            $type_img = $this->type_img;
			$gd 	  = $this->gd_info;

         	if( $type_img == 3 AND $gd['PNG Support'] == 1 )
         	{

                      /// $black=imagecolorclosest($this->img_ouput, 0, 0, 0);
    				   // ������� ������������ ������� ����� ����
    					//imagecolortransparent($this->img_ouput, $black);


    			imageSaveAlpha($this->img_ouput, true);

    			$trans_colour = imagecolorallocatealpha($this->img_ouput, 0, 0, 0, 127);
    			imagefill($this->img_ouput, 0, 0, $trans_colour);


            }

            //imageCopyResampled ($this->img_ouput, $this->img, 0, 0, 0, 0, $this->width, $this->height,             $this->width, $this->height);
			                  $this->img_ouput =   $this->img;


		}


        function create()
        {

         	$type_img = $this->type_img;
			$gd 	  = $this->gd_info;

         	if( $type_img == 3 AND $gd['PNG Support'] == 1 )
         	{         		$img= imagecreatefromPNG( $this->inputFile );
				         	}
         	elseif( $type_img == 2 AND ($gd['JPEG Support'] == 1 || $gd['JPG Support'] == 1) )
         	{
         		$img= imagecreatefromJPEG( $this->inputFile );

         	}
            elseif( $type_img == 1 AND $gd['GIF Create Support'] == 1  )
            {            	$img 	 = imagecreatefromGIF( $this->inputFile );
            }


            return $img;        }

         function save($file)
         {
             $type_img = $this->type_img;
			$gd 	  = $this->gd_info;

         	if( $type_img == 3 AND $gd['PNG Support'] == 1 )
         	{
         		imagepng($this->img_ouput, $file);
         	}
         	elseif( $type_img == 2 AND ($gd['JPEG Support'] == 1 || $gd['JPG Support'] == 1) )
         	{
         		imagejpeg($this->img_ouput, $file,100);

         	}
            elseif( $type_img == 1 AND $gd['GIF Create Support'] == 1  )
            {
            	imagegif($this->img_ouput, $file);
            }


    		imagedestroy($this->img);
			 @imagedestroy($this->img_ouput);
         }













  		public function resize($w, $h, $stretch = false, $scale=false)
    	{
            $width = $w;
            $height =$h;
    		$ratio = $w/$h;

    		$size_img = getimagesize($this->inputFile);

    		$src_ratio=$this->width/$this->height;

    		if(($this->width>$w || $this->height>$h) && !$stretch)
    		{
     			if ($ratio<$src_ratio)
     			{
     	    		$h = $w/$src_ratio;
    			}
    			else
    			{
    	  			$w = $h*$src_ratio;

    			}

			}

			else
			{

				$h = $w/$src_ratio;

			}
            if($scale)
			{
    			if($this->width<$w ) $w = $this->width;
    			if($this->height<$h ) $h = $this->height;

			}


            $this->img_ouput = imagecreatetruecolor($w, $h);


    		imageSaveAlpha($this->img_ouput,     true);

  			$trans_colour = imagecolorallocatealpha($this->img_ouput, 0, 0, 0, 127);
    		imagefill($this->img_ouput, 0, 0, $trans_colour);

    		imagecopyresampled($this->img_ouput, $this->img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);

            $this->img = $this->img_ouput;

	    	return $this;
  		}


  		function pixelOperation($operation_callback, $factor = false)
    	{
        	for ($x = 0; $x < $this->width; $x++) {
            	for ($y = 0; $y < $this->height; $y++) {

                	$rgb = imagecolorat($this->img, $x, $y);
                	$r = ($rgb >> 16) & 0xFF;
                	$g = ($rgb >> 8) & 0xFF;
                	$b = $rgb & 0xFF;

                	$pixel = new Pixel($r, $g, $b);
                	$pixel = call_user_func(
                    	$operation_callback,
                    	$pixel,
                    	$factor
                	);

                	$color = imagecolorallocate(
                    	$this->img,
                    	$pixel->r,
                    	$pixel->g,
                    	$pixel->b
                	);

                	imagesetpixel($this->img_ouput, $x, $y, $color);
                }

            }


       }


       function watermark($watermarkFile, $position = 'right', $alpha_level = 100 )
		{

  			 $watermark = imagecreatefrompng($watermarkFile);

  			// ������ � ������ �������� �����
  			$width = imagesx($watermark);
  			$height = imagesy($watermark);
  			if ( $position == 'right' ) { // ������� ���� ����� ����� ������
    			$dest_x = imagesx($this->img_ouput) - $width;
    			$dest_y = imagesy($this->img_ouput) - $height;
  			} else { // ������� ���� ����� �� ������
    			$dest_x = intval(imagesx($this->img_ouput)*0.5) - intval( $width  * 0.5 );
    			$dest_y = intval(imagesy($this->img_ouput)*0.5) - intval( $height * 0.5 );
  			}

            imageAlphaBlending($watermark, false);
    		imageSaveAlpha($watermark, true);

  			$trcolor = ImageColorAllocate($watermark, 255, 255, 255);
    		ImageColorTransparent($watermark , $trcolor);

  			imagecopy($this->img_ouput, $watermark, $dest_x, $dest_y, 0, 0, $width, $height);//, $alpha_level);

  			$this->img = $this->img_ouput;
  			return $this;
		}

		function watermark2($watermarkFile, $alpha_level = 100 )
		{

  			 $watermark = imagecreatefrompng($watermarkFile);



  			// ������ � ������ �������� �����
  			$width = imagesx($watermark);
  			$height = imagesy($watermark);
  		/*	if ( $position == 'right' ) { // ������� ���� ����� ����� ������
    			$dest_x = imagesx($this->img_ouput) - $width;
    			$dest_y = imagesy($this->img_ouput) - $height;
  			} else { // ������� ���� ����� �� ������
    			$dest_x = intval(imagesx($this->img_ouput)*0.5) - intval($width*0.5);
    			$dest_y = intval(imagesy($this->img_ouput)*0.5) - intval($height*0.5);
  			}
           */
            imageAlphaBlending($watermark, false);
    		imageSaveAlpha($watermark, true);

  			$trcolor = ImageColorAllocate($watermark, 255, 255, 255);
    		ImageColorTransparent($watermark , $trcolor);



    		$sx = intval(imagesx($this->img_ouput)/$width)+2;
            $sy = intval(imagesy($this->img_ouput)/$height)+2;
            for($i=0;$i<$sx;$i++)
            {
                 $dest_x = $i*$width-$width*0.5;

                for($j=0;$j<$sy;$j++)
                {
                 $dest_y = $j*$height-$height*0.5;
  				imagecopy($this->img_ouput, $watermark, $dest_x, $dest_y, 0, 0, $width, $height);//, $alpha_level);
  				}
  			}
  			$this->img = $this->img_ouput;
  			return $this;
		}

		function watermark3($watermarkFile, $alpha_level = 100 )
		{

  			 $watermark = imagecreatefrompng($watermarkFile);



  			// ������ � ������ �������� �����
  			$width = imagesx($watermark);
  			$height = imagesy($watermark);
  		/*	if ( $position == 'right' ) { // ������� ���� ����� ����� ������
    			$dest_x = imagesx($this->img_ouput) - $width;
    			$dest_y = imagesy($this->img_ouput) - $height;
  			} else { // ������� ���� ����� �� ������
    			$dest_x = intval(imagesx($this->img_ouput)*0.5) - intval($width*0.5);

  			}

            */

    		imageSaveAlpha($watermark, true);
           /*
    		$trans_colour = imagecolorallocatealpha($watermark, 0, 0, 0, 127);
    imagefill($watermark, 0, 0, $trans_colour);

    $red = imagecolorallocate($watermark, 255, 0, 0);
    imagefilledellipse($watermark, 400, 300, 400, 300, $red);
     */


    		$sx = intval(imagesx($this->img_ouput)/$width)+2;
            $sy = intval(imagesy($this->img_ouput)/$height)+2;

            $dest_y = intval(imagesy($this->img_ouput)*0.5) - intval($height*0.5);

            if(imagesy($this->img_ouput) < 500)
            {
            	for($i=0;$i<$sx;$i++)
            	{
                 	$dest_x = $i*$width;
  					imageCopyResampled($this->img_ouput, $watermark, $dest_x, $dest_y, 0, 0, $width, $height, $width, $height);

  				}
  			}
  			else
  			{
  				$dest_y2 =  $dest_y/2 - intval($height*0.5);

  				for($i=0;$i<$sx;$i++)
            	{
                 	$dest_x = $i*$width;

  					imageCopyResampled($this->img_ouput, $watermark, $dest_x, $dest_y2, 0, 0, $width, $height, $width, $height);

  				}

  				$dest_y3 =  $dest_y + $dest_y/2+ intval($height*0.5);

  				for($i=0;$i<$sx;$i++)
            	{
                 	$dest_x = $i*$width;

  					imageCopyResampled($this->img_ouput, $watermark, $dest_x, $dest_y3, 0, 0, $width, $height,$width, $height);

  				}  			}
  				$this->img = $this->img_ouput;
  			return $this;
		}


     	static function _blackAndWhite($pixel, $factor=0)
		{
    		$pixel_total = ($pixel->r + $pixel->g + $pixel->b);

    		if ($pixel_total > (((255 + $factor) / 2) * 3)) {
        		// �����
        		$pixel->r = 255;
        		$pixel->g = 255;
        		$pixel->b = 255;
   	 		} else {
        		$pixel->r = 0;
        		$pixel->g = 0;
        		$pixel->b = 0;
    		}

    		return $pixel;
		}


		function blackAndWhite($factor=0)
		{
   			$this->pixelOperation("image::_blackAndWhite",$factor);
   			return $this;
		}

        static function _greyscale($pixel, $factor=3)
		{
    		$pixel_average = ($pixel->r + $pixel->g + $pixel->b) / $factor;

    		return new Pixel(
    			$pixel_average,
    			$pixel_average,
    			$pixel_average
    		);
		}

        function greyscale($factor=3)
		{
   			$this->pixelOperation("image::_greyscale",$factor);
   			return $this;
		}


		function adjustBrightness($factor=0)
		{
   			$this->pixelOperation("image::_adjustBrightness",$factor);
   			return $this;
		}


		function _adjustBrightness($pixel, $factor)
		{
    		return new Pixel(
    			$pixel->r + $factor,
    			$pixel->g + $factor,
    			$pixel->b + $factor
    		);
		}




  		function image2GrayColor(){

			imagefilter( $this->img_ouput, IMG_FILTER_GRAYSCALE );

			 $this->img = $this->img_ouput;

	    	return $this;
		}

		function imageType( $img_path ){

			if( function_exists( 'exif_imagetype' ) ){
				return exif_imagetype( $img_path );
			}
			else{
				$arr_from_img = getimagesize ( $img_path );
				return $arr_from_img['2'];
			}
		} 	}




class Pixel {
    function Pixel($r, $g, $b)
    {
        $this->r = ($r > 255) ? 255 : (($r < 0) ? 0 : (int)($r));
        $this->g = ($g > 255) ? 255 : (($g < 0) ? 0 : (int)($g));
        $this->b = ($b > 255) ? 255 : (($b < 0) ? 0 : (int)($b));
    }
}

?>