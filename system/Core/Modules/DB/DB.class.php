<?php 
abstract class Core_Modules_DB_DB {
	
	
	protected $link;
	protected $host;
	protected $user;
	protected $pass;
	protected $database;
	protected $port;
	
	protected $lastQuery;
	
	// Inicializador de los drivers
	protected function init($host, $user, $pass, $database, $port){
		$this->host = $host;	
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
		$this->port = $port;
	}
	
	// Getter general (permite leer pero no modificar)
	public function __get($name){
		return $this->$name;
	}
	
	// Funciones atomicas
	abstract function __construct($host, $user, $pass, $database , $port, $autoConnect = false);
	abstract public function connect();
	abstract public function disconnect();
	abstract public function selectDB($database);
	abstract public function query($query);
	abstract public function fetchAssoc($result);
	abstract public function fetchRow($result);
	abstract  public function beginTran();
	abstract public function commit();
	abstract public function rollBack();
	abstract public function autoCommit($value);
	abstract public function ping();
	abstract public function persist();
	abstract public function realEscapeString($string);
	abstract public function numRows($result);
	abstract public function insertedId();
	abstract public function setCharset($charset);
	abstract public function errorNo();
	abstract public function errorMsg();
	protected function escape($string){
		return static::ESCAPE_CHAR . $string . static::ESCAPE_CHAR;
	}
	// Funciones Compuestas
	public function error(){
		return '[' . $this->errorNo() . ']' . $this->errorMsg();
	}

	protected function parseWhere($where){
		if (is_array($where) && count($where)>0){
			$whereClause = 'WHERE ';
			foreach ($where as $name => $value) {
				$whereClause .= $this->escape($name) . '=' . $this->toDB($value) . ' AND ';
			}
			$where = chop($whereClause, ' AND ');
		}
		return $where;
	}
	protected function parseValues($values){
		if (is_array($values)){
			$valstr = '';
			foreach($values as $name => $value){
				$valstr .= $this->escape($name) . '=' . $this->toDB($value) .',';
			}
			$values = chop($valstr, ',');
		}
		return $values;
	}
	protected function toDB($value){
		if (is_numeric($value)){
			return $value;
		}
		if (is_string($value)){
			return '"' . $this->realEscapeString($value) . '"';
		}
		if (is_bool($value)){
			return '"' . $value . '"';
		}
		if (is_null($value)){
			return "NULL";
		}
	}
	public function fetchAllRow($result){
		$results = array();
		while ($row = $this->fetchRow($result)){
			$results[] = $row;
		}
		return $results;
	}
	public function fetchNRow($result, $limit){
		$results = array();
		for($i = 0; $i < $limit; $i++){
			if(!$row = $this->fetchRow($result)){
				break;
			}
			$results[] = $row;
		}
		return $results;
	}
	public function fetchAllAssoc($result){
		$results = array();
		while ($row = $this->fetchAssoc($result)){
			$results[] = $row;
		}
		return $results;
	}
	public function fetchNAssoc($result, $limit){
		$results = array();
		for($i = 0; $i < $limit; $i++){
			if(!$row = $this->fetchAssoc($result)){
				break;
			}
			$results[] = $row;
		}
		return $results;
	}
	// Operaciones básicas
	abstract public function select($table, Array $columns, $where = '', $limit = -1, $order = false);
	abstract public function update($table, Array $values, $where);
// 	abstract public function insert($table, $values);
// 	abstract public function delete($table, $where);
}
?>