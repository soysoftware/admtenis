<?php

/**
 * Clase constraint Positive
 */

abstract class Type_Constraint_Positive extends Type_Constraint_Constraint {
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
		throw new Exception_ConstraintException('debe ser un número positivo');
	}
}


?>