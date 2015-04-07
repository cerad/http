<?php

namespace Cerad\Component\HttpMessage;

require __DIR__  . '/../../vendor/autoload.php';

class MessageTest extends \PHPUnit_Framework_TestCase
{
  public function testHeaderBag()
  {
    $bag = new HeaderBag(
    [
      'Accept' => 'accept',
      'Host'   => 'zayso.org',
      'Auth'   => 'auth0,auth1,auth2',
    ]);
    
    $this->assertEquals('accept',   $bag->get('Accept'));
    $this->assertEquals('not found',$bag->get('Acceptx','not found'));
    
    $this->assertEquals(3,count($bag->get()));
    
    $auths = $bag->get('Auth',null,true);
    $this->assertEquals('auth1',$auths[1]);
    
    $bag->set('Accept', 'acceptx');
    $this->assertEquals('acceptx',$bag->get('Accept'));
    
    $bag->set('Host',null);
    $this->assertEquals('not found',$bag->get('Host','not found'));
  }
  public function testServerBag()
  {
    $bag = new ServerBag();
    $headers = $bag->getHeaders(); // print_r($headers);
    $this->assertEquals('localhost',$headers['Host']);
    $this->assertTrue(isset($headers['Content-Type']));
  }
  public function testUriBagString()
  {
    $uriString = 'https://user:pass@api.zayso.org:8080/referees?project=ng2016&title=NG+2016#42';
    
    $bag = new UriBag($uriString);
    
    $this->assertEquals('api.zayso.org',$bag->get('host'));
    
    $this->assertEquals('user:pass',$bag->get('user_info'));
    
    $this->assertEquals('user:pass@api.zayso.org:8080',$bag->get('authority'));
    
    $this->assertEquals('/referees',$bag->get('path'));
    
    $this->assertEquals('project=ng2016&title=NG 2016',$bag->get('query'));

  }
  public function testUriBagServer()
  {
    $server = 
    [
      'QUERY_STRING' => 'project=ng2016&title=NG+2016',
    ];
    $serverBag = new ServerBag($server);
    $uriParts = $serverBag->getUriParts();
    $uriBag = new UriBag($uriParts);
    
    $this->assertEquals('/',        $uriBag->get('path'));
    $this->assertEquals('localhost',$uriBag->get('host'));
    $this->assertEquals('http',     $uriBag->get('scheme'));
    
    $this->assertEquals('project=ng2016&title=NG 2016',$uriBag->get('query'));
  }
  public function testRequest()
  {
    $uriString = 'https://user:pass@api.zayso.org:8080/referees?project=ng2016&title=NG+2016#42';
    
    $requestLine = 'POST ' . $uriString . ' HTTP/1.1';
    
    $request = new Request($requestLine);
    
    $this->assertEquals('POST',         $request->getMethod());
    $this->assertEquals('HTTP/1.1',     $request->getProtocolVersion());
    $this->assertEquals('/referees',    $request->getPath());
    $this->assertEquals('api.zayso.org',$request->getHost());
    $this->assertEquals('api.zayso.org',$request->getHeader('Host'));    
  }
  public function testPost()
  {
    $requestLine = 'POST /referees';
    $headers = 
    [
      'Content-Type' => 'application/json',
    ];
    $data = ['name' => 'Art H','roles' => ['Developer']];
    
    $request = new Request($requestLine,$headers,json_encode($data));
    
    $content = $request->getContent();
    
    $this->assertEquals('Art H',$content['name']);
  }
}