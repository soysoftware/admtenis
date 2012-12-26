<?php

/**
 * Clase constraint Even
 */

abstract class Constraint_Even extends Constraint_Constraint {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = true) {
		if ((is_integer($val) && ($val % 2 == 0)) || !$settings) {
			return true;
		}
		throw new Exception_ConstraintException('debe ser un número par');
	}
}

?>