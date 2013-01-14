<?php
namespace Flush\Exception;
/**
 * Clase de Exception base para cualquier error generado por los Type
 */

class TypeException extends \Exception {
	
    public function __construct($given, $expected){
    	$this->message = '[Data Type Error] Field ' . debug_backtrace()[6]['args'][0] . ' must be ' . $expected . ' , ' . $given . '(' . gettype($given) . ') given' ;
	}

}

?>