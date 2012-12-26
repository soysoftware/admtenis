<?php

/**
 * Clase de Exception para cuando un registro no existe
 */

class Exception_RecordNotFound extends Exception {
    public function __construct($class, $id){
        $this->message = 'No existe registro para ' . $class . ' con ID: ' . $id;
	}
}

?>