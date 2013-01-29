<?php

namespace Flush\Core;

class Request {
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
