<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\ServerRequest;

class ServerRequestTest extends \PHPUnit_Framework_TestCase
{
  public function testConstruct()
  {
    $request = new ServerRequest();
  }
  public function testWithAttribute()
  {
    $request1 = new ServerRequest();
    $request2 = $request1->withAttribute('xxx','zzz');
    
    $this->assertEquals('zzz',$request2->getAttribute('xxx'));
    $this->assertEquals(1,count($request2->getAttributes()));
  }
  public function testWithoutAttribute()
  {
    $request1 = new ServerRequest();
    $request2 = $request1
      ->withAttribute('one','one')
      ->withAttribute('two','two')
      ->withAttribute('thr','thr');
    
    $request3 = $request2->withoutAttribute('two');
    
    $this->assertEquals('one',  $request3->getAttribute('one'));
    $this->assertEquals('XXX',  $request3->getAttribute('two','XXX'));
    $this->assertEquals(2,count($request3->getAttributes()));
    
  }
}