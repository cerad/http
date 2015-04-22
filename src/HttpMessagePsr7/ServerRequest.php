<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Request as Psr7Request;

use Psr\Http\Message\ServerRequestInterface as Psr7ServerRequestInterface;

class ServerRequest extends Psr7Request implements Psr7ServerRequestInterface
{ 
  protected $serverParams  = [];
  protected $cookieParams  = [];
  protected $queryParams   = [];
  protected $uploadedFiles = [];
  protected $parsedBody    = null;
  protected $attributes    = [];
  
  public function getServerParams () { return $this->serverParams;  }
  public function getCookieParams () { return $this->cookieParams;  }
  public function getQueryParams  () { return $this->queryParams;   }
  public function getUploadedFiles() { return $this->uploadedFiles; }
  public function getParsedBody   () { return $this->parsedBody;    }
  public function getAttributes   () { return $this->attributes;    }
   
  public function withCookieParams(array $cookies) 
  { 
    $new = clone $this;
    $new->cookieParams = $cookies;
    return $new;
  }
  public function withQueryParams(array $query)   
  { 
    $new = clone $this;
    $new->queryParams = $query;
    return $new;
  }
  public function withUploadedFiles(array $uploadedFiles)   
  { 
    $new = clone $this;
    $new->uploadedFiles = $uploadedFiles;
    return $new;
  }
  public function withParsedBody($parsedBody)
  { 
    $new = clone $this;
    $new->parsedBody = $parsedBody;
    return $new;
  }
  public function getAttribute($name, $default = null)
  {
    return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
  }
  public function withAttribute($name,$value)
  {
    $new = clone $this;
    $new->attributes[$name] = $value;
    return $new;
  }
  public function withoutAttribute($name)
  {
    if (!isset($this->attributes[$name])) return $this;
    
    $new = clone $this;
    unset($new->attributes[$name]);
    return $new;
  }
}
