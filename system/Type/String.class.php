<?php
namespace Flush\Type;
/**
 * Clase para el tipo de dato String. Hereda de Type
 */

class String extends BaseType {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if ( parent::$validationsEnabled && !is_string($val) ) {
			throw new Exception_TypeException($val , 'String');
		}
		return true;
		
	}
}


?>