<?php

/**
 * Clase base de las clases de restricciones como MaxLength, NotNull, NotEmpty
 */

abstract class Constraint_Constraint {
	const PREFIX = 'Constraint_';	
	abstract static function check($val, $settings);
}

?>