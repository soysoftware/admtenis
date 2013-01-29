<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

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
