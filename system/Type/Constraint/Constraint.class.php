<?php

/**
 * Clase base de las clases de restricciones como MaxLength, NotNull, NotEmpty
 */

abstract class Type_Constraint_Constraint {
	const PREFIX = 'Type_Constraint_';	
	abstract static function check($val, $settings);
}

?>