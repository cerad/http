<?php
namespace Cerad\Component\HttpMessage;

class ResponseRedirect extends Response
{
  public function __construct($url, $statusCode = 302, $headers = [])
  {
    $headers['Location'] = $url;
    
    $urlx = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    
    $contents = <<<EOT
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="refresh" content="$urlx" />
  <title>Redirecting to $urlx</title>
</head>
<body>
  Redirecting to <a href="$urlx">$urlx</a>.
</body>
</html>
EOT;
    parent::__construct($contents,$statusCode,$headers);
  }
}