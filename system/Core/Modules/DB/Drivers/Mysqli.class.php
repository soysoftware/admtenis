<?php

namespace Flush\Core\Modules\DB\Drivers;

class Mysqli extends BaseDriver {
	
	const DEFAULT_PORT = 3306;
	const ESCAPE_CHAR = '`';
	
	private $transactionLevel = 0;
	
	public function __construct($host, $user, $pass, $database = '', $port = self::DEFAULT_PORT, $autoConnect = false){
		parent::init($host, $user, $pass, $database, $port);
		$this->link = new \mysqli($host, $user, $pass, $database, $port);
		if ($autoConnect){
			$this->connect();
		}
	}
	
	public function connect(){
		$this->link->connect($this->host, $this->user, $this->pass, $this->database, $this->port);
		return (!mysqli_connect_errno());
	}
	public function disconnect(){
		return mysqli_close($this->link);
	}
	public function selectDB($database){
		return mysqli_select_db($this->link, $database);
	}
	public function query($query){
		$this->lastQuery = $query;
		return mysqli_query($this->link, $query);
	}
	public function fetchAssoc($result){
		return mysqli_fetch_assoc($result);
	}
	public function fetchRow($result){
		return mysqli_fetch_row($result);
	}
	public function beginTran(){
		if($this->transactionLevel === 0){
			$result = $this->query('START TRANSACTION');
		}
		$this->transactionLevel++;
		return (isset($result) ? $result : true);
	}
	public function commit(){
		if($this->transactionLevel === 1){
			if ($result = mysqli_commit($this->link)) {
				$this->transactionLevel--;
			}
		}
		return (isset($result) ? $result : true);
	}	
	public function rollBack(){
		if($this->transactionLevel === 1){
			if ($result = mysqli_rollback($this->link)){
				$this->transactionLevel --;				
			}
		}
		return (isset($result) ? $result : true);
	}
	public function autoCommit($value){
		return mysqli_autocommit($this->link, $value);
	}
	public function ping(){
		return mysqli_ping($this->link);
	}
	public function persist(){
		if (!isset($this->link) || !$this->ping()) {
			return $this->connect();
		}
		return true;
	}
	public function realEscapeString($string){
		return mysqli_real_escape_string($this->link, $string);
	}
	public function numRows($result){
		return mysqli_num_rows($result);
	}
	public function insertedId(){
		return mysqli_insert_id ($this->link);
	}
	public function setCharset($charset){
		return mysqli_set_charset($this->link, $charset);
	}
	public function errorNo(){
		return mysqli_errno($this->link);
	}
	public function errorMsg(){
		return mysqli_error($this->link);
	}
	public function select($table, Array $columns, $where = '', $limit = -1, $order = false){
		$query  = ' SELECT ' . $this->parseColumns($columns);
		$query .= ' FROM ' . $this->escape($table);
		$query .= ' ' . $this->parseWhere($where);
		$query .= ($limit > 0) ? ' LIMIT ' . $limit : '';
		return $this->query($query);		
	}
	public function update($table, Array $values, $where){
		$query  = ' UPDATE ' . $this->escape($table);
		$query .= ' SET ' . $this->parseValuesForUpdate($values);
		$query .= ' ' . $this->parseWhere($where);
		return $this->query($query);		
	}
	public function delete($table, $where){
		$query  = ' DELETE FROM ' . $this->escape($table);
		$query .= ' WHERE ' . $this->parseWhere($where);
		return $this->query($query);
	}
	public function insert($table, Array $columns, Array $values){
		$query  = ' INERT INTO ' . $this->escape($table);
		$query .= ' ( ' . $this->parseColumns($columns) . ') ';
		$query .= ' VALUES ';
		$query .= ' ( ' . implode(',', array_map('toDB', $values)) . ');';
		return $this->query($query);
	}
	
}
?>