<?php
namespace Flush\Type;
/**
 * Clase para el tipo de dato Float. Hereda de Type
 */

class Float extends BaseType {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if (parent::$validationsEnabled && !is_float($val)) {
			throw new Exception_TypeException($val , 'Float');
		}
		return true;
		
	}
}

?>