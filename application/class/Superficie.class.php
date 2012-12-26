<?php
/**
 * 
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 * 
 */

/**
 * @property Int	$idSuperficie
 * @property String	$nombre
 */
class Superficie extends Core_Base {
	const _table = 'superficie';
	const _primaryKeyName = 'idSuperficie';

	protected	$idSuperficie;
	protected	$nombre;
}

?>