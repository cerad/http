<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Message;

//  \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class Response extends Message implements Psr7ResponseInterface
{ 
  // http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
  public static $statusTexts = 
  [
    200 => 'OK', 201 => 'Created', 202 => 'Accepted',
  ];
  protected $statusCode;
  protected $statusText;
  
  public function getStatusCode()   { return $this->statusCode; }
  public function getReasonPhrase() { return $this->statusText; }
  
  public function withStatus($statusCode,$reasonPhrase = null)
  {
    $statusText = $reasonPhrase ? $reasonPhrase : self::$statusTexts[$statusCode];
    
    $response = clone $this;
    
    $responseClass = new \ReflectionClass(__CLASS__);
    
    $statusCodeProp = $responseClass->getProperty('statusCode');
    $statusTextProp = $responseClass->getProperty('statusText');
    
    $statusCodeProp->setAccessible(true);
    $statusTextProp->setAccessible(true);
    
    $statusCodeProp->setValue($response,$statusCode);
    $statusTextProp->setValue($response,$statusText);
    
    return $response; 
  }
}
