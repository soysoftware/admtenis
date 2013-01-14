<?php
namespace Flush\Core\Model;
use Flush\Core\Modules\DB\Loader;

use Flush\Type\BaseType;

use Flush\Core\Modules\DB;
use Flush\Exception;
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 1.5.1
 */


abstract class BaseModel {
	/**
	 * @var object $__original
	 *
	 */
	protected $__original = null;
	/**
	 * @var boolean $__readOnly
	 *
	 */
	protected $__readOnly = false;
	/**
	 * 
	 * @var \Flush\Core\Modules\DB\Drivers\BaseDriver $_db;
	 */
	protected $__db;
	// Constructor
	/**
	 * @ignore
	 *
	 * Este constructor se encarga de poner el ID y si este no es NULO lo busca en la DB y llena el objeto
	 * Si es nulo, no hace nada
	 *
	 * @todo Implementar lógica para objetos readOnly
	 * @param int
	 * @param bool $readOnly
	 */
	public function __construct($idObj = null, $readOnly = false){
		$this->__readOnly = $readOnly;		
		if (!is_null($idObj)){
			$this->id = $idObj;
			$this->createObject(); //Conecto con la base de datos y cargo los datos del objeto
		}
	}
	


	/**
	 * Magic method que se dispara cuando se intenta serializar el objeto (función serialize)
	 * Devuelve un array asociativo que contiene los nombres de los atributos del objeto y los valores asociados
	 * Además carga las relational lists para poder perdir luego sus objetos desde JS (al igual que los objetos comunes)
	 *
	 * @todo ESTE METODO ES UNA MIERDA
	 * @return array
	 */
	public function __sleep(){
		$array = array();
		foreach ($this as $attr => $val) {
			if (self::isStorableAttribute($attr)) {
				$array[$attr] = $val;
			} else {
				if (Core_Functions::esAtributoLL($attr)) { //TODO cambiar esto! que esté en Base
					$auxAttr = substr($attr, 1);
					if (is_object($this->__get($auxAttr)) && get_class($this->__get($auxAttr)) == 'RelationalList') {
						$arr = $this->$attr->get();
						$array[$auxAttr] = $arr;
						/* Antes (para Relational Lists
						$arr = array();
						foreach ($this->$attr->get() as $item)
							$arr[] = $item->expand();
						$array[$auxAttr] = $arr;
						*/
					//} else { //Esto es para objetos comunes
					//	$array[$auxAttr] = $this->__get($auxAttr);
					}
				}
			}
		}
		return $array;
	}

	// Getters
	/**
	 * Magic Method para el GET de una variable que no existe o no puede acceder (Lazy Loading)
	 * Además contempla que la variable puede ser de un tipo personalizado (Type)
	 *
	 * @param string
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($name) {
		if (method_exists($this, $method = 'get' . ucfirst($name))){
			return $this->$method();
		} else if (property_exists(get_class($this), $name = '_' . $name)){
			return BaseType::isTyped($this->$name) ? $this->$name->val : $this->$name; 
		} else {
			throw new \Exception('No existe el método ' . $method . ' ni la propiedad ' . $name . ' en la clase "' . get_class($this) . '"');
		}
	}
	
	protected function getDb(){
		if (!isset($this->__db)){
			$this->__db = Loader::load(defined('static::DB') ? static::DB : 0);
		}
		return $this->__db;
	}
	
	/**
	 * Retorna el ID del objeto
	 * Este método se usa como Lazy Loading ya que el ID de cada objeto es PRIVATE
	 *
	 * @return int
	 */
	protected function getId(){
		return BaseType::isTyped($this->{static::_primaryKeyName}) ? $this->{static::_primaryKeyName}->val : $this->{static::_primaryKeyName};
	}

	/**
	 * Arma un array con el nombre de la PrimaryKey y el value de la misma
	 *
	 * @return array
	 */
	protected final function getArrayPrimaryKey(){
		return array(trim(static::_primaryKeyName,'_') => $this->getId());
	}

