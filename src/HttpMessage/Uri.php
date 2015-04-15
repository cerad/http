<?php

namespace Cerad\Component\HttpMessage;

use Cerad\Component\HttpMessagePsr7\Uri as Psr7Uri;

class Uri extends Psr7Uri
{
  /* ==================================================
   * Params can either be a url or an array
   * null is okay for buildig from stratch
   */
  public function __construct($params = null)
  { 
    $parts = null;
    
    if (is_string($params)) $parts = parse_url($params);
    
    if (is_array ($params)) $parts = $params;
    
    if ($parts === null) return;
    
    if (isset($parts['scheme'])) $this->scheme = $this->checkScheme($parts['scheme']);

    if (isset($parts['user'])) $this->user = $this->checkUser($parts['user']);
    if (isset($parts['pass'])) $this->pass = $this->checkPass($parts['pass']);
    if (isset($parts['host'])) $this->host = $this->checkHost($parts['host']);
    if (isset($parts['port'])) $this->port = $this->checkPort($parts['port']);
    if (isset($parts['path'])) $this->path = $this->checkPath($parts['path']);
    
    if (isset($parts['query'])) $this->query = $this->checkQuery($parts['query']);
    
    if (isset($parts['fragment'])) $this->fragment = $this->checkFragment($parts['fragment']);
    
  }
}