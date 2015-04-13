<?php

namespace Cerad\Component\HttpMessagePsr7;

use \InvalidArgumentException as Psr7InvalidArgumentException;

class Message
{
  protected $protocolVersion = null;
  
  protected $headers    = [];
  protected $headerKeys = [];
  
  public function getProtocolVersion()
  {
    return $this->protocolVersion;
  }
  public function withProtocolVersion($version)
  {
    $message = clone $this;
    
    $messageClass = new \ReflectionClass('Cerad\Component\HttpMessagePsr7\Message');
    $messageClassProp = $messageClass->getProperty('protocolVersion');
    $messageClassProp->setAccessible(true);
    $messageClassProp->setValue($message,$version);
    
    return $message;
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
  
  public function withHeader($name,$value)
  {
    // TODO: Toss exception on invalid input
    
    $nameLower  = strtolower($name);
    $valueArray = is_array($value) ? $value : [$value];
    
    $message = clone $this;
    
    $messageClass = new \ReflectionClass('Cerad\Component\HttpMessagePsr7\Message');
    
    $headersProp    = $messageClass->getProperty('headers');
    $headerKeysProp = $messageClass->getProperty('headerKeys');
    
    $headersProp    ->setAccessible(true);
    $headerKeysProp ->setAccessible(true);
    
    $headers    = $headersProp   ->getValue($message);
    $headerKeys = $headerKeysProp->getValue($message);
    
    $headers   [$name]      = $valueArray;
    $headerKeys[$nameLower] = $name;
    
    $headersProp   ->setValue($message,$headers);
    $headerKeysProp->setValue($message,$headerKeys);
    
    return $message;
    
  }
}