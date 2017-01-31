<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 01/27/17
 * Time: 06:43 PM
 */
namespace Core\Mailer;

use Exception;
use Swift_Attachment;
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
    private $mailContentType = "text/plain";
    private $mailHeaders = array();
    private $mailHost = null;
    private $mailPort = null;
    private $mailUser = null;
    private $mailPassword = null;
    private $mailEncryption = null;
    private $mailAttachments = array();

    /**
     * Mailer constructor.
     */
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
     * Sets the mailing Engine php/swiftmailer etc avoid using this method except for testing purposes
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

    /**
     * Initialize the mailing system
     * @return Mailer class Object
     */
    public static function init()
    {
        $mailer = new Mailer();
        return $mailer;
    }

    /**
     * Sets the mail recipients
     * @param $recipients can be a single email address or an array
     * @return Mailer class Object
     */
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

    /**
     * Sets the sender of the mail
     * @param $from can be email address or an associative array of length 1 containing the name as key and email as value
     * @return Mailer class Object
     */
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

    /**
     * Sets the CC recipient of the email
     * @param $cc can be a single email address or an array of addresses
     * @return Mailer class Object
     */
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

    /**
     * Sets the Bcc recipient of the email
     * @param $bcc
     * @return Mailer class Object
     */
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
        return $this;
    }

    /**
     * Sets the content type of mail message defaults to text/plain
     * @param $type
     * @return Mailer class Object
     */
    public function contentType($type)
    {
        $this->mailContentType = $type;
        return $this;
    }

    /**
     * Sets the email subject
     * @param $subject
     * @return Mailer class Object
     */
    public function subject($subject)
    {
        $this->mailSubject = $subject;
        return $this;
    }

    /**
     * Set the body of the email message a custom view can also be passed as a string
     * @param $body
     * @return Mailer class Object
     */
    public function body($body)
    {
        $this->mailBody = $body;
        return $this;
    }

    /**
     * Adds an attachment to the mail
     * @param $attachmentPath
     * @param $fileName
     * @return $this
     */
    public function attach($attachmentPath, $fileName)
    {
        $this->mailAttachments[$attachmentPath] = $fileName;
        return $this;
    }

    /**
     * Send the compiled mail
     * @param null $options for swiftmailer/php mailer
     * @return bool mail send response
     */
    public function send($options=null) //$options['ssl']['verify_peer'] = false;
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

    /**
     * PHP mailer
     * @param $options
     * @return bool
     * @throws Exception
     */
    private function corePHPMailer($options)
    {
        $options = null;
        $this->mailHeaders[] = "MIME-Version: 1.0";
        $this->mailHeaders[] = "Content-Type: ".$this->mailContentType;
        $this->mailHeaders[] = "Cc: ".implode(",",$this->mailCc);
        $this->mailHeaders[] = "Bcc: ".implode(",",$this->mailBcc);
        foreach ($this->mailFrom as $name=>$mail)
        {
            $this->mailHeaders[] = "From: ".$name." <".$mail.">";
        }
        if(count($this->mailAttachments)>0)
        {
            $this->mailBody .= "\r\n";
            foreach($this->mailAttachments as $path => $name)
            {
                $fileData = null;
                try
                {
                    $fileHandle = fopen($path, 'r');
                    $fileData = stream_get_contents($fileHandle);
                    fclose($fileHandle);
                }
                catch(Exception $e)
                {
                    Throw New Exception("Unable to open File for Reading",1);
                }
                $this->mailBody .= "Content-Type: application/octet-stream; name=\"" . $name . "\"\r\n";
                $this->mailBody .= "Content-Transfer-Encoding: base64\r\n";
                $this->mailBody .= "Content-Disposition: attachment\r\n";
                $this->mailBody .= chunk_split(base64_encode($fileData)) ."\r\n";
            }
        }
        $response = mail(implode(",",$this->mailTo), $this->mailSubject , wordwrap($this->mailBody,70), implode("\r\n", $this->mailHeaders),$options);
        return $response;
    }

    /**
     * Swiftmailer
     * @param $options
     * @return int
     */
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
                $message->attach(Swift_Attachment::fromPath($path)->setFilename($name));
            }
        }
        $result = $mailer->send($message);
        return $result;
    }

    /**
     * @internal
     * Test mailer
     * @return bool
     */
    protected function test()
    {
        $options['ssl']['verify_peer'] = false;
        return Mailer::init()
        ->setEngine("swiftmailer","mail.example.com",587,"jane.doe@email.com","janeSecurePassword","tls")
        ->to("john@email.com")
        ->from("jane.doe@email.com")
        ->contentType("text/html")
        ->subject("Sample Test Mail")
        ->body("<h1>This is the mail body</h1>")
        ->attach("/home/jane/somefile.zip", "theFile.zip")
        ->send($options);
    }

}