<?php

/**
 * Clase con métodos para crear código HTML
 */

class Core_HTML {
	/**
	 * Encodea una variable en formato JSON, ya sea objeto, array o escalar
	 * 
	 * @param mixed
	 * @param int
	 * @return string
	 * 
	 */
	public static function jsonEncode($json = null, $nivel = 0){
		//Si no pongo lo de is_array y lo de is_object, un array con count 0 me lo tomaba como NULL
		if ($json == null && !is_array($json) && !is_object($json)){
			return @json_encode();
		}
		$aux = array();
		if (is_object($json))
			$loopBy = $json->getObjectVars(true);
		else
			$loopBy = $json;
		foreach($loopBy as $id => $val){
			if (is_scalar($val))
				$val = utf8_encode($val);
			elseif (count($val) != 0)
				$val = self::jsonEncode($val, $nivel + 1);
			$aux[$id] = $val;
		}
		if ($nivel == 0)
			return json_encode($aux);
		else
			return $aux;
	}

	/**
	 * Encodea un mensaje de SUCCESS en formato JSON para que JS lo interprete como tal
	 *
	 * @param string
	 * @return string
	 *
	 */
	public static function jsonSuccess($msg = '', $obj = null){
		$json = new Core_JsonResponse();
		$json->code = Core_JsonResponse::CODE_SUCCESS;
		$json->status = Core_JsonResponse::STATUS_SUCCESS;
		$json->message = $msg;
		$json->data = $obj;
		return self::jsonEncode($json);
	}

	/**
	 * Encodea un mensaje de ERROR en formato JSON para que JS lo interprete como tal
	 *
	 * @param string $msg
	 * @param object $obj
	 * @return string
	 */
	public static function jsonError($msg = '', $obj = null){
		$json = new Core_JsonResponse();
		$json->code = Core_JsonResponse::CODE_ERROR;
		$json->status = Core_JsonResponse::STATUS_ERROR;
		$json->message = $msg;
		$json->data = $obj;
		return self::jsonEncode($json);
	}

	/**
	 * Encodea NULL en formato JSON para que JS lo interprete como tal
	 *
	 * @param void
	 * @return string
	 *
	 */
	public static function jsonNull(){
		return self::jsonEncode();
	}

	/**
	 * Devuelve un JSON vacío para que JS lo interprete como tal
	 *
	 * @param void
	 * @return string
	 *
	 */
	public static function jsonEmpty(){
		return self::jsonEncode(array());
	}

	/**
	 * Devuelve un JSON de CONFIRM para que JS lo interprete como tal
	 *
	 * @param string
	 * @param string
	 * @return string
	 *
	 */
	public static function jsonConfirm($msg, $codigo){
		return self::jsonEncode(array($msg, $codigo, ''));
	}
}

?>