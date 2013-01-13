<?php
namespace Flush\Exception;
/**
 * Clase de Exception para los errores generados por las constraints
 */

class ConstraintException extends TypeException {
	public function __construct($msg = 'no cumple con todas las restricciones'){
        parent::__construct($msg);
	}
}

?>