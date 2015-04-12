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
   * 
   * SymfonyRequest::getBasePath . '/' represents the base href
   * It is not directly available from the $_SERVER
   */
  public function getBaseHref()
  {
    // index.php, think we always have these two
    $scriptFileName = basename($this->server->get('SCRIPT_FILENAME'));
    $scriptName     =          $this->server->get('SCRIPT_NAME');
    
    $pos = strpos($scriptName,$scriptFileName);
    
    // /web/
    return $pos === false ? $scriptName : substr($scriptName,0,$pos);
  }
  /* ===========================================
   * PATH_INFO is almost there but always want a /
   * Init it in the constructor
   * 
   * TODO: Probably does not work correctly from test script
   * And maybe not from a sub directory
   */
  public function getRoutePath() 
  { 
    return $this->getUri()->get('path');
    
    die('Path ' . $this->getUri()->get('path'));
    $pathInfo   = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'NONE';
    die('PathInfo ' . $pathInfo . ' ' . $_SERVER['REQUEST_URI']);
    return $this->server->get('PATH_INFO');
  }

}