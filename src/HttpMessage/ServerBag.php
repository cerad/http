<?php

namespace Cerad\Component\HttpMessage;

class ServerBag extends ParamBag
{
  protected $items;
  
  public function __construct($items = [])
  {
    $this->items = array_replace(
    [
      'SERVER_NAME'          => 'localhost',
      'SERVER_PORT'          => 80,
      'SERVER_PROTOCOL'      => 'HTTP/1.1',
      
      'HTTPS'                => 'off', // On, need to mess with port stuff and scheme
    //'FRAGMENT'             => '',    // Never sent to server so NA
      
      'PATH_INFO'            => '/',
      'QUERY_STRING'         => '', // TODO
      
      'REQUEST_URI'          => '', // TODO
      'REQUEST_TIME'         => time(),
      'REQUEST_METHOD'       => 'GET',
      
      'REMOTE_ADDR'          => '127.0.0.1',
      
      'PHP_SELF'             => '', // These three all interact when generating url's
      'SCRIPT_NAME'          => '',
      'SCRIPT_FILENAME'      => '',
      
      // Headers
      'CONTENT_TYPE'         => 'text/plain', // 'application/x-www-form-urlencoded'
      'HTTP_HOST'            => 'localhost',
      'HTTP_USER_AGENT'      => 'Cerad/1.X',
      'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
      'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
    ],
    $items);
  }
  public function getUriParts()
  {
    $items = $this->items;
    
    $parts = 
    [
      'host' => $items['HTTP_HOST'],
      'path' => $items['PATH_INFO'],
      'port' => $items['SERVER_PORT'],
      
      'query'  => $items['QUERY_STRING'],
      
      'scheme' => $items['HTTPS'] = 'off' ? 'http' : 'https',
      
      // skip user and pass until we need them for something
      // fragment never get's sent to the server
    ];
    return $parts;
  }
  public function getHeaders()
  {
    $headers = [];
    
    // These do not have HTTP_ prefixes
    $contentHeaders = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
    
    foreach($this->items as $key => $value)
    {
      if (substr($key,0,5) == 'HTTP_')
      {
        $headers[$this->transformHeaderKey(substr($key,5))] = $value;
      }
      if (isset($contentHeaders[$key])) 
      {
        $headers[$this->transformHeaderKey($key)] = $value;
      }
    }
    // Also some USER_AUTH stuff that we don't care about
    
    return $headers;
  }
  protected function transformHeaderKey($key)
  {
    return implode('-', array_map('ucfirst', explode('_',strtolower($key))));
  }
}
