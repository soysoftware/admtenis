<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint MinLength
 */

abstract class MinLength extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = 0) {
		if (strlen($val) >= $settings) {
			return true;
		}
		throw new Exception\ConstraintException('no debe tener menos de ' . $settings . ' caracteres');
	}
}


?>