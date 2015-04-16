<?php

namespace Cerad\Component\HttpMessage;

class HeaderBag extends ParamBag
{
  public function __construct($headers = [])
  {
    $this->items = $headers;
  }
  public function get($name = null, $default = null, $asArray = false)
  {
    $value = parent::get($name,$default);
    
    if (($asArray === false) || ($value === $default) || (is_array($value))) return $value;
    
    return array_map('trim', explode(',', $value));
  }
}
