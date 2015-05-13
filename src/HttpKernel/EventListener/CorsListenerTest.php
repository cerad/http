<?php

namespace Cerad\Component\HttpKernel\EventListener;

use Cerad\Component\HttpKernel\Event\KernelRequestEvent;
use Cerad\Component\HttpKernel\Event\KernelResponseEvent;

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponsePreflight;

class CorsListenerTest extends \PHPUnit_Framework_TestCase
{
  public function testPreflight()
  {
    $listener = new CorsListener();
    $headers = 
    [
      'Origin' => 'localhost',
      'Access-Control-Request-Method' => 'GET',
    ];
    
    $request = new Request('OPTIONS /resource',$headers);
    
    $event = new KernelRequestEvent($request);
    
    $listener->onKernelRequest($event);
    
    $response = $event->getResponse();

    $this->assertTrue($response instanceof ResponsePreflight);
    
    $this->assertEquals('localhost',$response->getHeaderLine('Access-Control-Allow-Origin'));
  }
  public function testCors()
  {
    $listener = new CorsListener();
    $headers = 
    [
      'Origin' => 'localhost',
    ];
    $request  = new Request('GET /resource',$headers);
    $response = new Response();
    
    $event = new KernelResponseEvent($request,$response);
    
    $listener->onKernelResponse($event);
    
    $response = $event->getResponse();
    
    $this->assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
    
    $this->assertEquals('localhost',$response->getHeaderLine('Access-Control-Allow-Origin'));
    
  }
}