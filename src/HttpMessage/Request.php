<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessagePsr7\ServerRequest as Psr7ServerRequest;

class Request extends Psr7ServerRequest
{
  protected $baseHref  = '/';
  protected $routePath = '/';
  
  protected $isJson = false;
  protected $isForm = false;
  
  public function __construct($serverData = null, $headers = [], $content = null)
  {
    if (is_array($serverData))
    {
      return $this->createFromServerData($serverData,$headers,$content);
    }
    if (is_string($serverData)) 
    {
      return $this->createFromRequestLine($serverData,$headers,$content);
    }
    // Empty constructor is okay
    return;
  }
  /* =========================================================
   * Build request from $_SERVER
   */
  protected function createFromServerData(array $serverData,$headers=[],$content=null)
  {
    $this->serverParams = $serverParams = array_replace(
    [
      'SERVER_NAME'     => 'localhost',
      'SERVER_PORT'     => 80,
      'SERVER_PROTOCOL' => 'HTTP/1.1', // USED
      
      'REQUEST_URI'     => null,   // USED
      'REQUEST_TIME'    => time(), // Not Used CURRENTLY
      'REQUEST_METHOD'  => 'GET',  // USED
      
      'SCRIPT_NAME'     => '', // USED for baseHref and routePath
      'HTTPS'           => 'off',
      
      // Headers
      'CONTENT_TYPE'    => 'text/plain', // 'application/x-www-form-urlencoded'
      'HTTP_HOST'       => 'localhost',
    ],
    $serverData);
    
    // The basics
    $this->method          = $this->checkMethod(         $serverParams['REQUEST_METHOD']);
    $this->protocolVersion = $this->checkProtocolVersion($serverParams['SERVER_PROTOCOL']);
    
    // Request uri seems to be the most reliable
    $requestUriParts = explode('?',$serverParams['REQUEST_URI']);
    $uriParts = [];
    $uriParts['path']  = $requestUriParts[0];
    $uriParts['query'] = isset($requestUriParts[1]) ? $requestUriParts[1] : null;
    
    $uriParts['host'] = $serverParams['HTTP_HOST'];
    
    $uriParts['scheme'] = $serverParams['HTTPS'] !== 'off' ? 'http' : 'https';
    $uriParts['port']   = (int)$serverParams['SERVER_PORT'];
    
    // Leave user/pass until we need them
    
    // Build the uri
    $this->uri = $uri = new Uri($uriParts);
    parse_str($uri->getQuery(),$this->queryParams);
    
    // These do not have HTTP_ prefixes
    $contentHeaders = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
    
    foreach($serverParams as $key => $value)
    {
      if (substr($key,0,5) == 'HTTP_')
      {
        $name = $this->transformHeaderKey(substr($key,5));
        $headers[$name] = isset($headers[$name]) ? $headers[$name] : $value;
      }
      if (isset($contentHeaders[$key])) 
      {
        $name = $this->transformHeaderKey($key,5);
        $headers[$name] = isset($headers[$name]) ? $headers[$name] : $value;
      }
    }
    $this->setHeaders($headers);

    // baseHref is before any php script
    $scriptName = $serverParams['SCRIPT_NAME'];
    $pos = strrpos($scriptName,'/');
    $this->baseHref = substr($scriptName,0,$pos+1);
    
    // routePath is after any script name
    $scriptNameLen = strlen($scriptName);
    $requestPath = $this->getUri()->getPath();
    
    if (substr($requestPath,0,$scriptNameLen) == $scriptName) 
    {
      $routePath = substr($requestPath,$scriptNameLen);
    }
    else $routePath = $requestPath;
    
    $this->routePath = $routePath ? $routePath : '/';
    
    // Content stuff
    $stream = $content ? $content : fopen('php://input','r+');
    $this->body = new Body($stream);
    $this->parseBody();
  }
  protected function transformHeaderKey($key)
  {
    return implode('-', array_map('ucfirst', explode('_',strtolower($key))));
  }
  /* =========================================================
   * POST url
   */
  protected function createFromRequestLine($requestLine,$headers=[],$content=null)
  {
    // GET url PROTOCOL
    $parts = explode(' ',$requestLine);
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
    $this->uri = $uri = new Uri($url);
      
    if (!isset($headers['Host']))
    {
      $headers['Host'] = $uri->getHost();
    }
    if (!isset($headers['Content-Type']))
    {
      $headers['Content-Type'] = 'text/plain';
    }
    $this->setHeaders($headers);
      
    $this->routePath = $uri->getPath();
    $this->requestTarget = $url;
      
    parse_str($uri->getQuery(),$this->queryParams);
    
    $this->body = new Body($content);
    
    $this->parseBody();
  }
  /* ======================================================
   * For now this gets called when the request is constructed
   * Avoids the immutable stuff
   */
  protected function parseBody()
  {
    // Go ahead and parse here, implement getParserContent later
    $contentType = strtolower($this->getHeaderLine('Content-Type'));
    
    if (strpos($contentType,'application/json') !== false)
    {
      $this->isJson  = true;
      $this->parsedBody = json_decode($this->body->getContents(),true); 
    }
    if (strpos($contentType,'application/x-www-form-urlencoded') !== false)
    {
       $this->isForm = true;
       $formData = [];
       parse_str($this->body->getContents(),$formData);
       $this->parsedBody = $formData;
    }
  }
  /* =========================================================
   * Some misc stuff
   */
  public function isMethodPost()    { return $this->method == 'POST'    ? true : false; }
  public function isMethodOptions() { return $this->method == 'OPTIONS' ? true : false; }
  
  public function isContentForm() { return $this->isForm; }
  public function isContentJson() { return $this->isJson; }
  
  public function getBaseHref () { return $this->baseHref;  }
  public function getRoutePath() { return $this->routePath; }
  
}