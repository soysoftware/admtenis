<?php
require_once('../include/initialize.inc.php');

/**
 * Controller que cancela una reserva (turno) existente
 * Desde JS (dsp de pasar por todas las comprobaciones y etapas en JS),
 * debe llegar un único objeto llamado 'reserva' que debe contener los siguientes datos:
 * $reserva['idTurno'];
 */

//$reserva = Core_Functions::post('reserva');
$reserva = json_decode(Core_Functions::get('reserva'));

try {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_LOCK (por el tema de ?)
	$turno = new Turno($reserva['idTurno']);

	//Penalizo
	$minAntes = Parametro::get(7); //Tiempo check-in (antes de la hora)
	$minTolerancia = Parametro::get(8); //Tiempo tolerancia (pasada la hora)
	$sePenaliza = Parametro::get(14); //Penalización si cancela en tiempo de checkIn
	
	/*
	 * Penalizaciones activadas?
	* 		Estoy dentro del tiempo de check-in?
	* 			Penalizar;
	*/

	//Cancelo el turno
	$minAntes = Parametro::get(7); //Tiempo check-in (antes de la hora)
	$minTolerancia = Parametro::get(8); //Tiempo tolerancia (pasada la hora)
	$sePenaliza = Parametro::get(14); //Penalización si cancela en tiempo de checkIn

	/*
	 * Se anula el turno, se pone como ESTADO_NO_JUGADO;
	 * Alcanza el tiempo sobrante para jugar un partido nuevo (Single o Doble)?
	 * 		SI -> Se libera el turno;
	 *
	 */

	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
} catch (Exception_RecordNotFound $ex) {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
} catch (Exception $ex) {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
}

?>