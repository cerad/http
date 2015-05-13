<?php

namespace Cerad\Component\HttpRouting;

class RouteTest extends RoutingTestBase
{
  public function testRefereesAll()
  {
    $match = static::$routeRefereesAll;
    
    $matched1 = $match('/referees');
    $this->assertEquals('some action',$matched1['_action']);
    
    $matched2 = $match('/referees/123');
    $this->assertFalse($matched2);
  }
  public function testRefereesOne()
  {
    $match = static::$routeRefereesOne;
    
    $matched1 = $match('/referees/42');
    $this->assertEquals('42',$matched1['id']);
    
    $this->assertFalse($match('/referees'));
    $this->assertFalse($match('/referees/'));
    $this->assertFalse($match('/referees/23a'));
    $this->assertFalse($match('/referees/23/'));
  }
  public function testRefereesBoth()
  {
    $match = static::$routeRefereesBoth;
    
    $matched1 = $match('/referees/42');
    $this->assertEquals('42',$matched1['id']);
    
    $matched2 = $match('/referees');
    $this->assertEquals(null,$matched2['id']);
  }
  public function testProjectGames()
  {
    $match = static::$routeProjectGamesOne;
    
    $matched1 = $match('/projects/ng2014/games/42');
    $this->assertEquals(      42,$matched1['gameNum']);
    $this->assertEquals('ng2014',$matched1['projectKey']);
    
    $this->assertFalse($match('/referees/'));
    $this->assertFalse($match('/projects'));
    $this->assertFalse($match('/projects/'));
    $this->assertFalse($match('/projects/xxx/'));
    $this->assertFalse($match('/projects/xxx/games'));
    $this->assertFalse($match('/projects/xxx/games/current'));
    $this->assertFalse($match('/projects/xxx/games/42a'));
  }
}
