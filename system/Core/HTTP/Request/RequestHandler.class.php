<?php

namespace Flush\Core\HTTP\Request;

class RequestHandler
{
    private $request;

    public function __construct() {
    	$requestClass = ucfirst(strtolower($_SERVER['REQUEST_METHOD'])) . 'Request';
    	$this->request = new $requestClass;
    }
    
    public function __get($name) {
    	return $this->request->$name;
    }
}
?>