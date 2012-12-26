<?php

require_once('../include/initialize.inc.php');

$postObject = Core_Functions::post('objToInsert');
$postClass = Core_Functions::post('objClass');

try {
	/*
	Esto es guardar genérico:
	viene un objeto, y su clase
	Hago el new (sin ID)
	Asigno todo con el fillObjectFromArray
	Save!
	*/
	$pkName = $postClass::_primaryKeyName;
	if (isset($postObject[$pkName]))
		throw new Exception('No puede guardarse como nuevo un objeto ya existente');
	$object = new $postClass;
	$object->fillObjectFromArray($postObject);
	$object->save();
	echo Core_HTML::jsonSuccess('El/la "' . $postClass . '" fue guardado/a correctamente', $object);
} catch (Exception $ex) {
	echo Core_HTML::jsonError($ex->getMessage());
}

?>