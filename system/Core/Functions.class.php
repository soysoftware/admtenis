<?php

/**
 * Clase con métodos statics de funciones varias
 * Muchos son wrappers
 *
 */

class Core_Functions {
	/**
	 * Compara dos horarios y devuelve true si el primero es mayor o igual al segundo
	 * 
	 * @param string
	 * @param string
	 * @return boolean
	 */
	static function esHoraMayorIgual($hora1, $hora2) {
		$h1 = self::explodeHora($hora1);
		$h2 = self::explodeHora($hora2);
		if ($h1[0] > $h2[0])
			return true;
		elseif($h1[0] == $h2[0] && $h1[1] > $h2[1])
			return true;
		elseif($h1[0] == $h2[0] && $h1[1] == $h2[1] && $h1[2] >= $h2[2])
			return true;
		return false;
	}

	/**
	 * Compara dos horarios y devuelve true si el primero es menor o igual al segundo
	 * 
	 * @param string
	 * @param string
	 * @return boolean
	 */
	static function esHoraMenorIgual($hora1, $hora2) {
		$h1 = self::explodeHora($hora1);
		$h2 = self::explodeHora($hora2);
		if ($h1[0] < $h2[0])
			return true;
		elseif($h1[0] == $h2[0] && $h1[1] < $h2[1])
			return true;
		elseif($h1[0] == $h2[0] && $h1[1] == $h2[1] && $h1[2] <= $h2[2])
			return true;
		return false;
	}

	/**
	 * Devuelve la hora de la próxima fracción posible
	 * Si se le manda un parámetro, la hora base es esa
	 *
	 * @param null $horaBase
	 * @return string
	 */
	static function getProximaHora($horaBase = null) {
		$duracion = Parametro::get(2);
		if (isset($horaBase))
			$h = self::explodeHora($horaBase);
		else
			$h = self::explodeHora(self::time());
		if ($h[1] < $duracion)
			$h[1] = $duracion;
		else {
			$h[1] = $duracion;
			$h = self::explodeHora(self::sumaMinutos(self::implodeHora($h), $duracion));
		}
		$h[2] = 0;
		$return = self::implodeHora($h);
		return $return;
	}
	
	/**
	 * Devuelve un array con la hora en int
	 * 
	 * @param string
	 * @return array
	 */
	static function explodeHora($stringHora) {
		$h = explode(':', $stringHora);
		$h[0] = self::toInt($h[0]);
		$h[1] = self::toInt($h[1]);
		$h[2] = self::toInt($h[2]);
		return $h;
	}
	
	/**
	 * Devuelve un string de hora con el formato '00:00:00' a partir de un array
	 * 
	 * @param array
	 * @return string
	 */
	static function implodeHora($h) {
		$h[0] = self::padLeft($h[0], 2, '0');
		$h[1] = self::padLeft($h[1], 2, '0');
		$h[2] = self::padLeft($h[2], 2, '0');
		$return = implode(':', $h);
		return $return;
	}

	/**
	 * Le resta $sustraccion minutos a la hora $original
	 * 
	 * @param string
	 * @param int
	 * @return string
	 */
	static function restaMinutos($original, $sustraccion) {
		$h = self::explodeHora($original);
		$h[1] -= self::toInt($sustraccion);
		if ($h[1] <= 0) {
			$horasDeMenos = floor(abs($h[1]) / 60) + 1;
			$h[0] -= $horasDeMenos;
			$h[1] += self::toInt(($horasDeMenos) * 60);
		}
		$return = self::implodeHora($h);
		return $return;
	}

	/**
	 * Le suma $adicion minutos a la hora $original
	 * 
	 * @param string
	 * @param int
	 * @return string
	 */
	static function sumaMinutos($original, $adicion) {
		$h = self::explodeHora($original);
		$h[1] += self::toInt($adicion);
		if ($h[1] >= 60) {
			$horasDeMas = floor($h[1] / 60);
			$h[0] += $horasDeMas;
			$h[1] -= self::toInt($horasDeMas * 60);
		}
		$return = self::implodeHora($h);
		return $return;
	}

	/**
	 * Pad Right
	 * 
	 * @param mixed
	 * @param int
	 * @param string
	 * @return string
	 */
	static function padRight($obj, $cant, $char = ' ') {
		return str_pad($obj, $cant, $char, STR_PAD_RIGHT);
	}

