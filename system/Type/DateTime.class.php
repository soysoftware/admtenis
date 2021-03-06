<?php
namespace Flush\Type;
/**
 * Clase para el tipo de dato DateTime. Hereda de Type
 */

class DateTime extends BaseType {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if( parent::$validationsEnabled && !preg_match('\20\d{2}-[0-1][0-2]-([0-2][0-9]|3[0-1])\s[0-2][0-3]:[0-5][0-9]:[0-5][0-9]', $val)){
			throw new Exception_TypeException($val , 'Datetime');
		}
		return true;
	}
}

?>