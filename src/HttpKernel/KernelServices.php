<?php

namespace Cerad\Component\HttpKernel;

use Cerad\Component\HttpKernel\EventListener\CorsListener;

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\RequestStack;

use Cerad\Component\HttpRouting\UrlMatcher;
use Cerad\Component\DependencyInjection\Container;

use Symfony\Component\EventDispatcher\EventDispatcher;

class KernelServices
{
  public function __construct(Container $container)
  {
    $this->registerServices($container);
  }
  public function registerServices(Container $container)
  {
    // Me
    $container->set('kernel',$this);

    // Request stack
    $container->set('request_stack',function()
    {
      return new RequestStack();
    });
    /* =============================================
     * $this->context->getHost()
     * $this->context->getMethod()
     * $this->context->getScheme()
     */
    $container->set('request_context',function(Container $container)
    {
      /** @var Request $request */
      $request = $container->get('request_stack')->getMasterRequest();
      $context = [];
      $context['method'] = $request->getMethod();      
      return $context;
    });
    $container->set('route_matcher',function(Container $container)
    {
      $routes = [];
      $tags = $container->getTags('route');
      foreach($tags as $tag)
      {
        $serviceId = $tag['service_id'];
        $service   = $container->get($serviceId);
        $routes[$serviceId] = $service;
      }
      return new UrlMatcher
      (
        $routes,
        $container->get('request_context')
      );
    });
    $container->set('event_dispatcher',function(Container $container)
    {
      $dispatcher = new EventDispatcher();
      $tags       = $container->getTags('event_listener');
      foreach($tags as $tag)
      {
        $listener = $container->get($tag['service_id']);
        $dispatcher->addSubscriber($listener);
      }
      return $dispatcher;
    });
    $container->set('kernel_cors_listener',function()
    {
      return new CorsListener();
    },'event_listener');
  }

}