	/**
	 * Pad Left
	 *
	 * @param mixed $obj
	 * @param int $cant
	 * @param string $char
	 * @return string
	 */
	static function padLeft($obj, $cant, $char = ' ') {
		return str_pad($obj, $cant, $char, STR_PAD_LEFT);
	}

	/**
	 * Formatea un número con la cantidad de decimales indicada
	 * Por defecto usa 2 decimales
	 * 
	 * @param mixed
	 * @param int
	 * @return string
	 */
	static function formatDecimals($numero, $decimales = 2) {
		$numero = str_replace(',', '.', $numero);
		return number_format($numero, abs($decimales), ',', '');
	}

	/**
	 * Devuelve la fecha y hora actual, en formato "AÑO-MES-DIA HORA:MIN:SEG"
	 *
	 * @return string
	 *
	 */
	static function now() {
		return self::formatDate(time(), 'Y-m-d H:i:s');
	}

	/**
	 * Devuelve la fecha actual, en formato "AÑO-MES-DIA"
	 *
	 * @return string
	 *
	 */
	static function today() {
		return self::formatDate(time(), 'Y-m-d');
	}

	/**
	 * Devuelve la hora actual, en formato "HORA:MIN:SEG"
	 *
	 * @return string
	 *
	 */
	static function time() {
		return self::formatDate(time(), 'H:i:s');
	}

	/**
	 * Puede formatear los siguientes formatos:
	 * dd/mm/aaaa[ hh:mm]
	 * dd-mm-aaaa[ hh:mm]
	 * dd MES aaaa[ hh:mm]
	 *
	 * $today = date("d/m/Y H:i");						// 10/03/2001 05:16
	 * $today = date("F j, Y, g:i a");					// March 10, 2001, 5:16 pm
	 * $today = date("m.d.y");							// 03.10.01
	 * $today = date("j, n, Y");						// 10, 3, 2001
	 * $today = date("Ymd");							// 20010310
	 * $today = date('h-i-s, j-m-y, it is w Day');		// 05-16-18, 10-03-01, 1631 1618 6 Satpm01
	 * $today = date('\i\t \i\s \t\h\e jS \d\a\y.');	// it is the 10th day.
	 * $today = date("D M j G:i:s T Y");				// Sat Mar 10 17:16:18 MST 2001
	 * $today = date('H:m:s \m \i\s\ \m\o\n\t\h');		// 17:03:18 m is month
	 * $today = date("H:i:s");							// 17:16:18
	 */
	static function formatDate($fecha, $formato = 'd/m/Y') {
		if (count($arr = explode(' ', $fecha)) > 2){
			$arrayMeses = array( 'ene' => '01', 'feb' => '02', 'mar' => '03', 'abr' => '04',
					'may' => '05', 'jun' => '06', 'jul' => '07', 'ago' => '08',
					'sep' => '09', 'oct' => '10', 'nov' => '11', 'dic' => '12');
			$arr[1] = $arrayMeses[strtolower($arr[1])];
		} elseif (count($arr = explode('-', $fecha)) > 2) {
		} elseif (count($arr = explode('/', $fecha)) > 2) {
		} else {
			return date($formato, $fecha);
		}
		$fecha = $arr[1] . '/' . $arr[0] . '/' . $arr[2];
		if (isset($arr[4]))
			$fecha .= ' ' . $arr[4];
		elseif (isset($arr[3]))
			$fecha .= ' ' . $arr[3];
		$returnDate = date($formato, strtotime($fecha));
		return $returnDate;
	}

	/**
	 * Devuelve el nombre del tipo de variable que se le envía por parámetro
	 * Si es un objeto, devuelve su clase
	 *
	 * @param mixed
	 * @return string
	 *
	 */
	static function getType($var) {
		if (is_object($var))
			return get_class($var);
		if (is_null($var))
			return 'null';
		if (is_string($var))
			return 'string';
		if (is_array($var))
			return 'array';
		if (is_int($var))
			return 'int';
		if (is_bool($var))
			return 'bool';
		if (is_float($var))
			return 'float';
		if (is_double($var))
			return 'double';
		return '';
	}

	/**
	 * De ser posible, convierte una variable en entero
	 *
	 * @param mixed
	 * @return int
	 *
	 */
	static function toInt($var) {
		if (is_int($var))
			return $var;
		elseif (is_scalar($var))
			return (int)$var;
		else
			return (int)0;
	}

