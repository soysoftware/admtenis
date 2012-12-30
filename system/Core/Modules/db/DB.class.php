<?php 
abstract class Core_Modules_DB_DB {
	
	protected $link;
	protected $host;
	protected $user;
	protected $pass;
	protected $database;
	protected $port;
	
	protected $lastQuery;
	
	protected function init($host, $user, $pass, $database, $port){
		$this->host = $host;	
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
		$this->port = $port;
	}
	
	abstract public function __construct($host, $user, $pass, $database, $port, $autoConnect){}
	abstract public function connect(){}
	abstract public function disconnect(){}
	abstract public function selectDB($database){}
	abstract public function query($query){}
	abstract public function fetchAssoc($result){}
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
	abstract public function fetchRow($result){}
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
	abstract public function beginTran(){}
	abstract public function commit(){}
	abstract public function rollBack(){}
	abstract public function autoCommit($value){}
	abstract public function ping(){}
	abstract public function persist(){}
	abstract public function realEscapeString($string){}
	abstract public function numRows($result){}
	abstract public function insertedId(){}
	abstract public function setCharset(){}
}
?>