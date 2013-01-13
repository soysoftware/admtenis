<?php
namespace Flush\Type;
/**
 * Clase para el tipo de dato Bool. Hereda de Type
 */

class Bool extends BaseType {
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