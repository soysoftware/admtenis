<?php
namespace Flush\Exception;
/**
 * Clase de Exception base para cualquier error generado por los Type
 */

class TypeException extends \Exception {
	public static $field;
	
    public function __construct($given, $expected){
    	$this->message = '[Data Type Error] Field ' . self::$field . ' must be ' . $expected . ' , ' . $given . '(' . gettype($given) . ') given' ;
	}

}

?>