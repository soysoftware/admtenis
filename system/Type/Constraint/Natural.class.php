<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint Natural
 */

abstract class Natural extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if ((is_integer($val) && $val >= 0) || !$settings) {
			return true;
		}
		throw new Exception\ConstraintException('debe ser un número natural (entero y mayor o igual a cero)');
	}
}


?>