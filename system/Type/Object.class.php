<?php

/**
 * Clase para el tipo de dato Objeto. Hereda de Type
 */

class Type_Object extends Type_Type {
	
	private $class;
	private $referenceValue = null;
	
	public function __construct(&$constraints){
		if ( !isset($constraints['Class']) || empty($constraints['Class']) ) {
			throw new Exception_InternalSecurityException('No se ha especificado clase para el objeto de tipo object');
		}
		$this->class = $constraints['Class'];
		if ( isset($constraints['ReferenceValue']) ) {
			$this->referenceValue = $constraints['ReferenceValue'];
		}
	}
	
	protected function getVal() {
		if ( !isset($this->val) ) {
			$class = $this->class;
			$this->val = new $class( self::isTyped($this->referenceValue) ? $this->referenceValue->val : $this->referenceValue);
		}
		return $this->val;
	}
	
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if ( !is_int($val) && !ctype_digit($val) ) {
			throw new Exception_TypeException($val , 'Int');
		}
		return true;
	}
}



?>