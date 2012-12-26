<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class Core_RelationalList {
	const 	NO_ACTION = 0;
	const 	INSERT_ACTION = 1;
	const 	DELETE_ACTION = 2;
	
	private	$_array = null;
	private $objectClass;

	public function __construct($objectClass, Array $fatherPrimaryKeyArray){
		$this->objectClass = $objectClass;
		$this->_array = array();
		$this->fill($fatherPrimaryKeyArray);
	}

	/**
	 * @ignore
	 *
	 * @return array
	 */
	public function getArray(){
		return $this->_array;
	}

	/**
	 * Método encargado de agregar un objeto relacional a la lista de objetos relacionales
	 * 
	 * @param Core_RelationalObject $object
	 * @return bool
	 * @throws Exception
	 */
	public function add(Core_RelationalObject $object){
    	//Seteo el action en INSERT ya que estoy dentro de un add
    	$object->action = self::INSERT_ACTION;
    	//Verifico si el objeto relacional ya se encuentra dentro de la lista
    	if($pos = $this->find($object)){
    		//El objeto existe, resuelvo segun el action que tenga el objeto existente
    		switch($this->_array[$pos]->action){
    			case self::DELETE_ACTION:
    				//El objeto esta marcado para borrar, lo marco con NO_ACTION
    				$this->_array[$pos]->action = self::NO_ACTION;
    				break;
    		}
    	} else {
    		//No existe, lo inserto con action INSERT ya que es un objeto relacional nuevo
    		$this->_array[] = $object;
    	}
    	return true;
	}

	/**
	 * Método encargado de eliminar un objeto relacional de la lista de objetos relacionales
	 * 
	 * @param Core_RelationalObject $object
	 * @return bool
	 * @throws Exception
	 */
	public function remove(Core_RelationalObject $object){
		if(($pos = $this->find($object)) !== false){ //Pongo el "=== false" porque puede que el FIND devuelva "0" (posición 0), y "0" es == false
			// El objeto existe, resuelvo segun el action que tenga el objeto existente
			switch($this->_array[$pos]->action){
				case self::INSERT_ACTION:
					// El objeto relacional esta para insertarse, no existe fisicamente aun
					unset($this->_array[$pos]);
					break;
				case self::NO_ACTION:
					// El objeto relacional ya esta creado fisicamente y no tiene modificaciones, lo marco para borrar
					$this->_array[$pos]->action = self::DELETE_ACTION;
					break;	
			}
			return true;
		} else {
			// El objeto relacional no existe, por lo tanto no puedo borrarlo
			return false;
		}
	}

	/**
	 * Método encargado de buscar si un RelationalObject ya existe en la lista
	 * 
	 * @param Core_RelationalObject $object
	 * @return mixed
	 */
	private function find(Core_RelationalObject $object){
		foreach ($this->_array as $key => $value){
			if($object == $value){
				return $key;
			}
		}
		return false;
	}

	/**
	 * Metodo encargado de cargar los objetos relacionales, para hacerlo se le envia por parametro
	 * la clausula where a utilizar en el select, el metodo conecta con la DB, realizar la consulta
	 * y deja el array _array con todos los objetos relacionales dentro
	 *
	 * @param array $whereArray
	 * @throws Exception_MysqlException
	 */
	public function fill(Array $whereArray){
		$objectClass = $this->objectClass;
		$query = Core_DBI::getSingleSelectQuery($objectClass::_table, array( trim($objectClass::_primaryKeyName,'_') => null) , $whereArray);
		if(!$result = Core_DBI::getInstance()->query($query)){
			throw new Exception_MysqlException('Error en consulta MySQL para crear llenar relationalList con objetos de clase ' . $objectClass . ' | MySQL Error: ' . Core_DBI::getInstance()->error() . ' | Query: ' . $query);
		}
		while($row = Core_DBI::getInstance()->fetchAssoc($result)){
			//Agrego los objetos sin comprobación ya que estoy inicializando el array
			$this->_array[] = new $objectClass($row[$objectClass::_primaryKeyName]);
		}
	}

	/**
	 * Guarda todos los objetos relacionales almacenados en el array _array
	 *
	 * @return boolean
	 */
	public function save(){
		foreach($this->_array as $key => $object){
			switch ($object->action) {
				case self::INSERT_ACTION:
					$result = $object->save();
					break;
				case self::DELETE_ACTION:
					$result = $object->delete();
					break;
			}
		}
		return isset($result) ? $result : true;
	}

}

?>
