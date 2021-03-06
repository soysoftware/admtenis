<?php
namespace Flush\Type;
/**
 * Clase para el tipo de dato Date. Hereda de Type
 */

class Date extends BaseType {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if( parent::$validationsEnabled && !preg_match('/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/', $val)){
			throw new Exception_TypeException($val , 'Date');
		}
		return true;
	}
}


?>