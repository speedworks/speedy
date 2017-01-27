<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 01/27/17
 * Time: 06:43 PM
 */
namespace Core\Mailer;

class Mailer
{
    private $engine;
    public function __construct()
    {
        $this->engine = $_ENV['mail_engine'];
    }

}