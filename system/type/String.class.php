<?php

/**
 * Clase para el tipo de dato String. Hereda de Type
 */

class Type_String extends Type_Type {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if ( !is_string($val) ) {
			throw new Exception_TypeException($val , 'String');
		}
		return true;
		
	}
}


?>