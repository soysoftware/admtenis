<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint NotZero
 */

abstract class NotZero extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if ($val !== 0 || !$settings) {
			return true;
		}
		throw new Exception\ConstraintException('no debe ser cero');
	}
}


?>