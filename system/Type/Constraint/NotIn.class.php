<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint NotIn
 */

abstract class NotIn extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = array()) {
		if (!in_array($val, $settings)) {
			return true;
		}
		throw new Exception\ConstraintException('no debe ser uno de los siguientes valores: ' . implode(', ', $settings));
	}
}

?>