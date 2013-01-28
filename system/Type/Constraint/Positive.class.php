<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint Positive
 */

abstract class Positive extends Constraint {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if ($val > 0 || !$settings) {
			return true;
		}
		throw new Exception\ConstraintException('debe ser un número positivo');
	}
}


?>