<?php

namespace Cerad\Component\HttpKernel\Event;

use Symfony\Component\EventDispatcher\Event;

use Cerad\Component\HttpKernel\KernelApp;

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\Response;

class KernelRequestEvent extends Event
{
  const name = 'CeradKernelRequest';
  
  private $type;
  /** @var Request $request */
  private $request;
  /** @var Response $response */
  private $response;
  
  public function __construct(Request $request,$type = KernelApp::REQUEST_TYPE_MASTER)
  {
    $this->type    = $type;
    $this->request = $request;
  }
  public function isMasterRequest() { return $this->type === KernelApp::REQUEST_TYPE_MASTER; }

  public function getRequest () { return $this->request;  }
  public function getResponse() { return $this->response; }
  public function hasResponse() { return $this->response  ? true : false; }
  
  public function setResponse(Response $response) { $this->response = $response; }
}