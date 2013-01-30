<?php

namespace Flush\Core\HTTP;

class GetRequest {
	public function __get($name) {
		return $_GET[$name];
	}
}

?>
