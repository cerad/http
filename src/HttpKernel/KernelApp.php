<?php

namespace Cerad\Component\HttpKernel;

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\ResponseJson;

use Cerad\Component\DependencyInjection\Container;

use Cerad\Component\HttpKernel\Event\KernelRequestEvent;
use Cerad\Component\HttpKernel\Event\KernelResponseEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

class KernelApp
{
  const REQUEST_TYPE_MASTER = 1;
  const REQUEST_TYPE_SUB    = 2;

  /** @var  Container $container */
  protected $container;
  protected $environment;
  protected $debug;
  protected $booted = false;
  
  public function __construct($environment = 'prod', $debug = false)
  {
    $this->environment = $environment;
    $this->debug = (bool)$debug;
  }
  /**
   * @return Container
   */
  public function getContainer() { return $this->container; }
  
  // Make this public for testing
  public function boot()
  {
    if ($this->booted) return;
    
    $this->container = $container = new Container();
    
    $this->registerServices($container);

    $this->booted = true;
  }
  protected function registerServices(Container $container)
  {
    new KernelServices($container);
  }
  public function handle(Request $request, $requestType = self::REQUEST_TYPE_MASTER)
  {
    // Boot on first request
    if (!$this->booted) $this->boot();
    
    try
    {
      return $this->handleRaw($request,$requestType);
    } 
    catch (\Exception $ex) // TODO: Implement Exception listener
    {
      $class = get_class($ex);
      $message = $ex->getMessage();
      switch($class)
      {
        case 'Exception':
          $code = 404;
          break;
        case 'Symfony\Component\Security\Core\Exception\AccessDeniedException':
          $code = 401;
          break;
        default:
          $code = 401;
      }
      $response = new ResponseJson(['error' => $message],$code);
      
      // Need this so auth headers get set
      // TODO: What happens if another exception is thrown?
      $dispatcher = $this->container->get('event_dispatcher');
      return $this->dispatchResponse($dispatcher,$request,$response);
    }
  }
  protected function handleRaw(Request $request, $requestType)
  {
    // Add request
    $requestStack = $this->container->get('request_stack');
    $requestStack->push($request);

    // Match the route
    $matcher = $this->container->get('route_matcher');
    $match   = $matcher->match($request->getRoutePath());
    if (!$match) {
      throw new \Exception('No match for ' . $request->getRoutePath());
    }
    
    $request->setAttributes($match);
    
    // Dispatcher
    $dispatcher = $this->container->get('event_dispatcher');
    
     // Dispatch request event
    $response = $this->dispatchRequest($dispatcher,$request,$requestType);
    if ($response)
    {
      $response = $this->dispatchResponse($dispatcher,$request,$response);
      $requestStack->pop($request);
      return $response;
    }

    /** @var callable $action */
    $action = $request->getAttribute('_action');
    if ($action)
    {
      $response = $action($request);
    }
    /** @var callable $view */
    $view = $request->getAttribute('_view');
    if ($view)
    {
      $response = $view($request,$response);
    }
    if (!$response)
    {
      die('no response');
    }
    // Dispatch response event
    $response = $this->dispatchResponse($dispatcher,$request,$response);
    
    // Clean up
    $requestStack->pop($request);
    
    return $response;
  }
  protected function dispatchRequest(EventDispatcher $dispatcher,$request,$requestType)
  {
    $requestEvent = new KernelRequestEvent($request,$requestType);
    $dispatcher->dispatch(KernelRequestEvent::name,$requestEvent);
    return $requestEvent->getResponse();
  }
  protected function dispatchResponse(EventDispatcher $dispatcher,$request,$response)
  {
    $responseEvent = new  KernelResponseEvent($request,$response);
    $dispatcher->dispatch(KernelResponseEvent::name,$responseEvent);
    return $responseEvent->getResponse();
  }
}
