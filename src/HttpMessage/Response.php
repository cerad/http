<?php
namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessage\Body;

use Cerad\Component\HttpMessagePsr7\Response as Psr7Response;

class Response extends Psr7Response
{
  protected $charset = 'UTF-8';
  
  public function __construct($content = '', $statusCode = 200, $headers = [])
  {
    $this->body = new Body($content);
    
    $this->statusCode = $statusCode;
    $this->statusText = self::$statusTexts[$statusCode];
    
    if (!isset($headers['Cache-Control']))
    {
      $headers['Cache-Control'] = 'no-cache';
    }
    if (!isset($headers['Content-Type']))
    {
      $headers['Content-Type'] = 'text/html;charset=' . $this->charset;
    }
    if (!isset($headers['Date']))
    {
      $date = new \DateTime(null, new \DateTimeZone('UTC'));
      $headers['Date'] = $date->format('D, d M Y H:i:s').' GMT';
    }
    $this->setHeaders($headers);
  }
  /* =====================================================
   * Dump response to the client
   */
  public function send()
  {
    $this->sendHeaders();
    $this->sendContent();
  }
  public function sendHeaders()
  {
    if (headers_sent()) { return $this; }

    // status
    header($this->protocolVersion . ' ' . $this->statusCode . ' ' . $this->statusText, true, $this->statusCode);

    // headers
    foreach ($this->headers as $name => $value) 
    {
      header($name . ': ' . implode(',',$value), false);
    }
  }
  public function sendContent()
  {
    echo $this->body->getContents();
  }
  // Handy for tests
  public function getParsedBody()
  {
    return $this->parseBody();
  }
}