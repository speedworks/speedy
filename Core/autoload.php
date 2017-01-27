<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 10:11 AM
 */
/*function __autoload($class) //DEPRECATED
{
    $parts = explode('\\', $class);
    $filename = __DIR__ . '/Classes/' .end($parts) . '.php';
    if (file_exists($filename))
    {
        require $filename;
    }
    else
    {
        require __DIR__ . '/Plugins/autoload.php';
    }
}*/
function SPAutoload($class)
{
    $parts = explode('\\', $class);
    $filename = __DIR__ . '/Classes/' .end($parts) . '.php';
    if (file_exists($filename))
    {
        require $filename;
    }
}
spl_autoload_register('SPAutoload');
function ControllerAutoload($class)
{
    $parts = explode('\\', $class);
    $filename = __DIR__ . '/../Controllers/' .end($parts) . '.php';
    if (file_exists($filename))
    {
        require $filename;
    }
}
spl_autoload_register('ControllerAutoload');
function MiddlewareAutoload($class)
{
    $parts = explode('\\', $class);
    $filename = __DIR__ . '/Middleware/' .end($parts) . '.php';
    if (file_exists($filename))
    {
        require $filename;
    }
}
spl_autoload_register('MiddlewareAutoload');
require 'Plugins/autoload.php';