<?php
error_reporting(E_ALL);

require __DIR__  . '/../vendor/autoload.php';

use Cerad\Component\HttpMessage\Request;

$request = new Request($_SERVER);

$user = 'Art H';

if ($request->getMethod() == 'POST')
{
  $content = $request->getContent();
  $user = $content['user'];
}
?>
<!DOCTYPE html>
<html lang="en" ng-strict-di>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HTTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <style>
      .border-dark 
      {
        border-style: solid;
        border-color: #666;
        border-width: 1px;
      }
      .form-control-inline 
      {
        min-width: 0;
        width: auto;
        display: inline;
      }
    </style>
  </head>
  <body style="background-color: palegoldenrod">
    <div class="container" style="background-color: paleturquoise ">
      <div class="row border-dark">
        <div class="col-sm-12">
          <h1>HTTP Testing Page</h1>
        </div>
      </div>
      <div class="row border-dark">
        <div class="col-sm-12">
          <form class="form-inline" role="form" method="POST">
            <div class="form-group">
              <label  for="user">User Name:</label>
              <input type="text" name="user" class="form-control form-control-inline" value="<?php echo $user; ?>">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>
      </div>
      <div class="row border-dark">
        <div class="col-sm-12">
          <div>User: <?php echo $user; ?></div>
        </div>
      </div>
    </div>
    <script src="js/vendor.js"></script>
  </body>
</html>
  