<?php
error_reporting(E_ALL);

require __DIR__  . '/../vendor/autoload.php';

use Cerad\Component\HttpMessage\Request;

$request = new Request($_SERVER);
$name = null;
if ($request->getMethod() == 'POST')
{
  $content = $request->getContent();
  $user = $content['user'];
}
?>
<html>
  <head>
    <title>Request</title>
  </head>
  <body>
    <form method="POST">
      <input type="text" name="user" value="Art H" />
      <input type="submit" value="Submit" />
    </form>
    <hr>
    <div>User: <?php echo $user; ?></div>
  </body>
</html>
  