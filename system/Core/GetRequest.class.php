<?php

namespace Flush\Core;

class GetRequest {
	public function __get($name) {
		return $_GET[$name];
	}
}

?>
