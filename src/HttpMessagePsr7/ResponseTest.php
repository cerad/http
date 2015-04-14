<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  public function testStatusCode()
  {
    $response1 = new Response();
    
    $response2 = $response1->withStatus(200);
    
    $this->assertEquals(200, $response2->getStatusCode  ());
    $this->assertEquals('OK',$response2->getReasonPhrase());
    
    $response3 = $response1->withStatus(299,'CAT');
    
    $this->assertEquals('CAT',$response3->getReasonPhrase());
    
  }
}