<?php

namespace Cerad\Component\HttpMessage;

class Request
{
  public $uri;
  public $server;
  public $headers;
  public $attributes;
  
  protected $method;
  protected $protocol;
  
  protected $content;
  
  public function __construct($serverData, $headers = [], $content = null)
  {
    $this->attributes = new AttributeBag();
    
    if (is_array($serverData))
    {
      $this->server  = new ServerBag($serverData);
      $serverHeaders = $this->server->getHeaders();
      $this->headers = new HeaderBag(array_replace($serverHeaders,(array)$headers));
      $this->uri     = new UriBag($serverData);
      
      $this->method   = $this->server->get('REQUEST_METHOD');
      $this->protocol = $this->server->get('SERVER_PROTOCOL');
    }
    if (is_string($serverData)) 
    {
      // GET url PROTOCOL
      $parts = explode(' ',$serverData);
      switch(count($parts))
      {
        case 1: 
          $url = $parts[0]; // No method, probably bad
          break;
        case 2: 
          $this->method = strtoupper($parts[0]);
          $url =                     $parts[1];
          break;
        default:
          $this->method = strtoupper($parts[0]);
          $url =                     $parts[1];
          $this->protocol =          $parts[2];
      }
      $this->uri = new UriBag($url);
      
      $headers['Host'] = $this->uri->get('host'); // Sync
      
      $this->headers = new HeaderBag($headers);
      
      $this->server  = new ServerBag();
    }
    $this->attributes = new AttributeBag();
    
    $this->content = file_get_contents('php://input');
    
    if (!$this->content) $this->content = $content;
    
    // Do some parsing or leave till later?
    $contentType = $this->headers->get('Content-Type');
    switch($contentType)
    {
      case 'application/json':
        $this->content = json_decode($this->content,true); 
        break;
      case 'application/x-www-form-urlencoded':
        $formData = [];
        parse_str($this->content,$formData);
        $this->content = $formData;
        break;
    }
  }
  public function getMethod()          { return $this->method;   }
  public function getProtocolVersion() { return $this->protocol; }
  
  public function getPath() { return $this->uri->get('path'); }
  
  public function getHost() 
  { 
    // header->get('Host')
    return $this->uri->get('host'); 
  }
  public function getHeader($name, $default = null, $asArray = false)
  {
    return $this->headers->get($name,$default,$asArray);
  }
  public function getContent() { return $this->content; }
}