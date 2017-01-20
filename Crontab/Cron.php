<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/28/16
 * Time: 11:58 PM
 * Add this line to crontab : * * * * * php <path to Project Crontab directory>/cron.php 1>> /dev/null 2>&1
 */
require __DIR__."/../Core/Classes/Crontab.php";
use Core\Crontab\Crontab;

Crontab::init();

Crontab::add('CommandExample', [
    'command'  => 'ls',
    'schedule' => '* * * * *',
    'enabled'  => true,
]);

Crontab::add('ClosureExample', [
    'closure'  => function() {
        echo "I'm a function!\n";
        return true;
    },
    'schedule' => '* * * * *',
]);
Crontab::Run();