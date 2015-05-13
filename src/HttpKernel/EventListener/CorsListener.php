<?php
namespace Cerad\Component\HttpKernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponsePreflight;

use Cerad\Component\HttpKernel\Event\KernelRequestEvent;
use Cerad\Component\HttpKernel\Event\KernelResponseEvent;

/* ========================================================
 * http://www.html5rocks.com/en/tutorials/cors/
 */
class CorsListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return 
    [
      KernelRequestEvent ::name => [['onKernelRequest', 255]],
      KernelResponseEvent::name => [['onKernelResponse',  0]],
    ];
  }
  public function onKernelRequest(KernelRequestEvent $event)
  {
    if (!$event->isMasterRequest()) return;
    
    // Test got Cors Preflight
    $request = $event->getRequest();
    
    if (!$request->hasHeader('Origin')) return;
    
    if ($request->getMethod() !== 'OPTIONS') return;
    
    if (!$request->hasHeader('Access-Control-Request-Method')) return;
    
    // Assume Access-Control-Request-Method is valid, use default for caching
    
    $allowOrigin  = $request->getHeaderLine('Origin');
    $allowHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
    
    $response = new ResponsePreflight($allowOrigin,$allowHeaders);
   
    $event->setResponse($response);
    $event->stopPropagation();
  }
  public function onKernelResponse(KernelResponseEvent $event)
  {
    if (!$event->hasResponse()) return;

    /** @var Response $response */
    $response = $event->getResponse();
    
    if ($response->hasHeader('Access-Control-Allow-Origin')) return;
    
    $origin = $event->getRequest()->getHeaderLine('Origin');
    if (!$origin) return;
    
    $response = $response->withHeader('Access-Control-Allow-Origin',$origin);
    
    $event->setResponse($response);
  }
}
