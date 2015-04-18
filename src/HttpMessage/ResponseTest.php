<?php
namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponseJson;
use Cerad\Component\HttpMessage\ResponseRedirect;
use Cerad\Component\HttpMessage\ResponsePreflight;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  public function test1()
  {
    $response = new Response();
    $this->assertEquals(200,$response->getStatusCode());
    $this->assertEquals('no-cache',$response->getHeaderLine('Cache-Control'));
  }
  /* ====================================
   * 
   * Added by server
     Connection:close
     Host:localhost:8080
     X-Powered-By:PHP/5.5.11
   * 
   * Added by Response
     Cache-Control:no-cache
     Content-type:text/html
     Date:Wed, 08 Apr 2015 18:43:43 GMT
   */
  public function testResponse()
  {
    $content = 'HTML Content';
    
    $response = new Response($content);
    
    $this->assertEquals('no-cache',$response->getHeaderLine('Cache-Control'));
    
    $this->assertEquals('text/html;charset=UTF-8',$response->getHeaderLine('Content-Type'));
    
    $this->assertEquals(29,strlen($response->getHeaderLine('Date')));
    
    $this->assertEquals($content,$response->getParsedBody());
  }
  public function testResponseJson()
  {
    $item = ['name' => 'Art'];
    
    $response = new ResponseJson($item);
    
    $itemx = $response->getParsedBody();
    $this->assertEquals('Art',$itemx['name']);
    
    $contentType = $response->getHeaderLine('Content-Type');
    $this->assertTrue(strpos($contentType,'json') !== false);
  }
  public function testResponsePreflight()
  {
    // Always have an origin
    $response = new ResponsePreflight('localhost');
    $this->assertEquals('localhost',$response->getHeaderLine('Access-Control-Allow-Origin'));
  }
  public function testResponseRedirect()
  {
    // Always have an origin
    $response = new ResponseRedirect('localhost/xxx');
    
    $this->assertEquals(302,$response->getStatusCode());
    $this->assertEquals('localhost/xxx',$response->getHeaderLine('Location'));
  }
}
