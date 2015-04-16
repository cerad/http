<?php
namespace Cerad\Component\HttpMessage;

class ResponseJson extends Response
{
  public function __construct($contentArg = '', $statusCode = 200, $headers = [])
  {
    $contentJson = json_encode($contentArg); // TODO: Args
    
    if (!isset($headers['Content-Type']))
    {
      $headers['Content-Type'] = 'application/json;charset=' . $this->charset;
    }
    parent::__construct($contentJson,$statusCode,$headers);
  }
}