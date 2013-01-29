<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class PostRequest {
	public function request($var) {
		return $_POST[$var];
	}

	public function getObject() {
		return (object)$_POST;
	}
}

?>
