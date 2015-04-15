<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Util    as Psr7Util;
use Cerad\Component\HttpMessagePsr7\Message as Psr7Message;

//  \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\UriInterface     as Psr7UriInterface;
use Psr\Http\Message\RequestInterface as Psr7RequestInterface;

class Request extends Psr7Message implements Psr7RequestInterface
{ 
  protected $uri;
  protected $method = 'GET';
  
  protected $requestTarget = '/';
  
  public function getMethod() { return $this->method; }
  public function withMethod($method)
  {
    return ($this->method === $method) ? $this : Psr7Util::setProp($this,'method',$method);
  }
  public function getUri() { return $this->uri; }
  
  /* ==================================================================
   * Per the spec, host is added as a header
   * Possible want to do something similar with respect to requestTarget
   */
  public function withUri(Psr7UriInterface $uri, $preserveHost = false)
  {
    $self = $this;
    
    $uriHost     = $uri->getHost();
    $requestHost = $this->getHeaderLine('Host');
    if (!$preserveHost && ($uriHost !== $requestHost))
    {
      $self = $this->withHeader('Host',$uriHost);
    }
    return Psr7Util::setProp($self,'uri',$uri);  
  }
  public function getRequestTarget() 
  { 
    return $this->requestTarget; 
  }
  public function withRequestTarget($requestTargetArg) 
  { 
    $requestTargetChecked = $requestTargetArg !== null ? $requestTargetArg : '';
    
    if ($this->requestTarget === $requestTargetChecked) return $this;
    
    return Psr7Util::setProp($this,'requestTarget',$requestTargetChecked);    
  }
}
