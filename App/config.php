<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 5:03 PM
 */
return [
    'app_key' => '4r!wDe@uY*%mn1286afde#@O0+kUse1*', //ONCE CREATED YOU SHOULD PROBABLY NOT CHANGE IT
    'debug' => true, //DEBUG MODE
    'timezone' => 'Asia/Kolkata', //DEFAULT TIMEZONE
    'cors' => '*', // Cross-Origin Resource Sharing (CORS)
    'DB_TYPE' => 'mysql', //CAN BE REMOVED LATER
    'DB_ADO_DRIVER' => 'mysqli', //DB DRIVER
    'DB_SERVER' => 'localhost', //DB SERVER HOST
    'DB_PORT' => 3306, //DB PORT
    'DB_USER' => 'root', //DB USER
    'DB_PASSWORD' => 'password', //DB PASSWORD
    'DB_NAME' => 'api', //DB NAME
    'DB_CHARSET' => 'utf8', //DB DEFAULT CHARACTER SET
    'DB_FETCH_MODE' => PDO::FETCH_ASSOC, //DB FETCH MODE
    'session_prefix' => 'spf_', //APPLICATION COOKIES PREFIX
    'session_life' => 604800, // in seconds
    'cache_template' => true, //ALLOW TEMPLATE CACHING FOR SPEED
    'error_pages' => [
        '404' => '404' //404 ERROR Page
    ],
    'mail_engine' => 'PHP', //PHP,SWIFTMAILER,MAILCHIMP
];