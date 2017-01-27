<?php
/**
 * Created by PhpStorm.
 * User: shakti
 * Date: 1/27/17
 * Time: 10:45 AM
 */
namespace Core\Middleware\CSRF;
use Core\BaseClasses\BaseCSRF;
use Exception;

class CSRF
{
    private static function skipURI()
    {
        return [

        ];
    }
    public static function verifyCSRFToken($uri)
    {
        if(in_array($uri,self::skipURI()))
        {
            return true;
        }
        $base = new BaseCSRF();
        if(!$base->verifyToken())
        {
            throw new Exception("CSRF Token Mismatch");
        }
        return true;
    }
}