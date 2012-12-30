<?php
/**
 * @author Lucas Ceballos
 * @version 0.0.6
 * @since 20/02/2012
 */


/**
 * Clase wrapper de la API de PHP para MySQL 
 * Otorga mayor flexibilidad a las aplicaciones en caso de cambiar el motor de base de datos en el futuro
 */

class Core_DBI {
	private			$link;
	private			$host = DB_HOST;
	private			$user = DB_USER;
	private			$pass = DB_PASS;
	private	static	$instance;
	private			$transactionLevel = 0;
	public static	$lastQuery;

	/**
	 * @ignore
	 * 
	 * Constructor de la clase
	 *
	 * $host
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 */
	private function __construct($host, $user, $password) {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $password;
		self::$instance = $this;
		self::$lastQuery = '';
	}

	/**
	 * Devuelve la instancia de la base de datos iniciada (dado que es una clase con métodos static)
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return Core_DBI
	 *
	 */
	public static function getInstance($host = DB_HOST, $user = DB_USER, $pass = DB_PASS){
		if(!isset(self::$instance)){
			self::$instance = new self($host, $user, $pass);
		}
		return self::$instance;
	}

	/**
	 * Realiza una conexión a la base de datos, retorna TRUE en caso de éxito o FALSE en caso de error
	 * 
	 * @param void
	 * @return boolean
	 */
	public function connect(){
		$this->link = mysqli_connect($this->host, $this->user, $this->pass);
		return (!mysqli_connect_errno());
	}

	/**
	 * Selecciona la base de datos a utilizar
	 * 
	 * @param string   
	 * @return boolean
	 */
	public function selectDB($dbName){
		return mysqli_select_db($this->link, $dbName);
	}

	/**
	 * Cierra la conexión a la base de datos, retorna TRUE en caso de exito o FALSE en caso de error
	 * 
	 * @param void
	 * @return boolean
	 */
	public function closeConnection(){
		return (mysqli_close($this->link));
	}

	/**
	 * Devuelve el ultimo error que ha ocurrido en la conexión actual con el formato "[Nro Error] Descripcion"
	 * 
	 * @param void
	 * @return string
	 */
	public function error(){
		return '[' . mysqli_errno($this->link) . '] ' . mysqli_error($this->link);
	}

	/**
	 * Ejecuta una query
	 * 
	 * @param string	 
	 * @return mixed [Error: FALSE | Consultas son resultset exitosas: Objeto mysqli_result | Consultas sin resultset exitosas: TRUE]
	 */
	public function query($query){
		$this->syncrhonize();
		$resultSet = mysqli_query($this->link, $query);
		self::$lastQuery = $query;
		return $resultSet;
	}

	/**
	 * Devuelve el resultado de una query como array asociativo
	 * 
	 * @param mysqli_result
	 * @return array
	 */
	public function fetchAssoc(mysqli_result $result){
		return mysqli_fetch_assoc($result);
	}

	/**
	 * Devuelve el resultado de una query como array numerico
	 *
	 * @param mysqli_result
	 * @return array
	 *
	 */
	public function fetchRow(mysqli_result $result){
		return mysqli_fetch_row($result);
	}
	
	/**
	 * Inicia una transacccion
	 *
	 * @return boolean
	 */
	public function beginTran(){
		if($this->transactionLevel === 0){
			$result = $this->query('START TRANSACTION');
		}
		$this->transactionLevel++;
		return (isset($result) ? $result : true);
	}

	/**
	 * Realiza commit de la ultima transaccion iniciada
	 * Devuelve TRUE en caso de éxito o FALSE en caso de error
	 *
	 * @return boolean
	 */
	public function commit(){
		if($this->transactionLevel === 1){
			$result = mysqli_commit($this->link);
		}
		$this->transactionLevel--;
		return (isset($result) ? $result : true);
	}

	/**
	 * Realiza un rollback de la ultima transaccion iniciada
	 * Devuelve TRUE en caso de éxito o FALSE en caso de error
	 *
	 * @return boolean
	 */
	public function rollback(){
		if($this->transactionLevel === 1){
			$result = mysqli_rollback($this->link);
		}
		$this->transactionLevel --;
		return (isset($result) ? $result : true);
	}

