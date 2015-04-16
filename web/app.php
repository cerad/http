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

$_serverPathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'UNDEFINED';

if ($ceradRequest->isMethodPost())
{
  $content = $ceradRequest->getParsedBody();
  
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

?>
