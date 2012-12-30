<?php

/**
 * Clase constraint NotNull
 */

abstract class Type_Constraint_NotNull extends Type_Constraint_Constraint {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if (!is_null($val) || !$settings) {
			return true;
		}
		throw new Exception_ConstraintException('no debe ser nulo');
	}
}


?>