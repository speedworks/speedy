<?php
/*
 * File for development Server
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
if ($uri !== '/' && file_exists(__DIR__.DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR.$uri))
{
    return false;
}
require_once __DIR__.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'index.php';
