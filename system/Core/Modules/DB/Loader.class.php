<?php
abstract class Core_Modules_DB_Loader {
	
	const DRIVER_PREFIX = 'Core_Modules_DB_';
	
	private static $loaded = array();
	
	public static function &load($db){
		$dbConfig = is_array($db) ? $db : Core_Config::getVal('dbs')[$db];
		if (!isset(self::$loaded[$dbConfig['user'] . '@' . $dbConfig['host']])){
			self::$loaded[$dbConfig['user'] . '@' . $dbConfig['host']] = self::createDataBase($dbConfig);
		}
		return self::$loaded[$dbConfig['user'] . '@' . $dbConfig['host']];
	}

	private static function &createDataBase(array &$dbConfig){
		$driver = self::DRIVER_PREFIX . ucfirst($dbConfig['driver']);
		$objDb = new $driver($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], @$dbConfig['database']?:'', @$dbConfig['port']?: $driver::DEFAULT_PORT, true);
		if (isset($dbConfig['charset'])){
			$objDb->setCharset($dbConfig['charset']);
		}
		return $objDb;
	}
}
?>