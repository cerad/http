<?php
error_reporting(E_ALL);

require __DIR__  . '/../vendor/autoload.php';

use Cerad\Component\HttpMessage\Request as CeradRequest;
use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponseJson;

use Symfony\Component\HttpFoundation\Request      as SymfonyRequest;
//  Symfony\Component\HttpFoundation\Response     as SymfonyResponse;
//  Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

$ceradRequest = new CeradRequest($_SERVER);

$ceradUri = $ceradRequest->getUri();

$ceradServer =$ceradRequest->getServerParams();

$symfonyRequest = SymfonyRequest::createFromGlobals();

$user = 'Art H';

$baseHref = $ceradRequest->getBaseHref();

if ($ceradRequest->isPost())
{
  $content = $ceradRequest->getContent();
  
  $user = $content['user'];
  
  if ($ceradRequest->isJson())
  {
    $response = new ResponseJson($content,201);
    $response->send();
    return;
  }
  if ($ceradRequest->isForm())
  {
    // Redirect if had a session to store things in
  }
}
ob_start();
require 'app.html';
$html = ob_get_clean();
$response = new Response($html);
$response->send();

/* ====================================================
 * Everything breaks as soon as I go to /web/index.php, can't find css etc
 * Need:  <base href="http://localhost:8080/web/">
 * Works: <base href="/web/">
 * 
 * SymfonyRequest::getBasePath . '/' represents the base href
 * It is not directly available from the $_SERVER
 * 
 */
?>
