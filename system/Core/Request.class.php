<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class Request {
	
	private $realRequest;
	
    public function __construct() {
    	$class = ucfirst(strtolower($_SERVER['REQUEST_METHOD'])) . 'Request';
    	$this->realRequest = new $class();
    }
    
    public function __get($name) {
    	return $this->realRequest->$name;
    }
}

?>
