<?php
namespace Cerad\Component\HttpMessage;

class ResponseJson extends Response
{
  public function __construct($content = '', $statusCode = 200, $headers = [])
  {
    $content = json_encode($content);
    
    parent::__construct($content,$statusCode,$headers);
    
    $this->headers->set('Content-Type', 'applicationt/json;charset=' . $this->charset);
  }
}