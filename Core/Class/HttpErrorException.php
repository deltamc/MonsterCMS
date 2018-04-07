<?php  namespace  Monstercms\Core;

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Class HttpErrorException
 * @package Monstercms\Core
 */
class HttpErrorException extends \Exception
{
    private $codes = array(
        403 => '403 Forbidden',
        404 => '404 Not Found'
    );

    protected $code = 0;

    function __construct($code)
    {
        if (!isset($this->codes[$code])) return ;

        $this->code = $code;

        $msg = $this->codes[$code];

        parent::__construct($msg, $code);
    }

    /**
     * Заголовок ответа
     */
    public function header()
    {
        if (isset($this->codes[$this->code])) {
            header('HTTP/1.1 ' . $this->codes[$this->code], true, $this->code);

        }
    }




}