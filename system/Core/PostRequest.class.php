<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class PostRequest {
    
	public function __get($name) {
		return $_POST[$name];
	}
	
}

?>