	// Setters
	/**
	 * Magic Method para el SET de una variable que no existe o no puede acceder (Lazy Loading)
	 * Además contempla que la variable puede ser de un tipo personalizado (Type), y maneja un posible error de tipos
	 *
	 * @param string $name
	 * @param mixed $value
	 * @throws Exception_TypeException
	 * @throws Exception_InternalSecurityException
	 * @throws Exception
	 * @return mixed
	 */
	public function __set($name, $value) {
		if (method_exists($this, $method = 'set' . ucfirst($method))){
			return $this->$method($this->db->realEscapeString($value));
		} else if (property_exists(get_class($this), $name = '_' . $name)){
			BaseType::isTyped($this->$name) ? $this->$name->val = $this->realEscapeString($value) : $this->$name = $this->realEscapeString($value); 
		} else {
			throw new \Exception('No existe el método ' . $method . ' ni la propiedad ' . $name . ' en la clase "' . get_class($this) . '"');
		}
	}
	/**
	 * Asigna el ID del objeto. Este método se usa para asignarlo en el __construct de Base
	 *
	 * @param mixed $value
	 */
	protected function setId($value){
		BaseType::isTyped($this->{static::_primaryKeyName}) ? $this->{static::_primaryKeyName}->val = $value : $this->{static::_primaryKeyName} = $value;
	}

	// Metodos
	/**
	 * Metodo encargado de devolver un array con los nombres de los atributos de un objeto
	 *
	 * @param string $class
	 * @return array
	 */
	protected final static function getStorableAttributes($class){
		$attributes = array();
		foreach(get_class_vars($class) as $attr => $value) {
			if (self::isStorableAttribute($attr) && $attr != $class::_primaryKeyName) {
				$attributes[] = trim($attr,'_');
			}
		}
		return $attributes;
	}

	/**
	 * Metodo encargado de pedir la query para llenar un objeto y mandarlo a llenar
	 * Inserta el atributo ___original
	 * Deja el objeto listo para comenzar a utilizarlo
	 *
	 * @throws Exception_RecordNotFound
	 * @throws Exception_MysqlException
	 * @return void
	 */
	protected final function createObject(){
		if (!$this->__readOnly) {
			$this->__original = array();
		}
		//Genero la consulta y traigo los resultados
		if (!$result = $this->db->select($this::_table, self::getStorableAttributes(get_class($this)), $this->getArrayPrimaryKey())){
			throw new Exception\MysqlException($this->db->error() , $this->db->lastQuery);
		}
		if ($this->db->numRows($result) == 0) {
			throw new Exception\RecordNotFound(get_class($this), $this->id);
		}
		$row = $this->db->fetchAssoc($result);
		//Mando a hacer el fill
		$this->fillObject($row);
	}

	/**
	 * Metodo encargado de llenar el objeto a partir de una data row 
	 * (se usa para los objetos que vienen de DB)
	 *
	 * @param array
	 * @return void
	 */
	protected final function fillObject(Array $row){
		//Hago el fill
		foreach($row as $attr => $value){
			$this->$attr = $value;
		}
	}

	/**
	 * Funcion que genera una lista de objetos a partir del nombre de Clase
	 * y una cláusula WHERE
	 *
	 * @param string $class
	 * @param string $where
	 * @param string $limit
	 * @param bool   $readOnly
	 * @throws Exception_MysqlException
	 * @return array
	 */
	public final static function getObjectList($class, $where, $limit = null, $readOnly = false){
		$query = is_array($where) ? Core_DBI::getSingleSelectQuery($class::_table, self::getStorableAttributes($class), $where, $limit) : Core_DBI::getCustomSelectQuery($class::_table, self::getStorableAttributes($class), $where, $limit);
		if (!$result = $this->db->query($query)) {
			throw new Exception\MysqlException('Error en consulta MySQL para crear lista de objetos de clase ' . $class . ' | MySQL Error: ' . $this->db->error() . ' | Query: ' . $query);
		}
		$list = array();
		while($row = $this->db->fetchAssoc($result)) {
			$obj = new $class(null, $readOnly);
			$obj->fillObject($row);
			$list[] = $obj;
		}
		return $list;
	}

	/**
	 * Metodo encargado de definir si el atributo recibido por argumento se guarda o no en la DB
	 * @param string $attributeName
	 * @return bool
	 */
	private static function isStorableAttribute($attributeName) {
		return (!empty($attributeName) && $attributeName[1] != '_');
	}

