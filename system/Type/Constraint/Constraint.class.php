<?php
namespace Flush\Type\Constraint;
/**
 * Clase base de las clases de restricciones como MaxLength, NotNull, NotEmpty
 */

abstract class Constraint {
	abstract static function check($val, $settings);
}

?>