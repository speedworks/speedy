<?php
/**
 * Created by PhpStorm.
 * @Author: Shakti Phartiyal
 * Date: 12/22/16
 * Time: 7:02 PM
 */
require __DIR__.'/../Core/Bridge.php';
use PHPUnit\Framework\TestCase;

class FlowTest extends TestCase
{
    public function testRoute()
    {
        $route = new \Core\Route\Route();
        $this->assertEquals("object", gettype($route)); //Object signifies no error
    }
    public function testBaseController()
    {

        $baseController = new \Core\BaseClasses\BaseController(null);
        $this->assertEquals("object", gettype($baseController)); //Object signifies no error
    }

    public function testFlow()
    {
    }
}