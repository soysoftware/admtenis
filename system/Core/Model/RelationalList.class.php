<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */
namespace Flush\Core\Model;
class RelationalList extends RelationalBase
{
	private $referenceValue;
	private	$_list;

	public function __construct($fromClass, $toClass, $referenceValue) {
		parent::__construct($fromClass, $toClass);
		$this->referenceValue = $referenceValue;
	}
	
	/**
	 * @ignore
	 *
	 * @return array
	 */
	public function getList(){
		if ( !isset($this->_list) ){
			$this->fill();
		}
		return $this->_list;
	}

	/**
	 * Método encargado de agregar un objeto relacional a la lista de objetos relacionales
	 * 
	 * @param Core_RelationalObject $object
	 * @return bool
	 * @throws Exception
	 */
	public function add(&$object){
		if ( $this->checkObject($object) ) {	
	    	//Verifico si el objeto relacional ya se encuentra dentro de la lista
	    	if($pos = $this->find($object)){
	    		//El objeto existe, resuelvo segun el action que tenga el objeto existente
	    		switch($this->_list[$pos]->action){
	    			case self::DELETE_ACTION:
	    				//El objeto esta marcado para borrar, lo marco con NO_ACTION
	    				$this->_list[$pos]->action = self::NO_ACTION;
	    				break;
	    		}
	    	} else {
	    		//No existe, lo creo e inserto con action INSERT ya que es un objeto relacional nuevo
		    	$relationalObject = new RelationalObject($this->fromClass, $this->toClass);
		    	$relationalObject->{$this->toPrimaryKeyName} = $object->id;
		    	$relationalObject->{$this->fromPrimaryKeyName} = $this->referenceValue;
	    		$relationalObject->action = self::INSERT_ACTION;
	    		$this->_list[] = $relationalObject;
	    	}
	    	return true;
		}
	}

	/**
	 * Método encargado de eliminar un objeto relacional de la lista de objetos relacionales
	 * 
	 * @param Core_RelationalObject $object
	 * @return bool
	 * @throws Exception
	 */
	public function remove(&$object){
		if ( $this->checkObject($object) ) {
			if(($pos = $this->find($object)) !== false){ //Pongo el "=== false" porque puede que el FIND devuelva "0" (posición 0), y "0" es == false
				// El objeto existe, resuelvo segun el action que tenga el objeto existente
				switch($this->_list[$pos]->action){
					case self::INSERT_ACTION:
						// El objeto relacional esta para insertarse, no existe fisicamente aun
						unset($this->_array[$pos]);
						break;
					case self::NO_ACTION:
						// El objeto relacional ya esta creado fisicamente y no tiene modificaciones, lo marco para borrar
						$this->_list[$pos]->action = self::DELETE_ACTION;
						break;	
				}
				return true;
			} else {
				// El objeto relacional no existe, por lo tanto no puedo borrarlo
				return false;
			}
		}
	}

	/**
	 * Método encargado de buscar si un RelationalObject ya existe en la lista
	 * 
	 * @param Core_RelationalObject $object
	 * @return mixed
	 */
	private function find(&$object){
		foreach ($this->_array as $key => $value){
			if($object->id == $value->{$this->toPrimaryKeyName}){
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
	public function fill(){
		$query = Core_DBI::getSingleSelectQuery($this->tableName(), $this->columnsNames() , $this->whereArray());
		if(!$result = Core_DBI::getInstance()->query($query)){
			throw new Exception_MysqlException('Error en consulta MySQL para crear llenar relationalList con objetos de clase ' . $objectClass . ' | MySQL Error: ' . Core_DBI::getInstance()->error() . ' | Query: ' . $query);
		}
		$i = 0;
		while($row = Core_DBI::getInstance()->fetchAssoc($result)){
			//Agrego los objetos sin comprobación ya que estoy inicializando el array
			$this->_list[$i] = new Core_RelationalObject($this->fromClass, $this->toClass);
			$this->_list[$i]->{$this->fromPrimaryKeyName} = $row[trim($this->fromPrimaryKeyName, '_')];
			$this->_list[$i]->{$this->toPrimaryKeyName} = $row[trim($this->toPrimaryKeyName, '_')];
			$i++;
		}
	}
	
	private function columnsNames(){
		return array ( trim($this->toPrimaryKeyName, '_') => true, trim($this->fromPrimaryKeyName, '_') => true);
	}
	
	private function tableName(){
		return $this->toClass . self::TABLE_GLUE . $this->fromClass;
	}
	
	private function whereArray(){
		return array( trim($this->fromPrimaryKeyName, '_') => Type_Type::isTyped($this->referenceValue) ? $this->referenceValue->val : $this->referenceValue);
	}

	/**
	 * Guarda todos los objetos relacionales almacenados en el array _array
	 *
	 * @return boolean
	 */
	public function save(){
		foreach($this->_list as $key => $object){
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
	
	private function checkObject(&$object){
		// Chequeo la clase del objeto enviado
		if ( get_class($object) != $this->toClass ) {
			throw new Exception_InternalSecurityException('Objeto invalido');
		}
		return true;
	}
}
?>
