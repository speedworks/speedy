<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/29/16
 * Time: 1:01 PM
 */
namespace Core\Crontab;
require __DIR__.'/../Plugins/autoload.php';
use Jobby\Jobby;

class Crontab
{
    private static $jobHandler;

    /**
     * CronJob initialization function
     */
    public static function init()
    {
        self::$jobHandler = new Jobby();
    }

    /**
     * Adds a new command to the execution queue
     * @param $jobName
     * @param $options
     */
    public static function add($jobName, $options)
    {
        if(!array_key_exists('output',$options))
        {
            $options['output'] = __DIR__.'/../../Crontab/cronLog.log';
        }
        self::$jobHandler->add($jobName, $options);

    }

    /**
     * Command Executor
     */
    public static function Run()
    {
        self::$jobHandler->run();
    }
}