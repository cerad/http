<?php
namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Request;

class RequestDataTest extends \PHPUnit_Framework_TestCase
{ 
  public function testMethodProtocolVersion()
  {
    $server =
    [
      'REQUEST_METHOD'  => 'put',
      'SERVER_PROTOCOL' => 'HTTP/1.0',
    ];
    $request = new Request($server);
    
    $this->assertEquals('PUT',$request->getMethod());
    $this->assertEquals('1.0',$request->getProtocolVersion());
    
  }
  public function testRequestUri()
  {
    $server =
    [
      'REQUEST_URI' => '/xxx?project=ng2016&title=NG+2016',
    ];
    $request = new Request($server);
    
    $this->assertEquals('/xxx',$request->getUri()->getPath());
    $this->assertEquals('/xxx',$request->getRoutePath());
    
    $queryParams = $request->getQueryParams();
    $this->assertEquals('NG 2016',$queryParams['title']);
  }
  public function testHeaders()
  {
    $server =
    [
      'REQUEST_URI' => '/xxx?project=ng2016&title=NG+2016',
      'HTTP_HOST'   => 'api.zayso.local'
    ];
    $request = new Request($server);
    
  //$this->assertEquals('api.zayso.local',$request->getUri()->getHost());
    $this->assertEquals('api.zayso.local',$request->getHeaderLine('Host'));
  }
  /* ======================================================
   * Bunch of different cases for baseHref and routePath
   */
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
  /* =========================================
   * http://localhost:8001/api.php/referees
   * Script Name  /api.php
   * Request Path /api.php/referees
   * 
   * http://localhost:8001/referees
   * Script Name  /referees
   * Request Path /referees
   * 
   * TODO: Test with /web/referees
   */
  public function testScriptNameMatchesRequestPath()
  {
    $server = 
    [
    // http://localhost:8001/referees
      'SCRIPT_NAME' => '/referees',
      'REQUEST_URI' => '/referees',
    ];
    $request = new Request($server);
    $this->assertEquals('/',         $request->getBaseHref());
    $this->assertEquals('/referees', $request->getRoutePath());
  }
}