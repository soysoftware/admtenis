<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class Core_RelationalObject extends Core_RelationalBase {
	
	private $_action = self::NO_ACTION;
	private $_object;


	public function __construct($fromClass,  $toClass){
		parent::__construct($fromClass, $toClass);
	}
	
	public function getObject(){
		if ( !isset($this->_object) ) {
			// Creamos el objeto en solo lectura
			$this->_object = new $this->toClass($this->{$this->toPrimaryKeyName}, true);
		}
		return $this->_object; 
	}
	
	/**
	 * @ignore
	 *
	 * @return int
	 */
	protected function getAction(){
		return $this->_action;
	}

	/**
	 * @ignore
	 * 
	 * @param int $action
	 * @return Core_RelationalObject
	 */
	protected function setAction($action){
		// Chequeamos que el action enviado sea valido
		if ( !preg_match('/' . self::DELETE_ACTION . '|' . self::INSERT_ACTION . '|' . self::NO_ACTION . '/', $action) ) {
			throw new Exception_InternalSecurityException('El action enviado no es válido');
		}
		$this->_action = $action;
		return $this;
	}
	
	public function save(){
		return true;
	}
	
	public function delete(){
		return true;
	}
}

?>
