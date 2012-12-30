<?php

/**
 * Clase constraint MaxLength
 */

abstract class Constraint_MaxLength extends Constraint_Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = 0) {
		if (strlen($val) <= $settings) {
			return true;
		}
		throw new Exception_ConstraintException('no debe superar los ' . $settings . ' caracteres');
	}
}


?>