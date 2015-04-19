<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Request;

class RequestLineTest extends \PHPUnit_Framework_TestCase
{ 
  /**
   *  expectedException PHPUnit_Framework_Error
   */
   public function testConstruct()
  {
    $request = new Request(); // Empty is okay for now
  }
  public function testRequestUrl()
  {
    $uriString = 'https://user:pass@api.zayso.org:8080/referees?project=ng2016&title=NG+2016#42';
    
    $requestLine = 'POSt ' . $uriString . ' HTTP/1.1';
    
    $request = new Request($requestLine);
    
    $this->assertEquals('POST',         $request->getMethod());
    $this->assertEquals('1.1',          $request->getProtocolVersion());
    $this->assertEquals('/referees',    $request->getUri()->getPath());
    $this->assertEquals('api.zayso.org',$request->getUri()->getHost());
    $this->assertEquals('api.zayso.org',$request->getHeaderLine('Host'));
    
    $queryParams = $request->getQueryParams();
    $this->assertEquals('NG 2016',$queryParams['title']);
    
    $this->assertEquals('https://user:pass@api.zayso.org:8080/',$request->getBaseHrefAbs());
  }
  public function testRequestHeader()
  {
    $headers = 
    [
      'Content-Type' => 'application/json',
      'Accept'       => 'whatever',
    ];
    $requestLine = 'POST /referees';
   
    $request = new Request($requestLine,$headers);
    
    $this->assertEquals('whatever',$request->getHeaderLine('Accept'));
  }
  public function testRequestPost()
  {
    $requestLine = 'POST /referees';
    $headers = 
    [
      'Content-Type' => 'application/json',
    ];
    $data = ['name' => 'Art H','roles' => ['Developer']];
    
    $request = new Request($requestLine,$headers,json_encode($data));
        
    $item = $request->getParsedBody();
    
    $this->assertEquals('Art H',$item['name']);
  }
}