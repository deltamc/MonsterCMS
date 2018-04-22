<?php

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: Danila
 * Date: 08.01.2016
 * Time: 16:24
 */
namespace  Monstercms\Lib;
class Smpt
{

    private $server, $user, $password, $port;
    public $debag = false;
    private $header;

    function __construct($server, $user, $password, $port = 25)
    {
        $this->server   = $server;
        $this->user     = $user;
        $this->password = $password;
        $this->port     = $port;

    }

    public function  send($to, $subject, $message, $replyTo = null, $domen = null)
    {

        if(!$domen) $domen = $_SERVER['HTTP_HOST'];

        $rtime = $_SERVER['REQUEST_TIME'];

        $form = (!$replyTo) ? $domen : $domen . " <".$replyTo.">";

        $this->addHeader("From: ".$form);
        $this->addHeader('X-Mailer: PHP/' . phpversion());
        $this->addHeader("MIME-Version: 1.0");
        $this->addHeader("Content-Type: text/html; charset=utf-8");
        $this->addHeader("X-PHP-Script: ".$domen." for 93.91.230.17, 93.91.230.17");
        $this->addHeader('Message-ID: <' . $rtime . md5($rtime) . '@' . $domen .'>');
        $this->addHeader("Date: ".date('r'));
        $this->addHeader("Subject: ".$subject);
        if($replyTo)$this->addHeader('Reply-To: '. $replyTo);
        $this->addHeader('To: '.$to);
        $this->addHeader('Content-Transfer-Encoding: 8bit:');

        $errno  = null;
        $errstr = null;

        $smtp_conn = fsockopen($this->server, $this->port, $errno, $errstr, 10);
        if(!$smtp_conn) throw new Exception("cоединение с серверов не прошло");
        $data = $this->get_data($smtp_conn);


        $this->command($smtp_conn, "EHLO mail.nic.ru", 250, "ошибка приветсвия EHLO");
        $this->command($smtp_conn, "AUTH LOGIN", 334,   "сервер не разрешил начать авторизацию");
        $this->command($smtp_conn, base64_encode($this->user), 334,   "ошибка доступа к такому юзеру");
        $this->command($smtp_conn, base64_encode($this->password), 235,   "не правильный пароль");
        $this->command($smtp_conn, "MAIL FROM: ".$this->user, 250,   "сервер отказал в команде MAIL FROM");
        $this->command($smtp_conn, "RCPT TO: ".$to, 250,   "Сервер не принял команду RCPT TO");
        $this->command($smtp_conn, "DATA", 354,   "сервер не принял DATA");
        //$this->command($smtp_conn, $this->header.PHP_EOL.$message.PHP_EOL, 250,   "сервер не принял DATA");
        fputs($smtp_conn, $this->header.PHP_EOL.$message.PHP_EOL);

        print $this->header.PHP_EOL.$message;

        fputs($smtp_conn,"QUIT\r\n");
        fclose($smtp_conn);

    }

    private function command($smtpConn, $command, $checkCode, $error)
    {
        fputs($smtpConn, $command.PHP_EOL);

        if($this->debag)
        {
            $code = substr($this->get_data($smtpConn),0,3);
            if($code != $checkCode) throw new Exception($error);
        }
    }


    public function addHeader($header)
    {
        $this->header .= $header . PHP_EOL;
    }

    private function get_data($smtp_conn)
    {
        $data = "";

        while($str = fgets($smtp_conn,515))
        {
            $data .= $str;
            if(substr($str,3,1) == " ") break;
        }
        return $data;
    }
}