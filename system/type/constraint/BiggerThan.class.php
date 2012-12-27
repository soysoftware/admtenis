<?php

/**
 * Clase constraint BiggerThan
 */

abstract class Constraint_BiggerThan extends Constraint_Constraint {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = 0) {
		if ($val > $settings) {
			return true;
		}
		throw new Exception_ConstraintException('debe ser mayor que ' . $settings);
	}
}

?>