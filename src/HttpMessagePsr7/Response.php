<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Util     as Psr7Util;
use Cerad\Component\HttpMessagePsr7\Message  as Psr7Message;

//  \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class Response extends Psr7Message implements Psr7ResponseInterface
{ 
  // http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
  public static $statusTexts = 
  [
    200 => 'OK', 201 => 'Created', 202 => 'Accepted',
    400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found',
    405 => 'Method Not Allowed', 406 => 'Not Acceptable',
  ];
  protected $statusCode;
  protected $statusText;
  
  public function getStatusCode()   { return $this->statusCode; }
  public function getReasonPhrase() { return $this->statusText; }
  
  public function withStatus($statusCode,$reasonPhrase = null)
  {
    $statusText = $reasonPhrase ? $reasonPhrase : self::$statusTexts[$statusCode];
    
    return Psr7Util::setProp($this,['statusCode' => $statusCode, 'statusText' => $statusText]);
  }
}
