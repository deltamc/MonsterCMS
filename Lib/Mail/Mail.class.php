<?php

/**
 * Created by PhpStorm.
 * User: Danila
 * Date: 12.01.2016
 * Time: 15:45
 */
namespace  Monstercms\Lib;

class Mail
{

    private $header;

    private $to, $subject, $message, $reply, $form;


    /**
     * @param $to - кому
     * @param $subject - тема
     * @param $message  - текст письма (html)
     * @param $form - от кого (например: вася)
     * @param null $reply - e-mail куда приходят ответы на письма
     */
    function __construct($to, $subject, $message,  $form, $reply = null, $charset='utf-8')
    {

        $this->to      = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->reply   = $reply;
        $this->from    = $form;

        $form = ($reply) ? $form . ' <'.$reply.'>' : $form;


        $this->addHeader("From: ".$form);
        $this->addHeader('X-Mailer: PHP/' . phpversion());
        $this->addHeader('MIME-Version: 1.0');
        $this->addHeader('Content-Type: text/html; charset='.$charset);
        if($reply) $this->addHeader('Reply-To: '.$reply);
        $this->addHeader('Content-Transfer-Encoding: 8bit');
    }

    function send()
    {
        mail($this->to, $this->subject, $this->message, $this->header.PHP_EOL);
    }



    public function addHeader($header)
    {
        $this->header .= $header . PHP_EOL;
    }
}