	/**
	 * De ser posible, convierte una variable en float
	 *
	 * @param mixed
	 * @return float
	 *
	 */
	static function toFloat($var) {
		if (is_float($var))
			return $var;
		elseif (is_scalar($var))
			return (float)$var;
		else
			return (float)0;
	}

	/**
	 * De ser posible, convierte una variable en double
	 *
	 * @param mixed
	 * @return float
	 */
	static function toDouble($var) {
		if (is_double($var))
			return $var;
		elseif (is_scalar($var))
			return (double)$var;
		else
			return (double)0;
	}

	/**
	 * De ser posible, convierte una variable en string
	 *
	 * @param mixed
	 * @return string
	 */
	static function toString($var) {
		if (is_string($var))
			return trim($var);
		elseif (is_scalar($var))
			return trim($var);
		else
			return '';
	}

	/**
	 * Obtiene un valor enviado por GET
	 * Si no es nulo ni vacío lo devuelve decodificado (UTF8)
	 *
	 * @param mixed
	 * @return mixed
	 *
	 */
	static function get($valor) {
		if ((!isset($_GET[$valor])) || (empty($_GET[$valor])))
			return null;
		return self::utfDecode($_GET[$valor]);
	}

	/**
	 * Obtiene un valor enviado por POST
	 * Si no es nulo ni vacío lo devuelve decodificado (UTF8)
	 *
	 * @param mixed
	 * @return mixed
	 *
	 */
	static function post($valor) {
		if ((!isset($_POST[$valor])) || (empty($_POST[$valor])))
			return null;
		return self::utfDecode($_POST[$valor]);
	}

	/**
	 * Obtiene un valor enviado por REQUEST
	 * Si no es nulo ni vacío lo devuelve decodificado (UTF8)
	 *
	 * @param mixed
	 * @return mixed
	 *
	 */
	static function request($valor) {
		if ((!isset($_REQUEST[$valor])) || (empty($_REQUEST[$valor])))
			return null;
		return self::utfDecode($_REQUEST[$valor]);
	}

	/**
	 * Decodifica toda una variable a UTF8
	 * Puede ser array, escalar u objeto
	 *
	 * @param mixed
	 * @return mixed
	 *
	 */
	static function utfDecode($obj){
		if (!isset($obj))
			return $obj;
		if (is_scalar($obj))
			return utf8_decode($obj);
		else {
			foreach($obj as $id => $val){
				if (is_scalar($val))
					$val = utf8_decode($val);
				else {
					if (count($val) != 0)
						$val = self::utfDecode($val);
					}
				$obj[$id] = $val;
			}
		}
		return $obj;
	}

	/**
	 * Se fija segun el nombre del atributo si es un campo de base de datos (para generar las consultas)
	 *
	 * @param string
	 * @return bool
	 *
	 */
	static function esAtributoDB($attrName){
		if ( empty($attrName) || ($attrName[0] == '_') ) {
			return false;
		}		
		return true;
	}

	/**
	 * Se fija según el nombre del atributo si es un campo cargado por Lazy Loading (para el expand)
	 *
	 * @param string
	 * @return bool
	 *
	 */
	static function esAtributoLL($attrName){
		if ($attrName[0] == '_' && $attrName != '_primaryKeyValue' && $attrName != '_original') {
			return true;
		}
		return false;
	}

	/**
	 * Compara los atributos públicos de dos objetos de la misma clase
	 * Si son idénticos devuelve TRUE, sino FALSE 
	 *
	 * @param object
	 * @param object
	 * @return bool
	 *
	 */
	static function compareObjects($obj1, $obj2){
		if (!isset($obj1) || !isset($obj2))
			return false;
		if (!is_object($obj1) || !is_object($obj2))
			return false;
		if (self::getType($obj1) != self::getType($obj2))
			return false;
		foreach($obj1 as $attr => $value){
			if ($obj1->$attr != $obj2->$attr)
				return false;
		}
		return true;
	}

	/**
	 * Devuelve un array con los atributos PÚBLICOS de un objeto
	 *
	 * @param object
	 * @return array
	 *
	 */
	static function getObjectVars($object){
		$attributes = array();
		foreach($object as $attr => $value){
			$attributes[$attr] = $value;
		}
		return $attributes;
	}
}

?>