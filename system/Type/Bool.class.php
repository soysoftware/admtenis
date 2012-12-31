<?php

/**
 * Clase para el tipo de dato Bool. Hereda de Type
 */

class Type_Bool extends Type_Type {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if ( parent::$validationsEnabled && !is_bool($val)) {
			throw new Exception_TypeException($val , 'Bool');
		}
		return true;
	}
}

?>