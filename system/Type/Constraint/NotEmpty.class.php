<?php

namespace Flush\Type\Constraint;
use Flush\Exception;

/**
 * Clase constraint NotNull
 */

abstract class NotEmpty extends Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if (strlen($val) > 0 || !$settings) {
			return true;
		}
		throw new Exception\ConstraintException('can not be empty');
	}
}


?>