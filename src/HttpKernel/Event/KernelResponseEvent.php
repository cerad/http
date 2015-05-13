<?php

namespace Cerad\Component\HttpKernel\Event;

use Symfony\Component\EventDispatcher\Event;

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\Response;

class KernelResponseEvent extends Event
{
  const name = 'CeradKernelResponse';

  /** @var  Request $request */
  private $request;
  /** @var  Response $response */
  private $response;
  
  public function __construct(Request $request,Response $response)
  {
    $this->request  = $request;
    $this->response = $response;
  }
  public function getRequest () { return $this->request;  }
  public function getResponse() { return $this->response; }
  public function hasResponse() { return $this->response  ? true : false; }
  
  public function setResponse(Response $response) { $this->response = $response; }
}