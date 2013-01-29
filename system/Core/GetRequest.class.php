<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class GetRequest {
    
	public function __get($name) {
		return $_GET[$name];
	}
		
}

?>
