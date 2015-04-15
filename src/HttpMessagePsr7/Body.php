<?php

namespace Cerad\Component\HttpMessagePsr7;

use Psr\Http\Message\StreamInterface as StreamInterface;

class Body implements StreamInterface
{
  protected $stream;
  
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
  public function __toString()
  {
    $stream = $this->stream;
    rewind($stream);
    return stream_get_contents($stream);
  }
  public function close()
  {
    fclose($this->stream);
    $this->stream = null;
  }
  public function detach()
  {
    fclose($this->stream);
    $this->stream = null;
  }
  public function getSize()      { return null; }
  public function tell()         { return 0; }
  public function eof()          { return false; }
  public function isSeekable()   { return false; }
  public function seek($offset, $whence = SEEK_SET) {}
  public function rewind()       { rewind($this->stream); }
  public function isWritable()   { return false; }
  public function write($string) { return 0; }
  public function isReadable()   { return true; }
  
  public function read($length)
  { 
    $stream = $this->stream;
    rewind($stream);
    return stream_get_contents($stream);
  }
  public function getContents()
  {
    $stream = $this->stream;
    rewind($stream);
    return stream_get_contents($stream); 
  }
  public function getMetadata($key = null)
  {
    $meta = stream_get_meta_data($this->stream);
    
    if ($key === null) return $meta;
    
    return isset($meta[$key]) ? $meta[$key] : null;
  }
}