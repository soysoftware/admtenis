<?php

//include bootstrap.inc.php

require_once 'Core/Controller.class.php';
require_once 'Core/NotFoundController.class.php';
require_once 'Core/JsonResponse.class.php';
require_once 'Core/FlushResponse.class.php';
require_once 'Core/FlushRequest.class.php';
require_once '../application/controllers/clubes/clubes.php';
require_once '../application/controllers/login/login.php';


FlushRequest::controller()->run();

/*
$request = explode('/', $_GET['request']);

$controller = (!empty($request[0])) ? $request[0] : 'home';
$action = (!empty($request[1])) ? $request[1] : 'index';
$parameter = (!empty($request[2])) ? $request[2] : null ;

include $controller . '/' . $controller . '.php';
call_user_func($action, $parameter);
*/

?>