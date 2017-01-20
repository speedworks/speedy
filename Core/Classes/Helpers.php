<?php
use Core\BaseClasses\BaseController;
use Core\System\System;

/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 1/1/17
 * Time: 9:42 PM
 */
//HELPER FUNCTION DEFINITIONS
function dbd($arg0,$arg1=0)
{
    System::DBD($arg0,$arg1);
}
function redirect($path)
{
    BaseController::redirect($path);
}
/*function view($viewName899lkks, $inArray65fyghed3s=null, $exit=false)
{
    BaseController::view($viewName899lkks, $inArray65fyghed3s=null, $exit=false);
}*/
function json($inArray)
{
    BaseController::json($inArray);
}
function back()
{
    BaseController::back();
}

function view($view,$arrayParams = [])
{
    BaseController::view($view,$arrayParams);
}