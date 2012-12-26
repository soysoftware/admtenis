<?php
require_once('../include/initialize.inc.php');

/**
 * Controller que crea una nueva reserva (turno)
 * Desde JS (dsp de pasar por todas las comprobaciones y etapas en JS),
 * debe llegar un único objeto llamado 'reserva' que debe contener los siguientes datos:
 * $reserva['idCancha'];
 * $reserva['socios']; //Es un array de ID's de los jugadores
 * $reserva['idSocioAnfitrion'];
 * $reserva['horaInicio'];
 * $reserva['horaFin'];
 * $reserva['luz'];
 */

//$reserva = Core_Functions::post('reserva');
$reserva = json_decode(Core_Functions::get('reserva'));

try {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_LOCK (por el tema de quedanTurnos() y eso)
	$turno = new Turno();

	//Agrego la cancha
	$turno->cancha = new Cancha($reserva->idCancha);

	//Agrego los socios
	if (count($reserva->socios) != 2 && count($reserva->socios) != 4) {
		throw new Exception('La cantidad de jugadores es incorrecta (deben ser 2 o 4 jugadores)');
	}
	foreach ($reserva->socios as $idSocio) {
		$turno->addSocio(new Socio($idSocio));
	}

	//Agrego el socio anfitrión, que se supone que ya fue "comprobado"
	$turno->socioAnfitrion = new Socio($reserva->idSocioAnfitrion);

	//Agrego la hora de inicio
	$turno->horaInicio = $reserva->horaInicio;

	//Agrego la hora de fin
	$turno->horaFin = $reserva->horaFin;

	//Agrego la hora de luz
	$turno->luz = $reserva->luz;

	$turno->save();

	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
} catch (Exception_RecordNotFound $ex) {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
} catch (Exception $ex) {
	//ACÁ DEBERÍAMOS HACER UN MUTEX_UNLOCK
}

?>