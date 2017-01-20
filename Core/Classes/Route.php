<?php
/**
 * Created by PhpStorm.
 * @Author : Shakti Phartiyal
 * Date: 11/28/16
 * Time: 2:05 PM
 */
namespace Core\Route;

class Route
{
    protected static $URLS = array();

    /**
     * Handle GET Request
     * @param $path
     * @param $classedFunction
     */
    public static function get($path, $classedFunction)
    {
        self::$URLS['GET'][$path]=$classedFunction;
    }

    /**
     * Handle POST Request
     * @param $path
     * @param $classedFunction
     */
    public static function post($path, $classedFunction)
    {
        self::$URLS['POST'][$path]=$classedFunction;
    }

    /**
     * Handle PUT request
     * @param $path
     * @param $classedFunction
     */
    public static function put($path, $classedFunction)
    {
        self::$URLS['PUT'][$path]=$classedFunction;
    }

    /**
     * Handle patch REQUEST
     * @param $path
     * @param $classedFunction
     */
    public static function patch($path, $classedFunction)
    {
        self::$URLS['PATCH'][$path]=$classedFunction;
    }

    /**
     * Handle DELETE request
     * @param $path
     * @param $classedFunction
     */
    public static function delete($path, $classedFunction)
    {
        self::$URLS['DELETE'][$path]=$classedFunction;
    }

    /**
     * Handle HEAD request
     * @param $path
     * @param $classedFunction
     */
    public static function head($path, $classedFunction)
    {
        self::$URLS['HEAD'][$path]=$classedFunction;
    }

    /**
     * Handle OPTIONS request
     * @param $path
     * @param $classedFunction
     */
    public static function options($path, $classedFunction)
    {
        self::$URLS['OPTIONS'][$path]=$classedFunction;
    }

    /**
     * Handle any request method
     * @param $path
     * @param $classedFunction
     */
    public static function any($path, $classedFunction)
    {
        self::get($path,$classedFunction);
        self::post($path,$classedFunction);
        self::put($path,$classedFunction);
        self::patch($path,$classedFunction);
        self::delete($path,$classedFunction);
        self::head($path,$classedFunction);
        self::options($path,$classedFunction);
    }
}
