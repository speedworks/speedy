<?php
/**
 * Created by PhpStorm.
 * @Author : Shakti Phartiyal
 * Date: 12/1/16
 * Time: 12:50 PM
 */
namespace Core\BaseClasses;

use Core\System\System;
use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;
use Core\Middleware\CSRF\CSRF;

class BaseController
{
    public $requestUri;
    public $requestMethod;
    public $requestData;
    public $rawData;

    /**
     * BaseController constructor. Sets the request data which can be accessed in all controllers extending this class.
     * @param $request
     */
    public function __construct($request)
    {
        $this->requestUri = $request[0];
        $this->requestMethod = $request[1];
        $this->requestData = $request[2];
        $this->rawData = $request[3];
        if($this->requestMethod == "POST")
        {
            CSRF::verifyCSRFToken($this->requestUri);
        }
    }


    /**
     * Returns the view from the template engine.
     * @param $view
     * @param array $arrayParams
     * @param bool $exit
     */
    public static function view($view, $arrayParams = [], $exit = false)
    {
        $loader = new Twig_Loader_Filesystem(__DIR__.'/../../Views');
        $cache = __DIR__.'/../../Storage/Cache/Views';
        if(!$_ENV['cache_template'])
        {
            $cache = false;
        }
        $twig = new Twig_Environment($loader, array(
            'cache' => $cache,
            'auto_reload' => $_ENV['debug'],
        ));
        $csrfFunction = new Twig_Function('csrf_token', function () {
            $csrf = new BaseCSRF();
            return $csrf->generateToken();
        });
        $twig->addFunction($csrfFunction);
        $assetFunction = new Twig_Function('asset', function () {
            $asset = ltrim(isset(func_get_args()[0])?func_get_args()[0]:"","/");
            return "//".$_SERVER['HTTP_HOST']."/".$asset;
        });
        $twig->addFunction($assetFunction);
        $template = $twig->load($view.'.vu.php');
        if($exit === true)
        {
            echo $template->display($arrayParams);
            exit(1);
        }
        return $template->display($arrayParams);
    }

/*    public static function view($viewName899lkks, $inArray65fyghed3s=null, $exit=false)
    {
        if($inArray65fyghed3s!=null)
        {
            foreach (array_keys($inArray65fyghed3s) as $variable65fyghed3s)
            {
                $$variable65fyghed3s=$inArray65fyghed3s[$variable65fyghed3s];
            }
        }
        $viewFileLoaded12wes=include __DIR__."/../../Views/".$viewName899lkks.".vu.php";
        $viewFileLoaded12wes=substr($viewFileLoaded12wes, 0,-1);
        if($exit) //only for error pages
        {
            echo $viewFileLoaded12wes;
            exit(1);
        }
        return $viewFileLoaded12wes;
    }*/

    /**
     * Redirects a user to a particular URL.
     * @param $path
     */
    public static function redirect($path)
    {
        $path="location: ".$path;
        header($path);
    }

    /**
     * Returns the JSON value of the passed array
     * @param $inArray
     */
    public static function json($inArray)
    {
        System::GiveJSON($inArray);
    }

    /**
     *Brings the user back to the requesting page.
     */
    public static function back()
    {
        $ref = "location: ".$_SERVER['HTTP_REFERER'];
        header($ref);
    }
}