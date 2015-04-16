<?php

namespace Cerad\Component\HttpMessage;

abstract class ParamBag
{
  protected $items;
  
  public function __construct($items = [])
  {
    $this->items = (array)$items;
  }
  public function get($name = null, $default = null)
  {
    if (!$name) return $this->items;
    
    return isset($this->items[$name]) ?  $this->items[$name] : $default;
  }
  public function set($name,$value = null)
  {
    if (!$name) return;
    
    if (is_array($name))
    {
      foreach($name as $key => $value)
      {
        $this->set($key,$value);
      }
      return;
    }
    if ($value === null)
    {
      if (isset($this->items[$name])) unset($this->items[$name]);
      return;
    }
    $this->items[$name] = $value;
  }
}
