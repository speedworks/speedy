<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/22/16
 * Time: 12:51 PM
 */
namespace Core\Input;
use Core\System\System;

class Input
{
    /**
     * Checks if a User request has a particular variable in request parameters
     * @param $input
     * @return bool
     */
    public static function has($input)
    {
        return isset($_REQUEST[$input])?true:false;
    }

    /**
     * Returns the user request data
     * @param null $input
     * @return array|mixed
     */
    public static function get($input=null)
    {
        if($input == null)
        {
            return System::FilterInput($_REQUEST);
        }
        else
        {
            return System::FilterInput($_REQUEST[$input]);
        }
    }
}