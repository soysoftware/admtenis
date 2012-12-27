<?php

/**
 * Clase constraint RegEx
 */

abstract class Constraint_RegEx extends Constraint_Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = '') {
		if (preg_match($settings, $val)) {
			return true;
		}
		throw new Exception_ConstraintException('debe cumplir con la expresión regular: ' . $settings);
	}
}


?>