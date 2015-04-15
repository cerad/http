<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Uri;

class UriTest extends \PHPUnit_Framework_TestCase
{
  public function testWithScheme()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withScheme('HTTP');
    
    $this->assertEquals('http',$uri2->getScheme());
  }
  /**
   * @expectedException InvalidArgumentException
   */
  public function testWithSchemeFail()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withScheme('HTTPx');
  }
  public function testWithHost()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withHost('localhost');
    
    $this->assertEquals('localhost',$uri2->getHost());
  }
  public function testWithPath()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withPath('/items/1');
    
    $this->assertEquals('/items/1',$uri2->getPath());
  }
  public function testWithPort()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withScheme('http');
    
    $uri3 = $uri2->withPort(8080);
    $uri4 = $uri2->withPort(  80);
    
    $this->assertEquals(8080,$uri3->getPort());
    $this->assertEquals(null,$uri4->getPort());
  }
  public function testWithQuery()
  {
    $query = 'role=admin&title=NG+2016';
    
    $uri1 = new Uri();
    $uri2 = $uri1->withQuery($query);
    
    $this->assertEquals($query,$uri2->getQuery());
  }
  public function testWithFragment()
  {
    $fragment = '42';
    
    $uri1 = new Uri();
    $uri2 = $uri1->withFragment($fragment);
    
    $this->assertEquals($fragment,$uri2->getFragment());
  }
  public function testWithUserInfo()
  {
    $uri1 = new Uri();
    $uri2 = $uri1->withUserInfo('user','pass');
    
    $this->assertEquals('user:pass',$uri2->getUserInfo());
  }
  public function testGetAuthority()
  {
    $uri1 = new Uri();
    $uri2 = $uri1
      ->withUserInfo('user','pass')
      ->withPort(8080)
      ->withHost('localhost')
    ;
    
    $this->assertEquals('user:pass@localhost:8080',$uri2->getAuthority());
  }
  public function testToString()
  {
    $url = 'https://user:pass@localhost:8080/referees?name=Clinton#42';
    $urlParts = parse_url($url);
    
    $uri1 = new Uri();
    $uri2 = $uri1
      ->withScheme  ($urlParts['scheme'])
      ->withUserInfo($urlParts['user'],$urlParts['pass'])
      ->withHost    ($urlParts['host'])
      ->withPort    ($urlParts['port'])
      ->withPath    ($urlParts['path'])
      ->withQuery   ($urlParts['query'])
      ->withFragment($urlParts['fragment'])
    ;
    $this->assertEquals($url,(string)$uri2);
  }
}