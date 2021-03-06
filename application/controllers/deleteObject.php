<?php

require_once('../include/initialize.inc.php');

$postObject = Core_Functions::post('objToInsert');
$postClass = Core_Functions::post('objClass');

try {
	/*
	Esto es modificar genérico:
	viene un objeto, y su clase
	Hago el new (con ID)
	Delete!
	*/
	$pkName = $postClass::_primaryKeyName;
	if (!isset($postObject[$pkName]))
		throw new Exception('No puede eliminarse el objeto ya que no existe');
	$object = new $postClass($postObject[$pkName]);
	$object->delete();
	echo Core_HTML::jsonSuccess('El/la "' . $postClass . '" fue eliminado/a correctamente', $object);
} catch (Exception $ex) {
	echo Core_HTML::jsonError($ex->getMessage());
}

?>