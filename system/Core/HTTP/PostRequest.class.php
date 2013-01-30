<?php

namespace Flush\Core\HTTP;

class PostRequest {
    
	public function __get($name) {
		return $_POST[$name];
	}
	
}

?>
