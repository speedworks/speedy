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
    /**
     * Initialize CSRF
     * BaseCSRF constructor.
     */
    public function __construct()
    {
        session_name("sea-surf");
        session_start();
    }

    /**
     * Generate and set the CSRF Token
     * @return string
     */
    public function generateToken()
    {
        if(function_exists('mcrypt_create_iv'))
        {
            $_SESSION['csrf_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        }
        else
        {
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify the submitted Token
     * @return bool
     */
    public function verifyToken()
    {
        if(isset($_POST['csrf_token']) && !empty($_POST['csrf_token']) && isset($_SESSION['csrf_token']))
        {
            if(hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']))
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
}