<?php
namespace Flush\Exception;
/**
 * Clase de Exception para cuando un registro no existe
 */

class MysqlException extends \Exception {
	public function __construct($mysqlError, $query = ''){
		$msg = 'Error MySQL | ERRO: ' . $mysqlError;
		if ( !empty($query) ) {
			$msg .= ' | Query: ' . $query;
		}
		parent::__construct($msg);
	}
}

?>