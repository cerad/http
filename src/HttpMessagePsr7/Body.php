<?php

namespace Cerad\Component\HttpMessagePsr7;

use Psr\Http\Message\StreamInterface as StreamInterface;

class Body implements StreamInterface
{
  protected $meta;
  protected $stream;
  
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
  public function seek($offset, $whence = SEEK_SET) {}
  
  public function isReadable()   { return true; }
  public function isWritable()   { return false; }
  public function write($string) { return 0; }
  
  public function isSeekable()   
  { 
    return $this->getMetaData('seekable') ? true : false;
  }
  public function rewind()       
  {
    if ($this->isSeekable()) rewind($this->stream); 
  }
  public function read($length)
  { 
    $stream = $this->stream;
    $this->rewind($stream);
    return stream_get_contents($stream);
  }
  public function getContents()
  {
    $stream = $this->stream;
    $this->rewind($stream);
    return stream_get_contents($stream); 
  }
  public function getMetadata($key = null)
  {
    $this->meta = $meta = $this->meta ? $this->meta : stream_get_meta_data($this->stream);
    
    if ($key === null) return $meta;
    
    return isset($meta[$key]) ? $meta[$key] : null;
  }
}