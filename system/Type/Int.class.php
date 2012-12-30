<?php

/**
 * Clase para el tipo de dato Int. Hereda de Type
 */

class Type_Int extends Type_Type {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if ( !is_null($val) && !is_int($val) && !ctype_digit($val) ) {
			throw new Exception_TypeException($val , 'Int');
		}
		return true;
	}
}


?>