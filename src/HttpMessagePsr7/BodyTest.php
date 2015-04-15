<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Body;

class BodyTest extends \PHPUnit_Framework_TestCase
{
  public function testString()
  {
    $contents = 'HTML Stuff';
    
    $body = new Body($contents);
    
    $this->assertEquals($contents,(string)$body);
    
    $this->assertEquals($contents,$body->getContents());
  }
}