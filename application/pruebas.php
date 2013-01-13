<?php
require_once '../system/bootstrap.inc.php';
$turno = new Turno(1);
$socio = $turno->socioAnfitrion;
$socio->nombre = 'Lucas';
$socio->apellido = 'Ceballos';
$socio->save();
$lucasgay = 'lamentablemente es MUY gay probando desde la consola';
$id = $socio->id;
?>
