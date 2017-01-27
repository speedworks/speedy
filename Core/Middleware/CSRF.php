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
    /**
     * URI's are defined in the array on which we do not want the CSRF security to work
     * @return array
     */
    private static function skipURI()
    {
        return [
        ];
    }

    /**
     * CSRF Verifier
     * @param $uri
     * @return bool
     * @throws Exception
     */
    public static function verifyCSRFToken($uri)
    {
        if(in_array($uri,self::skipURI()))
        {
            return true;
        }
        $csrf = new BaseCSRF();
        if(!$csrf->verifyToken())
        {
            throw new Exception("CSRF Token Mismatch");
        }
        return true;
    }
}
