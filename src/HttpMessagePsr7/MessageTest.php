<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
  public function testProtocolVersion()
  {
    $message1 = new Message();
    $message2 = $message1->withProtocolVersion('1.1');
    
    $this->assertEquals('1.1',$message2->getProtocolVersion());
  }
  public function testWithHeader()
  {
    $message1 = new Message();
    $message2 = $message1->withHeader('Host','localhost');
    
    $this->assertEquals('localhost',$message2->getHeader('Host')[0]);
    $this->assertEquals('localhost',$message2->getHeader('host')[0]);
    
    $this->assertTrue ($message2->hasHeader('HOST'));
    $this->assertFalse($message2->hasHeader('Most'));
    
    $message3 = $message2->withHeader('Content-Type',['application/json','charset=UTF-8']);
    
    $this->assertEquals('localhost',    $message3->getHeader('Host')[0]);
    $this->assertEquals('charset=UTF-8',$message3->getHeader('Content-Type')[1]);
    
    $this->assertEquals('application/json,charset=UTF-8',$message3->getHeaderLine('Content-Type'));
    
    $headers = $message3->getHeaders();
    $this->assertEquals('charset=UTF-8',$headers['Content-Type'][1]);
  }
}