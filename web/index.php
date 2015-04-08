<?php
error_reporting(E_ALL);

require __DIR__  . '/../vendor/autoload.php';

use Cerad\Component\HttpMessage\Request;
use Cerad\Component\HttpMessage\Response;
use Cerad\Component\HttpMessage\ResponseJson;

use Symfony\Component\HttpFoundation\Request      as SymfonyRequest;
//  Symfony\Component\HttpFoundation\Response     as SymfonyResponse;
//  Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

// $request = new Request($_SERVER);
$request = SymfonyRequest::createFromGlobals();

$user = 'Art H';

if (false && $request->isPost())
{
  $content = $request->getContent();
  
  $user = $content['user'];
  
  if ($request->isJson())
  {
    $response = new ResponseJson($content,201);
    $response->send();
    return;
  }
}
ob_start();
require 'app.html';
$html = ob_get_clean();
$response = new Response($html);
$response->send();
?>
