<?php
/**
 * Created by Creator.
 * @Author : Shakti Phartiyal
 * Date: 26/Dec/2016
 * Time: 14:42:12
 */
namespace Controllers;
use Core\BaseClasses\BaseController;

class WelcomeController extends BaseController
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function welcomeUser()
    {
        return view("welcome");
    }
}
