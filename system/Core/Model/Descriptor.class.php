<?php
namespace Flush\Core\Model;

abstract class Descriptor {
	
	const CACHE_ENABLED = false;
	
	private static $cache = array();
	
	public static function get($class){
		if ( !isset(self::$cache[$class]) ){
			self::$cache[$class] = self::create($class);
		}
		return self::$cache[$class];
	}
	
	private static function create($class){
		$reflectionClass = new \ReflectionClass($class);
		return self::CACHE_ENABLED ? self::createFromCache($reflectionClass) : self::createFromReflection($reflectionClass);
	}
	
	private static function createFromCache(\ReflectionClass &$reflectionClass){
		if ( preg_match('/on/i', ini_get('apc.enabled'))){
			
		}
	}
	
	private static function createFromReflection(\ReflectionClass &$reflectionClass){
		$descriptor = array();
		foreach($reflectionClass->getProperties() as $reflectionProperty){
			foreach ( self::docCommentToArray($reflectionProperty->getDocComment()) as $commentLine ) {
				$commentLine = explode(' ' , $commentLine);
				if ( preg_match('/type/i', $commentLine[0]) ) {
					$descriptor[$reflectionProperty->name]['Type'] = $commentLine[1];
				}
				if ( preg_match('/constraint/i', $commentLine[0]) ) {
					$descriptor[$reflectionProperty->name]['Constraints'][$commentLine[1]] = isset($commentLine[2]) ? $commentLine[2] : true;
				}
			}
		}
		return $descriptor;
	}
	
	private static function docCommentToArray($docComment){
		return preg_split('/@/', preg_replace(array('/\*/' , '/\//' , '/\\t/' , '/\s*(\\r)?\\n\s*/') , '' , $docComment) , -1, PREG_SPLIT_NO_EMPTY);
	}
}
?>
