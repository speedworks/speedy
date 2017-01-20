<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 11/24/16
 * Time: 10:11 AM
 */
function loadConfig()
{
    $config = require_once __DIR__ . '/../App/config.php';
    foreach ($config as $key => $value) {
        $_ENV[$key] = $value;
    }
}
loadConfig();
set_error_handler("ErrorHandler");
set_exception_handler("ExceptionHandler");
/**
 * Handle Application Exceptions
 * @param $exception
 */
function ExceptionHandler($exception)
{
    header("HTTP/1.0 500 Server Error");
    if($_ENV['debug'] == true)
    {
        echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
        echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#f77878; margin:10px 0px;text-align:center;">ERROR ENCOUNTERED</div>';
        echo "<b>UNCAUGHT EXCEPTION ::</b> " . $exception->getMessage();
        echo "<br/>";
        echo "<b>IN FILE ::</b> " . $exception->getFile();
        echo "<br/>";
        echo "<b>ON LINE ::</b> " . $exception->getLine();
        echo "<br/>";
        $trace = str_replace('#', "<br/>&#187;", $exception->getTraceAsString());
        $trace = str_replace(': ','<br/>&#187;&#187;',$trace);
        echo "<b>STACK TRACE ::</b> ".$trace;
        echo '</div>';
    }
    else
    {
        echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
        echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#f77878; margin:10px 0px;text-align:center;">';
        echo "OOP's we crashed onto something !";
        echo '</div></div>';
        error_log($exception);
    }
}

/**
 * Handle Application Errors
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 * @return bool|void
 */
function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno))
    {
        return;
    }
    header("HTTP/1.0 500 Server Error");
    if($_ENV['debug'] == true)
    {
        switch ($errno)
        {
            case E_ERROR:
                echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
                echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#f77878; margin:10px 0px;text-align:center;">Error Encountered</div>';
                echo "<b>ERROR ::</b> " . $errstr;
                echo "<br/>";
                echo "<b>IN FILE ::</b> " . $errfile;
                echo "<br/>";
                echo "<b>ON LINE ::</b> " . $errline;
                echo '</div>';
                break;
            case E_WARNING:
                echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
                echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#f4ff95; margin:10px 0px;text-align:center;">Error Encountered</div>';
                echo "<b>WARNING ::</b> " . $errstr;
                echo "<br/>";
                echo "<b>IN FILE ::<b> " . $errfile;
                echo "<br/>";
                echo "<b>ON LINE ::<b> " . $errline;
                echo '</div>';
                break;
            case E_PARSE:
                echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
                echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#baedf3; margin:10px 0px;text-align:center;">Error Encountered</div>';
                echo "<b>COMPILATION ERROR ::</b> " . $errstr;
                echo "<br/>";
                echo "<b>IN FILE ::</b> " . $errfile;
                echo "<br/>";
                echo "<b>ON LINE ::</b> " . $errline;
                echo '</div>';
                break;
            case E_NOTICE:
                echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
                echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#d8b4ff; margin:10px 0px;text-align:center;">Error Encountered</div>';
                echo "<b>NOTICE ::</b> " . $errstr;
                echo "<br/>";
                echo "<b>IN FILE ::</b> " . $errfile;
                echo "<br/>";
                echo "<b>ON LINE ::</b> " . $errline;
                echo '</div>';
                break;
            default:
                echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
                echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#b4ffd5; margin:10px 0px;text-align:center;">Error Encountered</div>';
                echo "<b>UNKNOWN EXCEPTION ::</b> " . $errno;
                echo "<br/>";
                echo "<b>DETAILS ::</b> " . $errstr;
                echo '</div>';
                break;
        }
    }
    else
    {
        $errDetails=array(
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline
        );
        echo '<div style="max-width: 600px;background-color: #ddd; margin:10px auto; border: 1px solid #000000;text-align: left; padding:10px 40px 10px 40px; word-break: break-all;">';
        echo '<div style="padding-top:6px;width:100%; min-height:30px; background:#f77878; margin:10px 0px;text-align:center;">';
        echo "OOP's we crashed onto something !";
        echo '</div></div>';
        error_log(json_encode($errDetails));
    }
    die;
    return true;
}
header("X-Powered-By: SpeedyPF");
require(__DIR__ . "/../Core/Bridge.php");
new Bridge();
$requestUri = parse_url($_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$rawData = file_get_contents('php://input');
$requestData = $_REQUEST;
if($requestUri['path']!="/")
{
    $requestUri['path']=rtrim($requestUri['path'],"/");
}
Bridge::Pass($requestUri['path'], $requestMethod, $requestData, $rawData);