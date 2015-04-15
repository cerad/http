<?php

namespace Cerad\Component\HttpMessagePsr7;

class Util
{
  /* ===============================================
   * Utitlity for setting a non-public property
   * TODO: Allow array for $name
   */
  static function setProp($obj, $name, $value = null)
  {
    $items = is_array($name) ? $name : [$name => $value];
    
    $clone = clone $obj;
    
    $cloneClass = new \ReflectionClass($clone);
    foreach($items as $propName => $propValue)
    {
      $prop = $cloneClass->getProperty($propName);
      $prop->setAccessible(true);
      $prop->setValue($clone,$propValue);
    }
    return $clone;
  }
}