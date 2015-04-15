<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessagePsr7\Body as Psr7Body;

class Body extends Psr7Body
{
  public function __construct($contents = null)
  {
    if (is_resource($contents))
    {
      $this->stream = $contents;
      return;
    }
    $stream = fopen('php://temp','r+');
    fputs ($stream,$contents);
    rewind($stream);
    
    $this->stream = $stream;
  }
}