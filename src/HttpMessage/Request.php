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
  
  protected $isJson = false;
  protected $isForm = false;
  
  public function __construct($serverData, $headers = [], $content = null)
  {
    $this->attributes = new AttributeBag();
    
    if (is_array($serverData))
    {
      $this->server  = new ServerBag($serverData);
      $serverHeaders = $this->server->getHeaders();
      $this->headers = new HeaderBag(array_replace($serverHeaders,(array)$headers));
      $this->uri     = new UriBag($this->server->getUriParts());
      
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
      
      // Might need more server initialization?
      $this->server  = new ServerBag(['PATH_INFO' => $this->uri->getPath()]);
    }
    $this->attributes = new AttributeBag();
    
    $this->content = file_get_contents('php://input');
    
    if (!$this->content) $this->content = $content;
    
    // Go ahead and parse here, implement getParserContent later
    $contentType = strtolower($this->headers->get('Content-Type'));
    
    if (strpos($contentType,'application/json') !== false)
    {
      $this->isJson  = true;
      $this->content = json_decode($this->content,true); 
    }
    if (strpos($contentType,'application/x-www-form-urlencoded') !== false)
    {
       $this->isForm = true;
       $formData = [];
       parse_str($this->content,$formData);
       $this->content = $formData;
    }
  }
  public function getProtocolVersion() { return $this->protocol; }
  
  public function getMethod() { return $this->method;   }
  
  public function isPost()    { return $this->method == 'POST' ? true : false; }
  
  public function isForm() { return $this->isForm; }
  public function isJson() { return $this->isJson; }
  
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
  
  public function getUri() { return $this->uri; }
  
  public function getServerParams() { return $this->server->get(); }
  public function getQueryParams() 
  { 
    $params = [];
    parse_str($this->uri->get('query'),$params);
    return $params;
  }
  
  /* ====================================================
   * Everything breaks as soon as I go to /web/index.php, can't find css etc
   * Need:  <base href="http://localhost:8080/web/">
   * Works: <base href="/web/">
   */
  public function getBaseHref()
  {
    $scriptName = $this->server->get('SCRIPT_NAME');
    $pos = strrpos($scriptName,'/');
    return substr($scriptName,0,$pos+1);    
  }
  public function getRoutePath() 
  { 
    $scriptName    = $this->server->get('SCRIPT_NAME'); // /app.php
    $scriptNameLen = strlen($scriptName);

  //$requestPath = explode('?',$this->server->get('REQUEST_URI'))[0];
    $requestPath = $this->getUri()->getPath();
    
    if (substr($requestPath,0,$scriptNameLen) == $scriptName) 
    {
      $routePath = substr($requestPath,$scriptNameLen);
    }
    else $routePath = $requestPath;
    
    return $routePath ? $routePath : '/';
  }
}