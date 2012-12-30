<?php
abstract class Core_Modules_DB_Loader {
	
	private static $loaded = array();
	
	public static function load($db){
		$dbConfig = is_array($db) ? $db : Core_Config::getVal('dbs')[$db];
	}
	
}
?>