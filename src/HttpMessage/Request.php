<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessagePsr7\Request as Psr7Request;

class Request extends Psr7Request
{
  protected $isJson = false;
  protected $isForm = false;
  
  public function __construct($serverData, $headers = [], $content = null)
  {
    if (is_array($serverData))
    {
      $this->server  = new ServerBag($serverData);
      $serverHeaders = $this->server->getHeaders();
      $this->headers = new HeaderBag(array_replace($serverHeaders,(array)$headers));
      $this->uri     = new Uri($this->server->getUriParts());
      
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
          $this->method = $this->checkMethod($parts[0]);
          $url =          $parts[1];
          break;
        default:
          $this->method = $this->checkMethod($parts[0]);
          $url =          $parts[1];
          $this->protocolVersion = $this->checkProtocolVersion($parts[2]);
      }
      $this->uri = new Uri($url);
      
      $headers['Host'] = $this->uri->getHost(); // Sync
      
      $this->setHeaders($headers);
      
      return;
    } 
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
  public function isMethodPost()    { return $this->method == 'POST'    ? true : false; }
  public function isMethodOptions() { return $this->method == 'OPTIONS' ? true : false; }
  
  public function isContentForm() { return $this->isForm; }
  public function isContentJson() { return $this->isJson; }
  
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