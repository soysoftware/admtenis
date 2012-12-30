<?php

/**
 * Clase de Exception para los errores generados por las constraints
 */

class Exception_ConstraintException extends Exception_TypeException {
	public function __construct($msg = 'no cumple con todas las restricciones'){
        parent::__construct($msg);
	}
}

?>