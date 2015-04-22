<?php

namespace Cerad\Component\HttpMessagePsr7;

/* ===============================================
 * v0.2
 * This is no longer used.  Keep for reference.
 */
class Utilx
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
  // Need this?
  static function getProp($obj, $name)
  {
    $propClass = new \ReflectionClass($obj);
    $prop = $propClass->getProperty($name);
    $prop->setAccessible(true);
    return $prop->getValue();
  }
}