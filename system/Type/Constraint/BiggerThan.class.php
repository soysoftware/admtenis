<?php
namespace Flush\Type\Constraint;
use Flush\Exception as Exception;
/**
 * Clase constraint BiggerThan
 */

abstract class BiggerThan extends Constraint {
	/**
	 * Método que valida esta constraint
	 *
	 * @param mixed $val
	 * @param mixed $settings
	 * @throws Exception_ConstraintException
	 * @return boolean
	 */
	static public function check($val, $settings = 0) {
		if ($val > $settings) {
			return true;
		}
		throw new Exception\ConstraintException('debe ser mayor que ' . $settings);
	}
}

?>