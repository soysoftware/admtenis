<?php
/**
 * 
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 * 
 */

class Parametro extends Core_Base {
	
	const _table = 'parametro';
	const _primaryKeyName = 'idParametro';
	
	protected static 	$cache = array();
	
	protected			$idParametro;
	protected			$valor;
	protected			$detalle;	

	static function get($id, $fresh = false) {
		if ( !isset(self::$cache[$id]) || $fresh ) {
			$parametro = new Parametro($id,true);
			self::$cache[$id] = $parametro->valor;
		}
		return self::$cache[$id];
	}
}

?>