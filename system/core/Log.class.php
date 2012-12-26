<?php 
/**
 * @author Lucas Ceballos
 * @since 12/02/2012
 * @version 0.0.1
 * 
 */

abstract class Core_Log {
	
	const VBS_OFF = 0;
	const VBS_ERROR = 1;
	const VBS_WARNING = 2;
	const VBS_INFO = 3;
	const VBS_DEBUG = 4;
	const VBS_ALL = 5;
	
	public static $file;
	public static $verbose;
	
	private static function log(Array $line){
		// Intento abrir el archivo y escribo
		if ( $handle = fopen(self::$file, 'a') ){
			// Completo datos faltantes
			$debugBacktrace = debug_backtrace();
			$line[1] = '[T:' . date('h:i:s') . ']';
			$line[2] = '[F:' . substr($debugBacktrace[1]['file'], strrpos($debugBacktrace[1]['file'], '/')+1) . ']';
			$line[3] = '[L:' . $debugBacktrace[1]['line'] . ']';
			ksort($line);
			// Escribo
			fputs($handle, implode('', $line) . "\n");
			// Cierro el archivo
			fclose($handle);			
		}			
	}
	
	public static function error($msg){
		if ( self::$verbose >= self::VBS_ERROR ) {
			self::log(array(0 => "[E]" , 4 => $msg));
		}
	}
	public static function warning($msg){
		if ( self::$verbose >= self::VBS_WARNING ) {
			self::log(array(0 => "[W]" , 4 => $msg));
		}
		
	}
	public static function info($msg){
		if ( self::$verbose >= self::VBS_INFO ) {
			self::log(array(0 => "[I]" , 4 => $msg));
		}
		
	}
	public static function debug($msg){
		if ( self::$verbose >= self::VBS_DEBUG ) {
			self::log(array(0 => "[D]" , 4 => $msg));
		}
	}
	

}

?>