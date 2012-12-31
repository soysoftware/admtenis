<?php
require_once '../system/bootstrap.inc.php';
$turno = new Turno(1);
$socio = $turno->socioAnfitrion;
$socio->nombre = 'Juan Pablo';
$socio->apellido = 'Segundo';
$socio->save();
$lucasgay = 'lamentablemente es MUY gay probando desde la consola';

?>
