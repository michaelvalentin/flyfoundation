<?php

require_once __DIR__ . '/postmark-php/Postmark.php';

class Email
{
    public $to_mail = null;
    public $to_name = null;
    public $from_mail = null;
    public $from_name = null;
    public $subject = "";
    public $message = "";

    public function __construct($to_mail=false, $to_name=false, $subject=false, $from_mail=false, $from_name=false)
    {
        foreach(array("to_mail","to_name","subject","from_mail","from_name") as $v)
                if($$v) $this->$v = $$v;
    }
    
    public function sendHtml()
    {
        require_once("postmark-php/Postmark.php");
        define('POSTMARKAPP_API_KEY', '120bca34-f743-402c-8650-d91767e5d0e7');
        
        if(!$this->to_mail || !$this->from_mail){
            throw new Exception("Emails can't be send without a sender and a reciever (to_mail and from_mail)");
        }

        Mail_Postmark::compose()
                ->addTo($this->to_mail, $this->to_name)
                ->from($this->from_mail, $this->from_name)
                ->subject($this->subject)
                ->messageHtml($this->message)
                ->tag(BASE_URL)
                ->send();

        return true;
    }

}