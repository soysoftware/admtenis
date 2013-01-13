<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint SmallerThan
 */

abstract class SmallerThan extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = 0) {
		if ($val < $settings) {
			return true;
		}
		throw new Exception\ConstraintException('debe ser menor que ' . $settings);
	}
}

?>