<?php
/**
 * Clase de Exception para violaciones de seguridad
 */

class Exception_InternalSecurityException extends Exception {
	public function __construct($msg = 'Error de seguridad interna'){
		parent::__construct($msg);
	}
}

?>