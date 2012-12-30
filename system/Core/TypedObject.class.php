<?php

/**
 * @author Lucas Ceballos
 * @since 10/12/2012
 * @version 0.0.1
 * 
 */

abstract class Core_TypedObject extends Core_Base {
	
	protected static $__descriptor;
	
	public function __construct($idObj = null, $readOnly = false){
		//if ( !is_array(static::$__descriptor) ) {
			$this->createDescriptor();
		//}
		$this->createAttributes();
		parent::__construct($idObj, $readOnly);
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
		if ( method_exists($this, 'get' . ucfirst($name)) ) {
			return $this->$method();
		}
		$name = property_exists($this, '_' . $name) ? '_' . $name : property_exists($this, '__' . $name) ? '__' . $name : false;
		if ( !$name ) {
			throw new Exception_InternalSecurityException('No existe la propiedad ' . $name . ' o el metodo para accederla');
		}
		return Type_Type::isTyped($this->$name) ? $this->$name->val : $this->val;
	}
	
	protected function getId(){
		$pkName = static::_primaryKeyName;
		return Type_Type::isTyped($this->$pkName) ? $this->$pkName->val : $this->$pkName; 
	}
	
	public function __set($name, $value) {
		$method = 'set' . ucfirst($name);
		$name = '_' . $name;
		if ( !method_exists($this, $method) && !property_exists($this, $name) ) {
			throw new Exception('No existe el método ' . $method . ' ni la propiedad ' . $name . ' en la clase "' . get_class($this) . '"');
		}
		Exception_TypeException::$field = $name;
		if ( method_exists($this, $method) ) {
			return $this->$method(Core_DBI::getInstance()->realEscapeString($value));
		} else {
			return Type_Type::isTyped($this->$name) ? $this->$name->val = Core_DBI::getInstance()->realEscapeString($value) : $this->$name = Core_DBI::getInstance()->realEscapeString($value);
		}
	}
	
	protected function setId($value){
		$pkName = static::_primaryKeyName;
		return Type_Type::isTyped($this->$pkName) ? $this->$pkName->val = $value : $this->$pkName = $value;
	}
	
	/**
	 * Metodo encargado de leer el descriptor y crear los objetos 
	 * correspondientes a cada atributo del objeto
	 *
	 * @return void
	 */
	protected final function createAttributes() {		
		foreach ( static::$__descriptor as $attrName => $attrConfig) {
			if ( isset($attrConfig['Type']) ) {
				$typeClass = Type_Type::PREFIX . $attrConfig['Type'];
				if ( class_exists($typeClass) ) {
					if ( isset($attrConfig['Constraints']['ReferenceValue']) ) {
						$attrConfig['Constraints']['ReferenceValue'] = &$this->$attrConfig['Constraints']['ReferenceValue'];
					}
					$this->$attrName = new $typeClass( isset($attrConfig['Constraints']) ? $attrConfig['Constraints'] : null );
				}
			}
		}
	}
	
	protected final function createDescriptor() {
		static::$__descriptor = array();
		$reflectionObject = new ReflectionObject($this);
		foreach($reflectionObject->getProperties() as $reflectionProperty) {
			foreach ( $this->docCommentToArray($reflectionProperty->getDocComment()) as $commentLine ) {
				$commentLine = explode(' ' , $commentLine);
				if ( preg_match('/type/i', $commentLine[0]) ) {
					static::$__descriptor[$reflectionProperty->name]['Type'] = $commentLine[1];
				}
				if ( preg_match('/constraint/i', $commentLine[0]) ) {
					static::$__descriptor[$reflectionProperty->name]['Constraints'][$commentLine[1]] = isset($commentLine[2]) ? $commentLine[2] : true;
				}
			}
		}
	}
	
	protected final function docCommentToArray($docComment){
		return preg_split('/@/', preg_replace(array('/\*/' , '/\//' , '/\\t/' , '/\s*(\\r)?\\n\s*/') , '' , $docComment) , -1, PREG_SPLIT_NO_EMPTY);
	}
	
	
} 

?>