<?php

//include bootstrap.inc.php

require_once 'Core/Controller.class.php';
require_once 'Core/NotFoundController.class.php';
require_once 'Core/JsonResponse.class.php';
require_once 'Core/FlushResponse.class.php';
require_once 'Core/FlushRequest.class.php';
require_once '../application/controllers/clubes/clubes.php';
require_once '../application/controllers/login/login.php';


$request = ($_SERVER['REQUEST_METHOD'] == 'GET' ? new GetRequest() : new PostRequest());
$aux = explode('/', $request->request('request'));
$controllerName = ((!empty($aux[0])) ? ucfirst($aux[0]) : 'NotFound') . 'Controller';
$actionName = (!empty($aux[1])) ? strtolower($aux[1]) : 'index';
$parameter = (!empty($aux[2])) ? $aux[2] : null;

$controller = class_exists($controllerName) ? new $controllerName($request) : new NotFoundController($request);

$controller->run($actionName, $parameter);

/*
$request = explode('/', $_GET['request']);

$controller = (!empty($request[0])) ? $request[0] : 'home';
$action = (!empty($request[1])) ? $request[1] : 'index';
$parameter = (!empty($request[2])) ? $request[2] : null ;

include $controller . '/' . $controller . '.php';
call_user_func($action, $parameter);
*/

?>