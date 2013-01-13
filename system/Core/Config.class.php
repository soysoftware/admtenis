<?php
namespace Flush\Core;

abstract class Config {
	
	private static $config = array();
	
	public static function init($config) {
		self::$config = $config;
	}
	
	public static function getVal($val, $subval = null){
		if (!isset(self::$config[$val])){
			return false;
		}
		if (!is_null($subval) && (!is_array(self::$config[$val]) || !isset(self::$config[$val][$subval]))){
			return false;
		}
		return !is_null($subval) ? self::$config[$val][$subval] : self::$config[$val];
	}
} 
?>