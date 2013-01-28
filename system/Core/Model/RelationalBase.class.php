<?php
abstract class Core_RelationalBase {
	
	const 	TABLE_GLUE = 'Por';
	const 	NO_ACTION = 0;
	const 	INSERT_ACTION = 1;
	const 	DELETE_ACTION = 2;
	
	protected $fromClass;
	protected $toClass;
	protected $fromPrimaryKeyName;
	protected $toPrimaryKeyName;
	
	protected function __construct($fromClass, $toClass) {
		$this->fromClass = $fromClass;
		$this->fromPrimaryKeyName = $fromClass::_primaryKeyName;
		$this->toClass = $toClass;
		$this->toPrimaryKeyName = $toClass::_primaryKeyName;
	}
	
	public function __get($name) {
		if (!method_exists($this, $method =  'get' . ucfirst($name))) {
			throw new Exception_InternalApplicationException('No existe el método ' . $method . ' en la clase "' . get_class($this) . '"');
		}
		return $this->$method();
	}
	
	
	
}
?>