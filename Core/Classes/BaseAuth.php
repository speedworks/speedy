<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/1/16
 * Time: 12:29 PM
 */
namespace Core\BaseClasses;

use Core\DB\DB;
use Core\System\System;

class BaseAuth
{
    private $headers;

    /**
     * Sets headers in Base Auth object
     * @return BaseAuth Object
     *
     */
    protected function headers()
    {
        $this->headers = System::FilterInput(getallheaders());
        return $this;
    }

    /**
     * Checks if Auth key exists in the request headers
     * @return bool
     */
    protected function hasAuthKey()
    {
        return (array_key_exists("Auth-Key",$this->headers) && $this->headers['Auth-Key'] != "");
    }

    /**
     * Returns the auth key
     * @return mixed
     */
    protected function getAuthKey()
    {
        return $this->headers['Auth-Key'];
    }

    /**
     * Checks if the API Keys in the request has any associated users with it in DB
     * @param $key
     * @return bool
     */
    protected function keyHasAssociatedID($key)
    {
        $db = DB::ADO();
        $rs = $db->Execute('select user_id from api_keys WHERE api_key = ? AND status = ? ',[$key, 1]);
        $associatedID = $rs->fetchRow();
        if($associatedID == "")
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks whether the request has a checksum in headers
     * @return bool
     */
    protected function hasChecksum()
    {
        return (array_key_exists("Auth-Ch", $this->headers) && $this->headers['Auth-Ch'] != "");
    }

    /**
     * Returns checksum
     * @return mixed
     */
    protected function getChecksum()
    {
        return $this->headers['Auth-Ch'];
    }

    /**
     * Matches the supplied user name and password with the DB Credentials
     * @param $userName
     * @param $password
     * @return bool | mixed user Data
     */
    protected function matchCredentials($userName, $password)
    {
        $db = DB::ADO();
        $rs = $db->Execute('SELECT * from users WHERE user_name = ? AND password = ? AND status = 1',[$userName, System::GenerateHash($password)]);
        $associateduser = $rs->getAssoc();
        if(count($associateduser) < 1)
        {
            return false;
        }
        else
        {
            foreach ($associateduser as $assocUser)
            {
                return $assocUser;
            }
        }
    }

    /**
     * Checks if the data in session matches the user data
     * @param $uid
     * @param $uname
     * @return bool | mixed
     */
    protected function matchSessionData($uid, $uname)
    {
        $db = DB::ADO();
        $rs = $db->Execute('SELECT * from users WHERE id = ? AND user_name = ? AND status = 1',[$uid, $uname]);
        $associateduser = $rs->getAssoc();
        if(count($associateduser) < 1)
        {
            return false;
        }
        else
        {
            foreach ($associateduser as $assocUser)
            {
                return $assocUser;
            }
        }
    }

}