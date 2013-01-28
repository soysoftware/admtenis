<?php
namespace Flush\Exception;
/**
 * Clase de Exception para los errores generados por las constraints
 */

class ConstraintException extends \Exception{
	
	public function __construct($msg){
		$this->message = '[Constraint Error] Field ' . debug_backtrace()[5]['args'][0] . ' ' . $msg;
	}
}

?>