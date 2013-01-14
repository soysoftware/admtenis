<?php
try{
	echo memory_get_usage() . "\n";
	require_once '../system/bootstrap.inc.php';
	$turno = new Turno(1);
	$socio = $turno->socioAnfitrion;
	$socio->nombre = 'Lucas';
	$socio->apellido = 12345;
	$socio->save();
	$id = $socio->id;
	$apc = new Flush\Core\Modules\Cache\Drivers\Memcached();
	$result = $apc->set('probando', 'probando el apc');
	$recuperado = $apc->get('probando');
	$result = $apc->set('probando', 'valor nuevo 2', true);
	$recuperado = $apc->get('probando');
	$result = $apc->clean();
	$recuperado = $apc->get('probando');
	echo memory_get_usage() . "\n";
} catch (Exception $ex){
	die($ex->getMessage());
}
?>
