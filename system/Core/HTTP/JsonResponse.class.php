<?php
namespace Flush\Core\HTTP;
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class Core_JsonResponse {
	const	CODE_SUCCESS = 1;
	const	CODE_ERROR = 2;
	const	CODE_WARNING = 3;
	const	CODE_ALERT = 4;

	const	STATUS_SUCCESS = 'success';
	const	STATUS_ERROR = 'error';
	const	STATUS_WARNING = 'warning';
	const	STATUS_ALERT = 'alert';

	public	$code;
	public	$status;
	public	$message;
	public	$data;

	public final function getObjectVars(){
		$attributes = array();
		foreach($this as $attr => $value){
			if ($attr[0] != '_'){
				$attributes[$attr] = $value;
			}
		}
		return $attributes;
	}
}

?>
