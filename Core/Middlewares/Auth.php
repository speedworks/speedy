<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 4:43 PM
 */
namespace Core\Middlewares\Auth;

use Core\BaseClasses\BaseController;
use Core\Session\Session;
use Core\System\System;
use Core\BaseClasses\BaseAuth;

class Auth extends BaseAuth
{
    private $type = null;
    private $userName = null;
    private $password = null;
    /**
     * Auth constructor.
     */
    private function __construct($type,$userName=null,$password=null)
    {
        $this->type = $type;
        if($userName !=null && $password!=null)
        {
            $this->userName = $userName;
            $this->password = $password;
        }
    }

    /**
     * Used to define that a key based authentication is being used.
     * @return Auth
     */
    public static function Key()
    {
        $auth = new Auth("KEY");
        return $auth;
    }

    /**
     * * Used to define that a checksum based authentication is being used.
     * @return Auth
     */
    public static function Checksum()
    {
        $auth = new Auth("CHECKSUM");
        return $auth;
    }

    /**
     * * Used to define that a username/password based authentication is being used.
     * @param $userName
     * @param $password
     * @return Auth
     */
    public static function Credentials($userName, $password)
    {
        $auth = new Auth("CREDENTIALS",$userName,$password);
        return $auth;
    }

    /**
     * Authorize a user to access a particular resource
     * @param $authType
     * @param null $redirectTo
     * @return bool
     */
    public function Authorize($authType, $redirectTo=null)
    {
        $auth = new BaseAuth();
        if($authType == "session")
        {
            if(Session::has('user_id') && Session::has('user_name') && Session::has('login_time'))
            {
                $match = $auth->matchSessionData(Session::get('user_id'),Session::get('user_name'));
                if($match)
                {
                    return true;
                }
                else
                {
                    if($redirectTo!=null)
                    {
                        BaseController::redirect($redirectTo);
                    }
                    return false;
                }
            }
            else
            {
                if($redirectTo!=null)
                {
                    BaseController::redirect($redirectTo);
                }
                return false;
            }
        }
    }

    /**
     * Authenticate a user and return its details
     * @return bool|mixed
     */
    public function Authenticate() //@TODO return user details as well
    {
        $auth = new BaseAuth();
        if($this->type == "KEY")
        {
            if(!$auth->headers()->hasAuthKey())
            {
                System::GiveError(401, "Missing Auth Key");
            }
            if(!$auth->keyHasAssociatedID($auth->getAuthKey()))
            {
                System::GiveError(401, "Invalid Auth Key");
            }
        }
        else if($this->type == "CHECKSUM")
        {

        }
        else if($this->type == "CREDENTIALS")
        {
            $authDetails = $auth->matchCredentials($this->userName,$this->password);
            if($authDetails)
            {
                Session::set('user_id',$authDetails['id']);
                Session::set('user_name',$authDetails['user_name']);
                Session::set('login_time',time());
            }
            return $authDetails;
        }
    }
}