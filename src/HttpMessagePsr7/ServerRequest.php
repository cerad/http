<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Util    as Psr7Util;
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
   
  public function withCookieParams (array $cookies) { return Psr7Util::setProp($this,'cookieParams', $cookies); }
  public function withQueryParams  (array $query)   { return Psr7Util::setProp($this,'queryParams',  $query);   }
  public function withUploadedFiles(array $files)   { return Psr7Util::setProp($this,'uploadedFiles',$files);   }
  public function withParsedBody   (      $data)    { return Psr7Util::setProp($this,'parsedBody',   $data);    }
  
  public function getAttribute($name, $default = null)
  {
    return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
  }
  public function withAttribute($name,$value)
  {
    $attributes = $this->attributes;
    
    if (isset($attributes[$name]) && ($attributes[$name] === $value)) return $this;
    
    $attributes[$name] = $value;
    
    return Psr7Util::setProp($this,'attributes',$attributes);
  }
  public function withoutAttribute($name)
  {
    $attributes = $this->attributes;
    
    if (!isset($attributes[$name])) return $this;
    
    unset($attributes[$name]);
    
    return Psr7Util::setProp($this,'attributes',$attributes);
  }
}
