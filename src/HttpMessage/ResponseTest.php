<?php
namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponseJson;

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
    
    $this->assertEquals($content,$response->getContent());
  }
  public function testResponseJson()
  {
    $item = ['name' => 'Art'];
    
    $response = new ResponseJson($item);
    
    $itemx = json_decode($response->getContent(),true);
    $this->assertEquals('Art',$itemx['name']);
    
    $contentType = $response->getHeaderLine('Content-Type');
    $this->assertTrue(strpos($contentType,'json') !== false);
    
  }
}
