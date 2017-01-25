<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 1/24/17
 * Time: 1:55 PM
 */
namespace Core\BaseClasses;

class BaseCSRF
{
    public function __construct()
    {
        session_start();
    }

    public function generateToken()
    {
        if(empty($_SESSION['token']))
        {
            if(function_exists('mcrypt_create_iv'))
            {
                $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
            }
            else
            {
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
        return $_SESSION['token'];
    }

    public function setToken()
    {

    }

    public function provideToken()
    {

    }

    public function verifyToken()
    {
        if(isset($_POST['csrf_token']) && !empty($_POST['csrf_token']) && isset($_SESSION['csrf_token']))
        {
            if(hash_equals($_SESSION['token'], $_POST['csrf_token']))
            {
                return true;
            }
        }
        else
        {
            return false;
        }
        //@TODO : $twigEnv->addFunction(new \Twig_SimpleFunction());
    }
}