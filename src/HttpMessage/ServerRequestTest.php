<?php
namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Request;

class ServerRequestTest extends \PHPUnit_Framework_TestCase
{ 
  public function test1()
  {
    $server = 
    [
   // http://localhost:8080/
      'SCRIPT_NAME' => '/',
      'REQUEST_URI' => '/',
    ];
    $request = new Request($server);
    $this->assertEquals('/',$request->getBaseHref());
    $this->assertEquals('/',$request->getRoutePath());
  }
  public function test2()
  {
    $server = 
    [
    // http://localhost:8080/app.php
      'SCRIPT_NAME' => '/app.php',
      'REQUEST_URI' => '/app.php',
    ];
    $request = new Request($server);
    $this->assertEquals('/',$request->getBaseHref());
    $this->assertEquals('/',$request->getRoutePath());
  }
  public function test3()
  {
    $server = 
    [
    // http://localhost:8080/app.php/xxx
      'SCRIPT_NAME' => '/app.php',
      'REQUEST_URI' => '/app.php/xxx',
    ];
    $request = new Request($server);
    $this->assertEquals('/',   $request->getBaseHref());
    $this->assertEquals('/xxx',$request->getRoutePath());
  }
  public function test4()
  {
    $server = 
    [
    // http://localhost:8080/app.php/xxx?role=admin
      'SCRIPT_NAME' => '/app.php',
      'REQUEST_URI' => '/app.php/xxx?role=admin',
    ];
    $request = new Request($server);
    $this->assertEquals('/',   $request->getBaseHref());
    $this->assertEquals('/xxx',$request->getRoutePath());
  }
  public function test5()
  {
    $server = 
    [
    // http://localhost:8081/web/app.php/xxx?role=admin
      'SCRIPT_NAME' => '/web/app.php',
      'REQUEST_URI' => '/web/app.php/xxx?role=admin',
    ];
    $request = new Request($server);
    $this->assertEquals('/web/',$request->getBaseHref());
    $this->assertEquals('/xxx', $request->getRoutePath());
  }
  public function test6()
  {
    $server = 
    [
    // http://http.zayso.local/xxx?role=admin
      'SCRIPT_NAME' => '/app.php',
      'REQUEST_URI' => '/xxx?role=admin',
    ];
    $request = new Request($server);
    $this->assertEquals('/',    $request->getBaseHref());
    $this->assertEquals('/xxx', $request->getRoutePath());
  }
}