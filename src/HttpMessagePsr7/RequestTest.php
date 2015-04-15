<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Uri;
use Cerad\Component\HttpMessagePsr7\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
  public function testWithMethod()
  {
    $request1 = new Request();
    $request2 = $request1->withMethod('PUT');
    
    $this->assertEquals('PUT',$request2->getMethod());
  }
  public function testWithUri()
  {
    $uri = new Uri();
    
    $request1 = new Request();
    $request2 = $request1->withUri($uri);
    
    $this->assertEquals($uri,$request2->getUri());
  }
  public function testWithUriHost()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withHost('localhost');
    
    $request1 = new Request();
    $request2 = $request1->withUri($uri2);
    
    $this->assertEquals('localhost',$request2->getHeaderLine('host'));
  }
  public function testWithRequestTarget()
  {
    $request1 = new Request();
    $request2 = $request1->withRequestTarget('/something');
    
    $this->assertEquals('something',$request2->getRequestTarget());
  }

}