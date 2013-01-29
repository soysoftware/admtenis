<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class GetRequest {
	public function request($var) {
		return $_GET[$var];
	}

	public function getObject() {
		return (object)$_GET;
	}
}

?>
