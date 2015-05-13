<?php

namespace Cerad\Component\HttpRouting;

class RoutingTestBase extends \PHPUnit_Framework_TestCase
{
  static $routeRefereesAll;
  static $routeRefereesOne;
  static $routeRefereesBoth;
  
  static $routeProjectGamesOne;
  
  static $routes = [];
  
  public static function setUpBeforeClass()
  {
    static::$routeRefereesAll = function($path,$context = null)
    {
      if ($path !== '/referees') return false;
        
      return [
        '_action' => 'some action',
        '_roles'  => ['ROLE_ASSIGNOR']
      ];
    };
    static::$routeRefereesOne = function($path,$context = null)
    {
      $matches = [];
        
      if (!preg_match('#^/referees/(\d+$)#', $path, $matches)) return false;

      return [
        'id'      => (int)$matches[1],
        '_action' => 'some action',
        '_roles'  => ['ROLE_ASSIGNOR']
      ];
    };
    static::$routeRefereesBoth = function($path, $context = null, $getPrefix = false)
    {
      if ($getPrefix) return '/referees';
      
      $params = [
        'id'      => null,
        '_action' => 'some action',
        '_roles'  => ['ROLE_ASSIGNOR']
      ];
      if ($path === '/referees') 
      {
        if (!in_array($context['method'],['GET','POST'])) return false;

        return $params;
      }
      $matches = [];
        
      if (!preg_match('#^/referees/(\d+$)#', $path, $matches)) return false;

      $params['id'] = (int)$matches[1];
        
      return $params;
    };
    static::$routeProjectGamesOne = function($path, $context = null, $getPrefix = false)
    {
      if ($getPrefix) return '/projects';
      
      $matches = [];
        
      // /projects/ng2014/games/42
      if (!preg_match('#^/projects/(\w+)/games/(\d+$)#', $path, $matches)) return false;
        
      return [
        'projectKey' =>      $matches[1],
        'gameNum'    => (int)$matches[2],
        '_action'    => 'some action',
        '_roles'     => ['ROLE_ASSIGNOR']
      ];
    };
    static::$routes =
    [
      'referees_both' => static::$routeRefereesBoth,
      'project_games' => static::$routeProjectGamesOne,
    ];
  }
}
