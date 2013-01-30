<?php

namespace Flush\Core\HTTP;

class Request {
	
	public function __construct() {
	    $className = ucfirst(strtolower($_SERVER['REQUEST_METHOD'])) . 'Request';
	    	
	}
	
	public final function server($var) {
		return $_SERVER[$var];
	}

	public function request($var) {
		return $_REQUEST[$var];
	}

	public function getObject() {
		return (object)$_POST;
	}
}

?>
