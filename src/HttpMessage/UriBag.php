<?php

namespace Cerad\Component\HttpMessage;

class UriBag extends ParamBag
{
  // Accepts either array or urlString
  public function __construct($url)
  {
    $items =
    [
      'scheme'   => null,
      'host'     => null,
      'port'     => null,
      'user'     => null,
      'pass'     => null,
      'path'     => null,
      'query'    => null,
      'fragment' => null,
    ];
    
    if (is_string($url)) $parts = array_replace($items,parse_url($url));
    else                 $parts = array_replace($items,   (array)$url);
    
    $parts['query'] = urldecode($parts['query']);
    
    if (($parts['scheme'] == 'http')  && ($parts['port'] ==  80)) $parts['port'] = null;
    if (($parts['scheme'] == 'https') && ($parts['port'] == 337)) $parts['port'] = null;
    
    $this->items = array_replace($items,$parts);
 }
  public function get($name = null, $value = null)
  {
    switch($name)
    {
      case 'user_info': return $this->getUserInfo();
      case 'authority': return $this->getAuthority();
    }
    return parent::get($name,$value);
  }
  protected function getUserInfo() 
  { 
    $user = $this->items['user'];
    if (!$user) return null;
    
    return $this->items['pass'] ? $user . ':' . $this->items['pass'] : $user;
  }
  protected function getAuthority() 
  { 
    $user = $this->getUserInfo();
    $host = $this->items['host'];
    
    $user_host = $user ? $user . '@' . $host : $host;
    
    $port = $this->items['port'];
    
    return $port ? $user_host . ':' . $port : $user_host;
  }
  public function set($name,$value = null)
  {
    throw new \BadMethodException("UriBag::set($name) is a no no");
  }
}
