<?php

/**
 * @author Lucas Ceballos
 * @since 10/12/2012
 * @version 0.0.1
 * 
 */

abstract class Core_TypedObject extends Core_Base {
	
	public function __construct($idObj = null, $readOnly = false){
		$this->createAttributes();
		Type_Type::disableValidations();
		parent::__construct($idObj, $readOnly);
		Type_Type::enableValidations();
	}	
	
	//Magic Methods
	
	/**
	 * Magic Method para el GET de una variable que no existe o no puede acceder (Lazy Loading)
	 * Además contempla que la variable puede ser de un tipo personalizado (Type)
	 *
	 * @param string
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($name) {
		if ( method_exists($this, $method = 'get' . ucfirst($name)) ) {
			return $this->$method();
		}
		$name = property_exists($this, '_' . $name) ? '_' . $name : ( property_exists($this, '__' . $name) ? '__' . $name : false );
		if ( !$name ) {
			throw new Exception_InternalSecurityException('No existe la propiedad ' . $name . ' o el metodo para accederla');
		}
		return Type_Type::isTyped($this->$name) ? $this->$name->val : $this->val;
	}
	
	public function __set($name, $value) {
		$method = 'set' . ucfirst($name);
		$name = '_' . $name;
		if ( !method_exists($this, $method) && !property_exists($this, $name) ) {
			throw new Exception('No existe el método ' . $method . ' ni la propiedad ' . $name . ' en la clase "' . get_class($this) . '"');
		}
		Exception_TypeException::$field = $name;
		if ( method_exists($this, $method) ) {
			return $this->$method($this->db->realEscapeString($value));
		} else {
			return Type_Type::isTyped($this->$name) ? $this->$name->val = $this->db->realEscapeString($value) : $this->$name = $this->db->realEscapeString($value);
		}
	}	
	
	// Setters
	protected function setId($value){
		$pkName = static::_primaryKeyName;
		return Type_Type::isTyped($this->$pkName) ? $this->$pkName->val = $value : $this->$pkName = $value;
	}
	
	// Getters
	protected function getId(){
		$pkName = static::_primaryKeyName;
		return Type_Type::isTyped($this->$pkName) ? $this->$pkName->val : $this->$pkName; 
	}
	
	protected function getDescriptor(){
		return Core_Descriptor::get(get_class($this));
	}
	
	/**
	 * Metodo encargado de leer el descriptor y crear los objetos 
	 * correspondientes a cada atributo del objeto
	 *
	 * @return void
	 */
	protected final function createAttributes() {		
		foreach ( $this->descriptor as $attrName => $attrConfig) {
			if ( isset($attrConfig['Type']) ) {
				$typeClass = Type_Type::PREFIX . $attrConfig['Type'];
				if ( class_exists($typeClass) ) {
					if ( isset($attrConfig['Constraints']['ReferenceValue']) ) {
						$attrConfig['Constraints']['ReferenceValue'] = &$this->$attrConfig['Constraints']['ReferenceValue'];
					}
					if ( $typeClass == 'Type_ObjectList') {
						$attrConfig['Constraints']['FromClass'] = get_class($this);
					}
					$arg = isset($attrConfig['Constraints']) ? $attrConfig['Constraints'] : null;
					$this->$attrName = new $typeClass($arg);
				}
			}
		}
	}	
	
} 

?>