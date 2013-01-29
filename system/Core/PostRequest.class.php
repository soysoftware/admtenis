<?php

namespace Flush\Core;

class PostRequest {
    
	public function __get($name) {
		return $_POST[$name];
	}
	
}

?>
