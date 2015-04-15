<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Body;

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