	/**
	 * Activa o desactiva el autocommit
	 * Devuelve TRUE en caso de éxito o FALSE en caso de error
	 *
	 * @param boolean
	 * @return boolean
	 *
	 */
	public function autocommit($value){
		return mysqli_autocommit($this->link, $value);
	}

	/**
	 * Realizar un PING a la conexion actual
	 *
	 * @param void
	 * @return boolean
	 *
	 */
	public function ping(){
		return mysqli_ping($this->link);
	}

	/**
	 * Mantiene abierta una conexion persistente a la base de datos
	 *
	 * @param void
	 * @return boolean
	 *
	 */
	public function persist(){
		if (!isset($this->link) || !$this->ping()) {
			//Conexion con la DB Perdida, reconectamos!
			return $this->connect();
		}
		//La conexion sigue establecida, no hace falta reconectar
		return true;
	}

	/**
	 * Escapa un string para poder incluirlo en una consulta SQL, evita injection
	 * 
	 * @param string $string
	 * @return string
	 */
	public function realEscapeString($string){
		return mysqli_real_escape_string($this->link, $string);
	}

	/**
	 * Escapa completamente una variable para incluirlo en una consulta SQL
	 * Se usa tanto para los valores en el INSERT y en el UPDATE como para los valores de filtro en el WHERE
	 *
	 * @param mixed $obj
	 * @throws Exception
	 * @return string
	 */
	public function objectToDB($obj){
		try {
			if (!isset($obj) && !(is_scalar($obj) && ($obj != 0)))
				return 'NULL';
			if (is_a($obj, 'Type'))
				return $this->objectToDB($obj->val);
			switch (Core_Functions::getType($obj)){
				case 'bool':
					if ($obj) return 'true';
					elseif (!$obj) return 'false';
					else return 'NULL';
				case 'string':
					if (!isset($obj) || empty($obj)) return "''";
					else return "'" . $this->realEscapeString($obj) . "'";
				case 'int':
					return $obj;
				default:
					return $obj;
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	/**
	 * Devuelve la cantidad de rows que devolvió la consulta ejecutada
	 * 
	 * @param mysqli_result
	 * @return int
	 */
	public function numRows($result){
		return mysqli_num_rows($result);
	}

	/**
	 * Parsea lo que mandan en el campo $columns para 'SingleSelect' y para 'CustomSelect'
	 * Pueden mandar tanto un array como un string
	 *
	 * @param array $argColumns
	 * @param string $prefix
	 * @return string
	 */
	private static function parseColumns(Array $argColumns, $prefix = ''){
		$columns = '';
		foreach($argColumns as $column => $value){
			$columns .= $prefix . '`' . $column . '`, ';				
		}
		$columns = trim($columns, ', ');		
		return $columns;
	}

	/**
	 * Parsea lo que mandan para la cláusula WHERE para funciones de 'SELECT', 'UPDATE' y 'DELETE'
	 *
	 * @param array
	 * @return string
	 */
	private static function parseWhere($argWhere){
		$whereClause = '';
		if (count($argWhere) > 0){
			$whereClause .= ' WHERE ';
			foreach ($argWhere as $name => $value) {
				$whereClause .= '`' . self::getInstance()->realEscapeString($name) . '` = ' . self::getInstance()->objectToDB($value) . ' AND ';
			}
			$whereClause = trim($whereClause, ' AND ');
		}
		return $whereClause;
	}

	/**
	 * Genera una consulta del tipo INSERT para la tabla y los valores enviados por parametro
	 * 
	 * @param string
	 * @param array
	 * @return string
	 */
	public static function getSingleInsertQuery($table, $arrayValues){
		$keys = '';
		$values = '';
		foreach($arrayValues as $key => $value){
			$keys .= '`' . self::getInstance()->realEscapeString($key) . '`, ';
			$values .= self::getInstance()->objectToDB($value) . ', ';
		}
		$keys = trim($keys, ', ');
		$values = trim($values, ', ');

		$query = 'INSERT INTO `' . $table . '` (' . $keys . ') VALUES (' . $values . ');';
		return $query;
	}

	/**
	 * Genera una consulta del tipo UPDATE para la tabla y los valores enviados por parametro
	 *
	 * @param string $table
	 * @param array $arrayValues
	 * @param array $arrayWhere
	 * @return string
	 */
	public static function getSingleUpdateQuery($table, $arrayValues, $arrayWhere){
		$query = 'UPDATE `' . $table . '` SET ';
		foreach($arrayValues as $key => $value){
			$query .= '`' . self::getInstance()->realEscapeString($key) . '` = ' . self::getInstance()->objectToDB($value) . ', ';
		}
		$query = trim($query, ', ');
		$whereClause = self::parseWhere($arrayWhere);
		$query.= ' ' . $whereClause . ' ;';
		return $query;
	}

	/**
	 * Genera una consulta del tipo SELECT para la tabla y los valores enviados por parametro
	 *
	 * @param string $table
	 * @param array $arrayColumns
	 * @param array $arrayWhere
	 * @param mixed $limit
	 * @return string
	 *
	 */
	public static function getSingleSelectQuery($table, $arrayColumns, $arrayWhere, $limit = ''){
		$columns = self::parseColumns($arrayColumns);
		$whereClause = self::parseWhere($arrayWhere);
		$query = 'SELECT ' . $columns . ' FROM `' . $table . '` ' . $whereClause . (!empty($limit) ? ' LIMIT ' . $limit : '') . ';';
		return $query;
	}

	/**
	 * Genera una consulta del tipo SELECT para la tabla y los valores enviados por parametro
	 *
	 * @param string
	 * @param array
	 * @param string
	 * @param mixed
	 * @return string
	 *
	 */
	public static function getCustomSelectQuery($table, $arrayColumns, $whereClause, $limit = ''){
		$columns = self::parseColumns($arrayColumns);
		$whereClause = (!empty($whereClause) ? ' WHERE ' . $whereClause : '');
		$query = 'SELECT ' . $columns . ' FROM `' . $table . '` ' . $whereClause . (!empty($limit) ? ' LIMIT ' . $limit : '') . ';';
		return $query;
	}

	/**
	 * Genera una consulta del tipo SELECT con un JOIN entre $table1 y $table2
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param array
	 * @param string
	 * @param mixed
	 * @return string
	 *
	 */
	public static function getJoinSelectQuery($table1, $table2, $joinOn, $arrayColumns, $whereClause, $limit = ''){
		$columns = self::parseColumns($arrayColumns, 't1.');
		$whereClause = (!empty($whereClause) ? ' WHERE ' . $whereClause : '');
		$query = 'SELECT ' . $columns . ' FROM `' . $table1 . '` t1 INNER JOIN `' . $table2 . '` t2 ON t1.' . $joinOn . ' = t2.' . $joinOn . ' ';
		$query .= $whereClause . (!empty($limit) ? ' LIMIT ' . $limit : '') . ';';
		return $query;
	}

	/**
	 * Realiza una baja lógica de un registro identificado con la PK enviada
	 * Le asigna a través de un UPDATE el valor 1 al campo 'DELETED'
	 * 
	 * @param string
	 * @param array
	 * @return string
	 */
	public static function getSingleDeleteQuery($table, $arrayWhere){
		$whereClause = self::parseWhere($arrayWhere);
		$query = 'UPDATE `' . self::getInstance()->realEscapeString($table) . '` SET `deleted` = 1 ' . (!empty($whereClause) ? $whereClause : '') . ';';
		return $query;
	}

	/**
	 * Devuelve el ID del último registro ingresado (la última PK)
	 * 
	 * @param void
	 * @return int
	 */
	public function insertedId(){
		return mysqli_insert_id ($this->link);
	}

	/**
	 * Vacia los resultset acumulados permitiendo realizar nuevas querys
	 * 
	 * @param void
	 * @return void
	 */
	public function syncrhonize(){
		if ( mysqli_more_results($this->link)) {
		    while(@mysqli_next_result($this->link));
		}
	}
	
	/**
	 * Cambia el CHARSET de la conexion con ls DB
	 * @param string
	 * @return boolean
	 */
	public function setCharset($charset){
		return mysqli_set_charset($this->link, $charset);
	}
}

?>