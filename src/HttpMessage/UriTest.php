<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Uri;

class UriTest extends \PHPUnit_Framework_TestCase
{
  public function testUri()
  {
    $uriString = 'https://user:pass@api.zayso.org:8080/referees?project=ng2016&title=NG+2016#42';
    
    $uri = new Uri($uriString);
    
    $this->assertEquals('api.zayso.org',$uri->getHost());
    $this->assertEquals('/referees',$uri->getPath());
    $this->assertEquals('user:pass',$uri->getUserInfo());
    
    $this->assertEquals('user:pass@api.zayso.org:8080',$uri->getAuthority());
    
    $this->assertEquals($uriString,(string)$uri);
  }
  public function testParams()
  {
    $params =
    [
      'host' => 'localhost',
      'path' => '/referees',
    ];
    
    $uri = new Uri($params);
    $this->assertEquals('localhost',$uri->getHost());
    $this->assertEquals('/referees',$uri->getPath());
 }
}