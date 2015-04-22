<?php
namespace Cerad\Component\HttpMessagePsr7;

use Cerad\Component\HttpMessagePsr7\Message as Psr7Message;

//  \InvalidArgumentException as Psr7InvalidArgumentException;

use Psr\Http\Message\UriInterface     as Psr7UriInterface;
use Psr\Http\Message\RequestInterface as Psr7RequestInterface;

class Request extends Psr7Message implements Psr7RequestInterface
{ 
  protected $uri;
  protected $method = 'GET';
  
  protected $requestTarget = '/';
  
  public function getMethod() 
  { 
    return $this->method; 
  }
  protected function checkMethod($methodArg)
  {
    return strtoupper($methodArg);
  }
  public function withMethod($method)
  {
    $this->checkMethod($method);  // Don't change case
    
    $new = clone $this;
    
    $new->method = $method;
    
    return $new;
  }
  public function getUri() { return $this->uri; }
  
  /* ==================================================================
   * Per the spec, host is added as a header
   * Possible want to do something similar with respect to requestTarget
   */
  public function withUri(Psr7UriInterface $uri, $preserveHost = false)
  {
    $new = clone $this;
    
    $new->uri = $uri;
    
    $uriHost     = $uri->getHost();
    $requestHost = $new->getHeaderLine('Host');
    if (!$preserveHost && ($uriHost !== $requestHost))
    {
      return $new->withHeader('Host',$uriHost);
    }
    return $new; 
  }
  public function getRequestTarget() 
  { 
    return $this->requestTarget; 
  }
  public function withRequestTarget($requestTargetArg) 
  { 
    $requestTargetChecked = $requestTargetArg !== null ? $requestTargetArg : '';
    
    $new = clone $this;
    
    $new->requestTarget = $requestTargetChecked;
    
    return $new; 
  }
}
