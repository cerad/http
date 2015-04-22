<?php
namespace Cerad\Component\HttpMessagePsr7;

use Psr\Http\Message\UriInterface as Psr7UriInterface;

use \InvalidArgumentException as Psr7InvalidArgumentException;

class Uri implements Psr7UriInterface
{ 
  protected $port     = null;
  protected $path     = '';
  protected $host     = '';
  protected $user     = '';
  protected $pass     = null;
  protected $query    = '';
  protected $scheme   = '';
  protected $fragment = '';
  
  /* ===================================================
   * Host stuff
   */
  protected function checkHost($host)
  {
    return ($host === null) ? '' : strtolower($host);
  }
  public function  getHost() { return $this->host; }
  public function withHost($host)
  {
    $new = clone $this;
    $new->host = $this->checkHost($host);
    return $new;
  }
  /* ======================================================
   * Scheme stuff
   */
  protected function checkScheme($schemeArg)
  {
    $schemeLower = ($schemeArg === null) ? '' : strtolower($schemeArg);
    switch($schemeLower)
    {
      case '':
      case 'http':
      case 'https':
        return $schemeLower;
    }
    throw new Psr7InvalidArgumentException('Invalid URI Scheme: ' . $schemeArg);
  }
  public function  getScheme() { return $this->scheme; }
  public function withScheme($scheme)
  {
    $new = clone $this;
    $new->scheme = $this->checkScheme($scheme);
    return $new;
  }
  /* ======================================================
   * Port stuff, dependent on scheme
   */
  protected function checkPort($portArg)
  {
    if ($portArg === null) return $portArg;
    
    $portInt = (int)$portArg;
    if ($this->scheme === 'http'  && $portInt === 80 ) $portInt = null;
    if ($this->scheme === 'https' && $portInt === 337) $portInt = null;
    
    return $portInt;
  }
  public function  getPort() { return $this->port; }
  public function withPort($port)
  {
    $new = clone $this;
    $new->port = $this->checkPort($port);
    return $new;
  }
  /* ======================================================
   * Path stuff
   */
  protected function checkPath($pathArg)
  {
    return ($pathArg === null) ? '' : $pathArg;
  }
  public function  getPath() { return $this->path; }
  public function withPath($path)
  {
    $new = clone $this;
    $new->path = $this->checkPath($path);
    return $new;
  }
  /* ======================================================
   * Query stuff
   */
  protected function checkQuery($queryArg)
  {
    return ($queryArg === null) ? '' : $queryArg;
  }
  public function  getQuery() { return $this->query; }
  public function withQuery($query)
  {
    $new = clone $this;
    $new->query = $this->checkQuery($query);
    return $new;
  }
  /* ======================================================
   * Fragment stuff
   */
  protected function checkFragment($fragmentArg)
  {
    return ($fragmentArg === null) ? '' : $fragmentArg;
  }
  public function  getFragment() { return $this->fragment; }
  public function withFragment($fragment)
  {
    $new = clone $this;
    $new->fragment = $this->checkFragment($fragment);
    return $new;
  }
  /* ======================================================
   * User Info
   */
  protected function checkUser($user) 
  { 
    return $user !== null ? $user : ''; 
  }
  protected function checkPass($pass) 
  { 
    return $pass ? $pass : null; 
  }
  public function getUserInfo() 
  { 
    return $this->pass ? $this->user . ':' . $this->pass : $this->user;
  }
  public function withUserInfo($user,$pass = null)
  {
    $new = clone $this;
    $new->user = $this->checkUser($user);
    $new->pass = $this->checkPass($pass);
    return $new;  
  }
  /* ===============================================
   * Authority : [user-info@]host[:port]
   */
  public function getAuthority()
  {
    $userInfo = $this->getUserInfo();
    
    $userInfoHost = $userInfo ? $userInfo . '@' . $this->host : $this->host;
    
    $authority = $this->port ? $userInfoHost . ':' . $this->port : $userInfoHost;
    
    return $authority;
  }
  /* ===============================================
   * Stich everything back together
   */
  public function __toString()
  {
    $scheme = $this->scheme ? $this->scheme . ':' : null;
    
    $authority = $this->getAuthority();
    
    $schemeAuthority = $authority ? $scheme . '//' . $authority : $scheme;
    
    $path = $this->path ? $this->path : '/';
    
    $schemeAuthorityPath = $schemeAuthority . $path;
    
    $schemeAuthorityPathQuery = $this->query ? $schemeAuthorityPath . '?' . $this->query : $schemeAuthorityPath;
    
    $schemeAuthorityPathQueryFragment = 
      $this->fragment ?
      $schemeAuthorityPathQuery . '#' . $this->fragment :
      $schemeAuthorityPathQuery;
    
    return $schemeAuthorityPathQueryFragment;
  }
}