	/**
	 * Si el objeto es nuevo se lo guarda en la base de datos, caso contrario se verifica si se han realizado
	 * modificaciones sobre el mismo y se guardan SÓLO las modificaciones realizadas
	 *
	 * @throws Exception_InternalSecurityException
	 * @return boolean
	 */
	public final function save(){
		if ($this->__readOnly) {
			throw new Exception\InternalSecurityException('Error: no se puede guardar el objeto ya que es readOnly');
		}
		//Guardo en transacción
		$this->db->beginTran();
		$tranStatus = ($this->id) ? $this->saveUpdate() : $this->saveInsert();
		//Recorro los atributos del objeto que estoy guardando, si alguno es un objeto lo mando a guardar
		foreach($this as $value){
			if(is_a($value, 'Core_RelationalList')){
				$tranStatus = $value->save();
			}
		}
		//Termino la transacción
		$tranStatus ? $this->db->commit() : $this->db->rollback();
		return $tranStatus;
	}

	/**
	 * Metodo que se encarga de manejar el guardado de objetos cuando se debe hacer INSERT
	 *
	 * @throws Exception_MysqlException
	 * @return boolean
	 */
	private final function saveInsert(){
		//Preparo el INSERT
		if (!$result = $this->db->insert(static::_table, $this->getStorableAttributes(get_class($this)))){
			throw new Exception\MysqlException('Error en consulta MySQL para guardar objeto de clase ' . get_class($this) . ' | MySQL Error: ' . $this->db->error() . ' | Query: ' . $query);
		}
		$this->setId($this->db->insertedId());
		return $result;
	}

	/**
	 * Metodo que se encarga de manejar el guardado de objetos cuando se debe hacer UPDATE
	 *
	 * @throws Exception_MysqlException
	 * @return boolean
	 */
	private final function saveUpdate(){
		if (!$result = $this->db->update(static::_table, $this->prepareUpdate(), $this->getArrayPrimaryKey())){
			throw new Exception\MysqlException($this->db->error() , $this->db->lastQuery);
		}
		return (isset($result) ? $result : true);
	}

	/**
	 * Si el objeto existe, lo borra
	 *
	 * @throws Exception
	 * @throws Exception_MysqlException
	 * @return boolean
	 */
	public final function delete(){
		if ($this->__readOnly) {
			throw new \Exception('Error: no se puede borrar el objeto ya que es readOnly');
		}
		if (!is_null($this->id)){
			//Borro en transacción
			$this->db->beginTran();
			if (!$tranStatus = $this->db->delete(static::_table, $this->getArrayPrimaryKey())) {
				throw new Exception\MysqlException('Error en consulta MySQL para eliminar objeto de clase ' . get_class($this) . ' | MySQL Error: ' . $this->db->error() . ' | Query: ' . $query);
			} 
			//Termino la transacción
			$tranStatus ? $this->db->commit() : $this->db->rollback();
		}
		return isset($tranStatus) ? $tranStatus : true;
	}

	/**
	 * Prepara un array asociativo que contiene los nombres de los atributos del objeto y los valores asociados
	 * Ej: $values['nombre'] = 'pepe';
	 * El array puede ser pasado por parametro a cualquier metodo generador de querys 
	 * 
	 * @return array
	 */
	protected function prepareInsert(){
		//Recorro los atributos del objeto y los guardo en un array asociativo
		$values = array();
		foreach($this as $attr => $value){
			if (is_a($value, 'Type_Type')) {
				$value = $value->val;
			}
			if (!is_object($value) && !is_array($value) && (self::isStorableAttribute($attr)) && ($attr != $this::_primaryKeyName)) {
				$values[trim($attr,'_')] = $value;
			}
		}
		return $values;
	}

	/**
	 * Prepara un array asociativo que contiene los nombres de los atributos del objeto y los valores asociados que han sido modificados
	 * Ej: $values['nombre'] = 'pepe';
	 * El array puede ser pasado por parametro a cualquier metodo generador de querys
	 *
	 * @return array
	 */
	protected function prepareUpdate(){
		//Recorro los atributos del objeto y guardo los modificados en un array asociativo
		$values = array();
		foreach($this as $attr => $value){
			if (BaseType::isTyped($value)){
				$value = $value->val;
			}
			if (!is_object($value) && !is_array($value) && (self::isStorableAttribute($attr))){
				if ($value != $this->__original[$attr]) {
					$values[trim($attr,'_')] = $value;
				}
			}
		}
		return $values;
	}

}

?>