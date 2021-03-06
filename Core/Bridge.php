<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 10:49 AM
 */
require __DIR__.'/autoload.php';
use Core\System\System;
use Core\Route\Route;
use Core\BaseClasses\BaseController;
class Bridge extends Route
{
    public function __construct()
    {
        require (__DIR__ . "/Classes/Helpers.php");
        require (__DIR__ . "/../App/Routes.php");
        header('Access-Control-Allow-Origin: '.$_ENV['cors']);
    }
    public static function Pass($requestUri, $requestMethod, $requestData, $rawData)
    {
        if(!array_key_exists($requestMethod,Route::$URLS))
        {
            System::GiveError(405,"Method not allowed");
        }
        if(!array_key_exists($requestUri,Route::$URLS[$requestMethod]))
        {
            System::GiveError(404,'Requested path not found');
        }
        $opCode=Route::$URLS[$requestMethod][$requestUri];
        if(gettype($opCode) == "object")
        {
            $opCode();
            exit(0);
        }
        $opCode=explode('@', $opCode);
        if(class_exists("Controllers\\".$opCode[0]))
        {
            $clsName='Controllers\\'.$opCode[0];
            $cObject=new $clsName([$requestUri, $requestMethod, $requestData, $rawData]);
            unset($clsName);
            if(method_exists($cObject, $opCode[1]))
            {
                $method=$opCode[1];
                return $cObject->$method();
            }
            else
            {
                System::GiveError(500,'Method '.$opCode[1].' does not exist');
            }
        }
        else
        {
            System::GiveError(500,'Class '.$opCode[0].' not found');
        }
    }
}