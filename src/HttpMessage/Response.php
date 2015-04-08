<?php
namespace Cerad\Component\HttpMessage;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse; // For status code stuff

class Response
{
  public $headers;
  
  protected $statusCode;
  protected $statusText;
  
  protected $charset  = 'UTF-8';
  protected $protocol = 'HTTP/1.1';
  
  protected $content;
  
  public function __construct($content = '', $statusCode = 200, $headers = [])
  {
    $this->content = $content;
    $this->headers = new HeaderBag($headers);
    
    $statusText = 
      isset(SymfonyResponse::$statusTexts[$statusCode]) ?
            SymfonyResponse::$statusTexts[$statusCode]  :
            null;
    
    $this->statusCode = $statusCode;
    $this->statusText = $statusText;
    
    $this->headers->set('Cache-Control','no-cache');
    $this->headers->set('Content-Type', 'text/html;charset=' . $this->charset);
    
    if (!$this->headers->get('Date')) 
    {
      $date = new \DateTime(null, new \DateTimeZone('UTC'));
    //$date->setTimezone(new \DateTimeZone('UTC'));
      $this->headers->set('Date', $date->format('D, d M Y H:i:s').' GMT');
    }
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
    header($this->protocol . ' ' . $this->statusCode . ' ' . $this->statusText, true, $this->statusCode);

    // headers
    foreach ($this->headers->get() as $name => $value) 
    {
      header($name . ': ' . $value, false);
    }
  }
  public function sendContent()
  {
        echo $this->content;
  }
  public function getStatusCode()   { return $this->statusCode; }
  public function getReasonPhrase() { return $this->statusText; }
}