<?php
abstract class Core_Config {
	
	private static $config = array();
	
	public static function init($config) {
		self::$config = $config;
	}
	
	public static function &getVal($val){
		return isset(self::$config[$val]) ? self::$config[$val] : false;
	}
} 
?>