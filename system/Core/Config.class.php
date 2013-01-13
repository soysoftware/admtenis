<?php
namespace Flush\Core;

abstract class Config {
	
	private static $config = array();
	
	public static function init($config) {
		self::$config = $config;
	}
	
	public static function getVal($val){
		$finalVal = self::$config;
		foreach ( func_get_args() as $arg ) {
			if(!isset($finalVal[$arg])){
				return false;
			}
			$finalVal = $finalVal[$arg];
		}
		return $finalVal;
	}
} 
?>