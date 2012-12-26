<?php
/**
 * Clase de Exception para cuando un registro no existe
 */

class Exception_MysqlException extends Exception {
	public function __construct($mysqlError, $query = ''){
		$msg = 'Error MySQL | ERRO: ' . $mysqlError;
		if ( !empty($query) ) {
			$msg .= ' | Query: ' . $query;
		}
		parent::__construct($msg);
	}
}

?>