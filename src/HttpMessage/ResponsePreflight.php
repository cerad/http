<?php
namespace Cerad\Component\HttpMessage;

class ResponsePreflight extends Response
{
  public function __construct
  (
    $allowOriginArg,
    $allowHeadersArg = 'Content-Type,Accept',
    $allowMethodsArg = null,
    $maxAgeArg = 100
  )
  {
    $allowMethods = $allowMethodsArg ? $allowMethodsArg : 'GET,POST,PUT,PATCH,DELETE';
    $headers = 
    [  
      'Access-Control-Allow-Origin'  => $allowOriginArg,
      'Access-Control-Allow-Methods' => $allowMethods,
      'Access-Control-Allow-Headers' => $allowHeadersArg,
      'Access-Control-Max-Age'       => $maxAgeArg,
    ];  
    parent::__construct('',200,$headers);
  }
}