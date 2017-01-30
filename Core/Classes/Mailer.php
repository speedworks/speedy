<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 01/27/17
 * Time: 06:43 PM
 */
namespace Core\Mailer;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mailer
{
    private $mailEngine = null;
    private $mailTo = array();
    private $mailCc = array();
    private $mailBcc = array();
    private $mailFrom = null;
    private $mailSubject = null;
    private $mailBody = null;
    private $mailContentType = null;
    private $mailHeaders = array();
    private $mailHost = null;
    private $mailPort = null;
    private $mailUser = null;
    private $mailPassword = null;
    private $mailEncryption = null;
    private $mailAttachments = array();
    private function __construct()
    {
        $this->mailEngine = $_ENV['mail']['engine'];
        $this->mailHost = $_ENV['mail']['host'];
        $this->mailPort = $_ENV['mail']['port'];
        $this->mailUser = $_ENV['mail']['user'];
        $this->mailPassword = $_ENV['mail']['password'];
        $this->mailEncryption = $_ENV['mail']['encryption'];
    }

    /**
     * @deprecated use the config file instead
     * @param $engine
     * @param null $host
     * @param null $port
     * @param null $user
     * @param null $password
     * @param null $encryption
     * @return Mailer class object
     */
    public function setEngine($engine, $host=null, $port=null, $user=null, $password=null, $encryption=null)
    {
        $this->mailEngine = $engine;
        $this->mailHost = $host;
        $this->mailPort = $port;
        $this->mailUser = $user;
        $this->mailPassword = $password;
        $this->mailEncryption = $encryption;
        return $this;
    }
    public static function init()
    {
        $mailer = new Mailer();
        return $mailer;
    }
    public function to($recipients)
    {
        if(is_array($recipients))
        {
            foreach ($recipients as $recipient)
            {
                $this->mailTo[] = $recipient;
            }
        }
        else
        {
            $this->mailTo[] = $recipients;
        }
        return $this;
    }
    public function from($from)
    {
        if(is_array($from))
        {
            $this->mailFrom = $from;
        }
        else
        {
            $this->mailFrom = [$from => $from];
        }
        return $this;
    }
    public function cc($cc)
    {
        if(is_array($cc))
        {
            foreach ($cc as $copy)
            {
                $this->mailCc[] = $copy;
            }
        }
        else
        {
            $this->mailCc[] = $cc;
        }
        return $this;
    }
    public function bcc($bcc)
    {
        if(is_array($bcc))
        {
            foreach ($bcc as $bcopy)
            {
                $this->mailBcc[] = $bcopy;
            }
        }
        else
        {
            $this->mailBcc[] = $bcc;
        }
    }
    public function contentType($type)
    {
        $this->mailContentType = $type;
        return $this;
    }
    public function subject($subject)
    {
        $this->mailSubject = $subject;
        return $this;
    }
    public function body($body)
    {
        $this->mailBody = $body;
        return $this;
    }
    public function send($options=null)
    {
        if(strtolower($this->mailEngine) == "php")
        {
            return $this->corePHPMailer($options);
        }
        else if(strtolower($this->mailEngine) == "swiftmailer")
        {
            return $this->swiftMailer($options);
        }
    }
    public function attach($attachmentPath)
    {
        $this->mailAttachments[] = $attachmentPath;
        return $this;
    }
    private function corePHPMailer($options)
    {
        $options = null;
        foreach ($this->mailFrom as $name=>$mail)
        {
            $this->mailHeaders[] = "From: ".$name." <".$mail.">";
        }
        $this->mailHeaders[] = "Content-Type: ".$this->mailContentType;
        $this->mailHeaders[] = "Cc: ".implode(",",$this->mailCc);
        $this->mailHeaders[] = "Bcc: ".implode(",",$this->mailBcc);
        $response = mail(implode(",",$this->mailTo), $this->mailSubject , wordwrap($this->mailBody,70), implode("\r\n", $this->mailHeaders),$options);
        return $response;
    }
    private function swiftMailer($options)
    {
        $transport = Swift_SmtpTransport::newInstance($this->mailHost, $this->mailPort, $this->mailEncryption)
                     ->setUsername($this->mailUser)
                    ->setPassword($this->mailPassword)
                    ->setStreamOptions($options);
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($this->mailSubject)
            ->setFrom($this->mailFrom)
            ->setTo($this->mailTo)
            ->setCc($this->mailCc)
            ->setBcc($this->mailBcc)
            ->setContentType($this->mailContentType)
            ->setBody($this->mailBody);
        if(count($this->mailAttachments)>0)
        {
            foreach($this->mailAttachments as $path => $name)
            {
                $message->attach(
                    Swift_Attachment::fromPath('/path/to/image.jpg')->setFilename('myfilename.jpg')
                );
            }
        }
        $result = $mailer->send($message);
        return $result;
    }

}