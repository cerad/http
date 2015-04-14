<?php

namespace Cerad\Component\HttpMessagePsr7;

use \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\MessageInterface as Psr7MessageInterface;
use Psr\Http\Message\StreamableInterface as Psr7StreamInterface;

class Message implements Psr7MessageInterface
{ 
  protected $protocolVersion = null;
  
  protected $headers    = [];
  protected $headerKeys = [];
  
  protected $body;
  
  public function getProtocolVersion()
  {
    return $this->protocolVersion;
  }
  public function withProtocolVersion($version)
  {
    $message = clone $this;
    
    $messageClass = new \ReflectionClass(__CLASS__);
    $versionProp = $messageClass->getProperty('protocolVersion');
    $versionProp->setAccessible(true);
    $versionProp->setValue($message,$version);
    
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
    
    $messageClass = new \ReflectionClass(__CLASS__);
    
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
    
    $nameLower  = strtolower($nameArg);
    
    $message = clone $this;
    
    $messageClass = new \ReflectionClass(__CLASS__);
    
    $headersProp    = $messageClass->getProperty('headers');
    $headerKeysProp = $messageClass->getProperty('headerKeys');
    
    $headersProp    ->setAccessible(true);
    $headerKeysProp ->setAccessible(true);
    
    $headers    = $headersProp   ->getValue($message);
    $headerKeys = $headerKeysProp->getValue($message);
    
    unset($headers[$headerKeys[$nameLower]]);
    
    unset($headerKeys[$nameLower]);
    
    $headersProp   ->setValue($message,$headers);
    $headerKeysProp->setValue($message,$headerKeys);
    
    return $message;
  }
  public function getBody()
  {
    return $this->body;
  }
  public function withBody(Psr7StreamInterface $body)
  {
    $message = clone $this;
    
    $messageClass = new \ReflectionClass(__CLASS__);
    $bodyProp = $messageClass->getProperty('body');
    $bodyProp->setAccessible(true);
    $bodyProp->setValue($message,$body);
    
    return $message;
  }
  // Fake
  public function getHeaderLines($name)
  {
    
  }
}