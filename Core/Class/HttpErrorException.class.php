<?php  namespace  Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

class HttpErrorException extends \Exception
{
    private $_codes = array(
        403 => '403 Forbidden',
        404 => '404 Not Found'
    );

    private $_code = 0;

    function __construct($code)
    {
        if(!isset($this->_codes[$code])) return ;

        $this->_code = $code;

        $msg = '';
        $msg = $this->_codes[$code];

        parent::__construct($msg, $code);
    }


    public function header()
    {
        
        if(isset($this->_codes[$this->code])) {
            header('HTTP/1.1 ' . $this->_codes[$this->code], true, $this->_code);

        }
    }


}