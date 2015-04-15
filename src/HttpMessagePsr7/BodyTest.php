<?php

namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Body;

class BodyTest extends \PHPUnit_Framework_TestCase
{
  public function testConstruct()
  {
    $body = new Body();
  }
}