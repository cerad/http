<?php
namespace Cerad\Component\HttpMessage;

class ResponsePreflight extends Response
{
  public function __construct
  (
    $allowOriginArg  = '*',
    $allowHeadersArg = null,
    $allowMethodsArg = null,
    $maxAgeArg = 100
  )
  {
    $allowMethods = $allowMethodsArg ? $allowMethodsArg : 'GET,POST,PUT,PATCH,DELETE';
    $allowHeaders = $allowHeadersArg ? $allowHeadersArg : 'Content-Type,Accept';
    
    $headers = 
    [  
      'Access-Control-Allow-Origin'  => $allowOriginArg,
      'Access-Control-Allow-Methods' => $allowMethods,
      'Access-Control-Allow-Headers' => $allowHeaders,
      'Access-Control-Max-Age'       => $maxAgeArg,
    ];  
    parent::__construct('',200,$headers);
  }
}