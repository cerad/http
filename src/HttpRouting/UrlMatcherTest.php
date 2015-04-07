<?php

namespace Cerad\Component\HttpRouting;

require __DIR__  . '/../../vendor/autoload.php';

class UrlMatcherTest extends RoutingTestBase
{
  public function test1()
  {
    $matcher = new UrlMatcher(static::$routes);
    
    $matched1 = $matcher->match('/referees/42');
    $this->assertEquals('42',$matched1['id']);
    
    $this->assertFalse($matcher->match('/referees'));
    
    $matched2 = $matcher->match('/referees',['method' => 'GET']);
    $this->assertEquals('referees_both',$matched2['_route']);
    
    $matched3 = $matcher->match('/projects/ng2014/games/42');
    $this->assertEquals(      42,$matched3['gameNum']);
    $this->assertEquals('ng2014',$matched3['projectKey']);
    $this->assertEquals('project_games',$matched3['_route']);
    
    $this->assertFalse($matcher->match('/projects/xxx/games/current'));

    $match = static::$routeRefereesBoth;
    $this->assertEquals('/referees',$match(null,null,true));
  }
  public function testContext()
  {
    
  }
}