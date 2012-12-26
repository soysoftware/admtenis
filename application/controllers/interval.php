<?php

require_once('../include/initialize.inc.php');


function interval() {
	$horaFinDia = new Parametro(1);
	$duracion = new Parametro(2);
	$horaInicioDia = new Parametro(3);
	if (Functions::esHoraMayorIgual(Functions::time(), $horaFinDia->valor))
		return;
	if (!Functions::esHoraMayorIgual(Functions::time(), Functions::restaMinutos($horaInicioDia->valor, $duracion->valor)));
		return;
		
	actualizarTurnos();
	controlarListaEspera();
	controlarCheckIn();
	actualizarCanchas();
}

function actualizarTurnos() {
}

function controlarListaEspera() {
	$listaDeEspera = new Parametro(4);
	if ($listaDeEspera->valor == 0)
		return;
	
}

function controlarCheckIn() {
}

function actualizarCanchas() {
}

try {
	interval();
} catch (Exception $ex) {
	//Logueo el error
	echo HTML::jsonError($ex->getMessage());
}

?>