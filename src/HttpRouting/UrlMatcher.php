<?php

namespace Cerad\Component\HttpRouting;

class UrlMatcher
{
  protected $routes;
  protected $context;
  
  /* =================================================
   * TODO: If this works out make a RouteCollection object
   * which can structure routes based on their static prefixes
   * avoid linear scan of all routes without adding much complexity
   * Or maybe just be able to pass a prefix array
   */
  public function __construct($routes,$context = null)
  {
    $this->routes  = $routes;
    $this->context = array_replace(
    [
      'host'   => null,
      'method' => null,
    ],
      (array)$context
    );
  }
  public function match($path,$context = null)
  {
    $context = array_replace($this->context,(array)$context);
   
    foreach($this->routes as $name => $match)
    {   
      if (($params = $match($path,$context)) !== false)
      {
        $params['_route'] = $name;
        return $params;
      }
    }
    return false;  // Could toss a route not found exception
  }
}