<?php

namespace Cerad\Component\HttpMessagePsr7;

use \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\MessageInterface as Psr7MessageInterface;
use Psr\Http\Message\StreamInterface  as Psr7StreamInterface;

class Message implements Psr7MessageInterface
{ 
  protected $body;
  protected $protocolVersion = '1.1'; // Okay?
  
  protected $headers    = [];
  protected $headerKeys = [];
  
  public function getProtocolVersion()
  {
    return $this->protocolVersion;
  }
  protected function checkProtocolVersion($protocolVersionArg)
  {
    $protocolVersionChecked = 
      substr($protocolVersionArg,0,5) !== 'HTTP/' ? 
      $protocolVersionArg : 
      substr($protocolVersionArg,5);
    
    return $protocolVersionChecked;
  }
  public function withProtocolVersion($protocolVersionArg)
  {
    $new = clone $this;
    
    $new->protocolVersion = $this->checkProtocolVersion($protocolVersionArg);
    
    return $new;
  }
  public function getHeader($name)
  {
    $nameLower = strtolower($name);
    
    return isset($this->headerKeys[$nameLower]) ? $this->headers[$this->headerKeys[$nameLower]] : [];
  }
  public function getHeaderLine($name)
  {
    $nameLower = strtolower($name);
    
    $value = isset($this->headerKeys[$nameLower]) ? $this->headers[$this->headerKeys[$nameLower]] : [];
    
    return implode(',',$value);
  }
  public function hasHeader($name)
  {
    $nameLower = strtolower($name);
    
    return isset($this->headerKeys[$nameLower]) ? true : false;
  }
  public function getHeaders() { return $this->headers; }
  
  public function withHeader($nameArg,$valueArg)
  {
    $nameLower  = strtolower($nameArg);
    $valueArray = is_array($valueArg) ? $valueArg : [$valueArg];
    
    $new = clone $this;
    
    $new->headers   [$nameArg]   = $valueArray;
    $new->headerKeys[$nameLower] = $nameArg;
    
    return $new;        
  }
  public function withAddedHeader($nameArg, $valueArg)
  {
    if (!$nameArg) throw new Psr7InvalidArgumentException('MessagePsr7::withAddedHeader');
    
    $valueArray = is_array($valueArg) ? $valueArg : [$valueArg];
    
    $valueExisting = $this->getHeader($nameArg);
    
    $valueNew = array_merge($valueExisting,$valueArray);
    
    return $this->withHeader($nameArg,$valueNew);
  }
  public function withoutHeader($nameArg)
  {
    if (!$this->hasHeader($nameArg)) return $this;
    
    $nameLower = strtolower($nameArg);
    
    $new = clone $this;
    
    unset($new->headers[$new->headerKeys[$nameLower]]);
    unset($new->headerKeys[$nameLower]);
    
    return $new; 
  }
  public function getBody()
  {
    return $this->body;
  }
  public function withBody(Psr7StreamInterface $body)
  {
    $new = clone $this;
    
    $new->body = $body;
    
    return $new; 
  }
  /* ===================================================
   * Helper function for multiple headers
   */
  protected function setHeaders($headers)
  {
    foreach($headers as $key => $value)
    {
      $valueArray = is_array($value) ? $value : [$value];
      
      $this->headers[$key] = $valueArray;
      $this->headerKeys[strtolower($key)] = $key;
    }
  }
  /* ======================================================
   * Helper function to parse body based on Content-Type
   */
  protected function parseBody()
  {
    $contents = $this->body->getContents();
    $contentType = strtolower($this->getHeaderLine('Content-Type'));
    
    if (strpos($contentType,'application/json') !== false)
    {
      $this->isJson  = true;
      return json_decode($contents,true); 
    }
    if (strpos($contentType,'application/x-www-form-urlencoded') !== false)
    {
       $this->isForm = true;
       $formData = [];
       parse_str($contents,$formData);
       return $formData;
    }
    // Not sure
    return $contents;
  }

}