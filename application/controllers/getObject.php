<?php

require_once('../include/initialize.inc.php');

$objectClass = Functions::request('clase');
$objectId = Functions::request('id');
$objectAttr = Functions::request('attr');
try {
	$object = new $objectClass($objectId);
	$json = new jsonResponse();
	$json->code = 1;
	$json->status = 'success';
	$json->message = '';
	if (isset($objectAttr)) {
		try {
			$result = $object->$objectAttr;
			if (Functions::getType($result) == 'array') {
				$aux = array();
				foreach ($result as $item)
					if (is_object($item))
						$aux[] = $item->expand();
				$result = $aux;
			} elseif (is_object($result))
				$result = $result->expand();
			$json->data = array('object' => $result);
		} catch (Exception $ex) {
			$json->data = array('object' => null);
		}
	} else
		$json->data = $object->expand();
	exit(HTML::jsonEncode($json));
} catch (CustomExceptionRegistroNoExiste $ex) {
	exit(HTML::jsonError('No existe el "' . $objectClass . '". Código: ' . $objectId));
} catch (Exception $ex) {
	exit(HTML::jsonError($ex->getMessage()));
}

?>