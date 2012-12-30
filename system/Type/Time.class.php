<?php

/**
 * Clase para el tipo de dato Time. Hereda de Type
 */

class Type_Time extends Type_Type {
	/**
	 * Método que valida esta constraint
	 * 
	 * @param mixed $val
	 * @param mixed $settings
	 * @return bool
	 * @throws Exception_ConstraintException
	 */
	protected function validate($val) {
		if(!preg_match('/(24:00:00)|(([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9])/', $val)){
			throw new Exception_TypeException($val , 'Time');
		}
		return true;
	}

	public function hour(){
		$a = explode(':', $this->val);
		return $a[0];
	}

	public function minutes(){
		$a = explode(':', $this->val);
		return $a[1];
	}

	public function seconds(){
		$a = explode(':', $this->val);
		return $a[2];
	}

	static function toMinutes($time) {
		self::check($time);
		$a = explode(':', $time);
		return ($a[0] * 60 + $a[1]);
	}

	static function toSeconds($time) {
		self::check($time);
		$a = explode(':', $time);
		return ($a[0] * 60 + $a[1] * 60 + $a[2]);
	}

	static function isEarlierThan($time1, $time2) {
		self::check($time1);
		self::check($time2);
		return strtotime($time1) < strtotime($time2);
	}

	static function isLaterThan($time1, $time2) {
		self::check($time1);
		self::check($time2);
		return strtotime($time1) > strtotime($time2);
	}

	static function addMinutes($time, $minutes) {
		self::check($time);
		return date('H:i:s', strtotime('+' . $minutes . ' minutes', strtotime($time)));
	}

	static function substracMinutes($time, $minutes) {
		self::check($time);
		return date('H:i:s', strtotime('-' . $minutes . ' minutes', strtotime($time)));
	}

	static function addSeconds($time, $seconds) {
		self::check($time);
		return date('H:i:s', strtotime('+' . $seconds . ' seconds', strtotime($time)));
	}

	static function substractSeconds($time, $seconds) {
		self::check($time);
		return date('H:i:s', strtotime('-' . $seconds . ' seconds', strtotime($time)));
	}

	static function addTime($time1, $time2) {
		self::check($time1);
		self::check($time2);
		return date('H:i:s', strtotime('+' . self::toSeconds($time2) . ' seconds', strtotime($time1)));
	}

	static function subtractTime($time1, $time2) {
		self::check($time1);
		self::check($time2);
		return date('H:i:s', strtotime('-' . self::toSeconds($time2) . ' seconds', strtotime($time1)));
	}
}

?>