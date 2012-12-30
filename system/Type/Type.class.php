<?php

/**
 * Clase base de las clases de tipos de datos como String, Int, Date
 * Cuando se instancia, se guarda la configuración del descriptor del objeto y la chequea cada vez que se intenta asignar un valor en $val
 *
 */

/**
 * @property mixed	$val
 */
abstract class Type_Type {
	const PREFIX = 'Type_';

	protected	$constraints;
	protected	$val;

	/**
	 * Constructor genérico para todas las clases Type
	 *
	 * @param array
	 *
	 */
	public function __construct($constraints = null) {
		$this->constraints = $constraints;
	}

	/**
	 * Magic Method para el GET de una variable que no existe o no puede acceder
	 * Lazy Loading
	 *
	 * @param string $name
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if (!method_exists($this, $method)) {
			throw new Exception_InternalSecurityException('No existe el método ' . $method . ' en la clase "' . get_class($this) . '"');
		}
		return $this->$method();
	}

	/**
	 * Magic Method para el SET de una variable que no existe o no puede acceder
	 * Lazy Loading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @throws Exception
	 * @return mixed
	 *
	 */
	public function __set($name, $value) {
		$method = 'set' . ucfirst($name);
		if (!method_exists($this, $method)) {
			throw new Exception_InternalSecurityException('No existe el método ' . $method . ' en la clase "' . get_class($this) . '"');
		}
		$this->$method($value);
	}

	/**
	 * Setter para el valor literal del atributo que este objeto representa
	 * 
	 * @param mixed $val
	 */
	protected function setVal($val) {
		if ( $this->validate($val) && $this->validateConstraints($val) ) {
			$this->val = $val;
		}
	}

	/**
	 * Getter para el valor literal del atributo que este objeto representa
	 * 
	 * @return mixed
	 */
	protected function getVal(){
		return $this->val;
	}

	/**
	 * Método que se manda a comprobar que el valor que se le intenta asignar al atributo cumpla con todas sus constraints
	 * 
	 * @param mixed $val
	 * @throws Exception_TypeException
	 */
	abstract protected function validate($val);
	
	private function validateConstraints($val) {
		if ( is_array($this->constraints) ) {
			foreach ( $this->constraints as $constraintName => $constraintValue ) {
				$constraint = Constraint_Constraint::PREFIX . $constraintName;
				$constraint::check($val, $constraintValue);
			}
		}
		// Si el trace llega aca no hubo excepciones, por lo tanto se pasaron todos los chequeos
		return true;
	}
	
	public static function isTyped($value){
		return is_a($value,'Type_Type');
	}
}

?>