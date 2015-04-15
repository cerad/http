<?php
namespace Cerad\Component\HttpMessagePsr7;

use Psr\Http\Message\UriInterface as Psr7UriInterface;

use \InvalidArgumentException as Psr7InvalidArgumentException;

use Cerad\Component\HttpMessagePsr7\Util as Psr7Util;

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
  public function getHost() { return $this->host; }
  
  public function withHost($hostArg)
  {
    $hostChecked = $this->checkHost($hostArg);
    
    return ($this->host === $hostChecked) ? $this : Psr7Util::setProp($this,'host',$hostChecked);
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
  public function getScheme() { return $this->scheme; }
  public function withScheme($schemeArg)
  {
    $schemeChecked = $this->checkScheme($schemeArg);
    
    return ($this->scheme === $schemeChecked) ? $this : Psr7Util::setProp($this,'scheme',$schemeChecked);
    
    // TODO: See if port needs to be updated (optional)
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
  public function getPort() { return $this->port; }
  public function withPort($portArg)
  {
    $portChecked = $this->checkPort($portArg);
    
    return ($this->port === $portChecked) ? $this : Psr7Util::setProp($this,'port',$portChecked);
  }
  /* ======================================================
   * Path stuff
   */
  protected function checkPath($pathArg)
  {
    return ($pathArg === null) ? '' : $pathArg;
  }
  public function getPath() { return $this->path; }
  public function withPath($pathArg)
  {
    $pathChecked = $this->checkPath($pathArg);
    
    return ($this->path === $pathChecked) ? $this : Psr7Util::setProp($this,'path',$pathChecked);
  }
  /* ======================================================
   * Query stuff
   */
  protected function checkQuery($queryArg)
  {
    return ($queryArg === null) ? '' : $queryArg;
  }
  public function getQuery() { return $this->query; }
  public function withQuery($queryArg)
  {
    $queryChecked = $this->checkQuery($queryArg);
    
    return ($this->query === $queryChecked) ? $this : Psr7Util::setProp($this,'query',$queryChecked);
  }
  /* ======================================================
   * Fragment stuff
   */
  protected function checkFragment($fragmentArg)
  {
    return ($fragmentArg === null) ? '' : $fragmentArg;
  }
  public function getFragment() { return $this->fragment; }
  public function withFragment($fragmentArg)
  {
    $fragmentChecked = $this->checkFragment($fragmentArg);
    
    return ($this->fragment === $fragmentChecked) ? $this : Psr7Util::setProp($this,'fragment',$fragmentChecked);
  }
  /* ======================================================
   * User Info
   */
  public function getUserInfo() 
  { 
    return $this->pass ? $this->user . ':' . $this->pass : $this->user;
  }
  public function withUserInfo($userArg,$passArg = null)
  {
    $userChecked = ($userArg === null) ?   '' : $userArg;
    $passChecked = ($passArg === null) ? null : $passArg;
    
    if (($this->user === $userChecked) && ($this->pass == $passChecked)) return $this;
    
    return Psr7Util::setProp($this,['user' => $userChecked, 'pass' => $passChecked]);    
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
