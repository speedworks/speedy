<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/22/16
 * Time: 12:43 PM
 */
namespace Core\Cookie;
use Core\System\System;
class Cookie
{
    private function __construct()
    {

    }

    /**
     * Set a Cookie
     * @param String $cookieName
     * @param String $cookieValue [optional]
     * @param int $expiryDays [optional]
     * @param String $path [optional]
     * @param String $domain [optional]
     * @param bool $secure [optional]
     * @param bool $httpOnly [optional]
     * @return bool
     */
    public static function set($cookieName, $cookieValue=null, $expiryDays=365, $path=null, $domain=null, $secure=null, $httpOnly=null)
    {
        return setcookie($cookieName, $cookieValue, time()+(86400 * $expiryDays), $path, $domain, $secure, $httpOnly);
    }

    /**
     * Get value of a cookie or get all cookies
     * @param null String $cookieName [optional]
     * @return String/array if exists | null if cookie does not exist
     */
    public static function get($cookieName = null)
    {
        if($cookieName == null)
        {
            return $_COOKIE;
        }
        if(isset($_COOKIE[$cookieName]))
        {
            return $_COOKIE[$cookieName];
        }
        return null;
    }

    /**
     * Checks if cookie exists or not
     * @param $cookieName
     * @return bool
     */
    public static function has($cookieName)
    {
        return isset($_COOKIE[$cookieName]);
    }

    /**
     * Delete a cookie or all cookies
     * @param String $cookieName [optional]
     * @return bool
     */
    public static function delete($cookieName = null)
    {
        if($cookieName == null)
        {
            if (isset($_SERVER['HTTP_COOKIE']))
            {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie)
                {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, '', time()-1000);
                    setcookie($name, '', time()-1000, '/');
                }
            }
            return true;
        }
        return setcookie($cookieName, "", time() - 1000);
    }
}