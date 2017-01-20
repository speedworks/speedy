<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 11:04 AM
 */
namespace Core\System;

use Core\BaseClasses\BaseController;

class System
{
    /**
     * System constructor.
     */
    public function __construct()
    {

    }

    /**
     * Debug and die function prints debug data. If second param is true it terminates the script.
     * @param $inData
     * @param bool $die (terminates the script if true, defaults to false)
     */
    public static function DBD($inData, $die=false) //DEBUG AND DIE
    {
        if(is_array($inData))
        {
            echo "<pre><br/>\n";
            print_r($inData);
            echo "</pre><br/>\n";
        }
        else
        {
            if(gettype($inData) == 'object')
            {
                echo "<br/>\n<pre>";
                var_dump($inData);
                echo "<br/>\n</pre>";
            }
            else
            {
                echo "<br/>\n".$inData."<br/>\n";
            }
        }
        if($die==1 || $die === true)
        {
            die;
        }
    }

    /**
     * Make log Files
     * @param $logdir
     * @param $filename
     * @param $logstr
     */
    public static function MakeLog($logdir, $filename, $logstr)
    {
        $logstr .= "\n";
        if(is_dir($logdir.date('Y')))
        {
            if(is_dir($logdir.date('Y').'/'.date('m')))
            {
                if(!is_dir($logdir.date('Y').'/'.date('m').'/'.date('d')))
                {
                    mkdir($logdir.date('Y').'/'.date('m').'/'.date('d'),0777);
                }
            }
            else
            {
                mkdir($logdir.date('Y').'/'.date('m').'/'.date('d'),0777,true);
            }
        }
        else
        {
            mkdir($logdir.date('Y').'/'.date('m').'/'.date('d'),0777,true);
        }
        $fpd = fopen($logdir.date('Y').'/'.date('m').'/'.date('d').'/'.$filename,"a+");
        fwrite($fpd,$logstr,strlen($logstr));
        fclose($fpd);
    }

    /**
     * Method to Encrypt & Decrypt Data Second Parameter wid D required for decryption only.
     * @param $inData
     * @param string $opt
     * @return string
     */
    public static function Crypto($inData, $opt="D")
    {
        $outData="";
        $iv = "r@nD0mKey#osekj%^876ghjkjb5dDdf8";
        if($opt=='E')
        {
            // Encrypt $string
            $outData = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $_ENV['app_key'], $inData, MCRYPT_MODE_CBC, $iv));
        }
        else
        {
            // Decrypt $string
            $outData = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_ENV['app_key'], base64_decode($inData), MCRYPT_MODE_CBC, $iv);
        }
        return $outData;
    }


    /**
     * Generate a non reversible secure hash
     * @param $plainPassword
     * @param string $saltKey (Optional)
     * @return string
     */
    public static function GenerateHash($plainPassword, $saltKey=null)
    {
        $salt = isset($_ENV['app_key'])?$_ENV['app_key']:"$#@kT!@p!7ram3w0rk";
        $salt = $saltKey == null ? $salt : $saltKey;
        $hash = hash_hmac('sha256', $plainPassword, $salt, false);
        return $hash;
    }


    /**
     * Generates a secure non reversible API Key
     * @param $digestString
     * @return string
     */
    public static function GenerateAPIKey($digestString)
    {
        $digestString.='|'.$_ENV['app_key'].'|'.microtime(true);
        $hash = hash_hmac('sha1', $digestString, microtime(true), false);
        return $hash;
    }

    /**
     * Generates a small salted CRC32B Secret
     * @param $digestString
     * @return string
     */
    public static function GenerateSecret($digestString)
    {
        $digestString.='|'.$_ENV['app_key'].'|'.microtime(true);
        $hash = hash_hmac('crc32b', $digestString, microtime(true), false);
        return $hash;
    }


    /**
     * Escape Strings
     * @param $string
     * @return mixed
     */
    public static function escapeString($string)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
        return str_replace($search, $replace, $string);
    }

    /**
     * Filters user input
     * @param $inData
     * @param bool $keepHTML
     * @return array|mixed
     */
    public static function FilterInput($inData, $keepHTML=false)
    {
        if(is_array($inData))
        {
            $outData=array();
            foreach($inData as $key => $value)
            {
                if(!$keepHTML)
                {
                    $value=filter_var(trim($value), FILTER_SANITIZE_STRING);
                }
                else
                {
                    $value=trim(htmlentities(strip_tags($value)));
                    if (get_magic_quotes_gpc())
                    {
                        $value = stripslashes($value);
                    }
                }
                $outData[$key]=System::escapeString($value);
            }
        }
        else
        {
            $inData=trim($inData);
            if(!$keepHTML)
            {
                $inData=filter_var($inData, FILTER_SANITIZE_STRING);
            }
            else
            {
                $inData=trim(htmlentities(strip_tags($inData)));
                if (get_magic_quotes_gpc())
                {
                    $inData = stripslashes($inData);
                }
            }
            $inData = System::escapeString($inData);
            $outData=$inData;
        }
        return $outData;
    }

    /**
     * Give JSON formatted response suited for APIS
     * @param $inData
     */
    public static function GiveResponse($inData)
    {
        header('Content-Type: application/json');
        $outData=array('errStatus' =>0,
            'code'=>200,
            'message'=>'success',
            'data'=>$inData
        );
        $outData=json_encode($outData);
        echo $outData;
        exit();
    }

    /**
     * Give JSON response suited for APIS
     * @param $inData
     */
    public static function GiveJSON($inData)
    {
        header('Content-Type: application/json');
        $outData=json_encode($inData);
        echo $outData;
        exit();
    }

    /**
     * Give JSON formatted error response suited for APIS
     * @param $errorCode
     * @param $errorMessage
     */
    public static function GiveError($errorCode, $errorMessage)
    {
        if($errorCode==400)
        {
            header("HTTP/1.0 400 Bad Request");
        }
        else if($errorCode==401)
        {
            header("HTTP/1.0 401 Unauthorized");
        }
        else if($errorCode==403)
        {
            header("HTTP/1.0 403 Forbidden");
        }
        else if($errorCode==404)
        {
            header("HTTP/1.0 404 Not Found");
        }
        else if($errorCode==405)
        {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        else if($errorCode==406)
        {
            header("HTTP/1.0 406 Not Acceptable");
        }
        else if($errorCode==503)
        {
            header("HTTP/1.0 503 Service Unavailable");
        }
        else if($errorCode==500)
        {
            header("HTTP/1.0 500 Server Error");
        }
        if(isset($_ENV['error_pages'][$errorCode]) && $_ENV['error_pages'][$errorCode] != "")
        {
            return BaseController::view($_ENV['error_pages'][$errorCode],[],true);
        }
        header('Content-Type: application/json');
        $outData=array('errStatus' =>1,
            'code'=>$errorCode,
            'message'=>$errorMessage);
        $outData=json_encode($outData);
        echo $outData;
        exit();
    }
}