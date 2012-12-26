<?php

require_once('../include/initialize.inc.php');

try {
	$array = array();
	$canchas = Base::getListObject('Cancha');
	foreach($canchas as $cancha) {
		$arrSocios = array();
		foreach ($cancha->turnoActual->socios->get() as $socio){
			$arrSocios[] = $socio->socio;
		}
		$cancha->turnoActual->socios = $arrSocios;
		$arrSocios = array();
		foreach ($cancha->turnoSiguiente->socios->get() as $socio){
			$arrSocios[] = $socio->socio;
		}
		$cancha->turnoSiguiente->socios = $arrSocios;
		$arrCancha = array(
			'idCancha' => $cancha->getId(),
			'numero' => $cancha->numero,
			'superficie' => $cancha->superficie,
			'estado' => $cancha->estado,
			'turnoActual' => array(
				'horaInicio' => $cancha->turnoActual->horaInicio,
				'horaFin' => $cancha->turnoActual->horaFin,
				'socios' => $cancha->turnoActual->socios
			),
			'proximoTurnoOcupado' => array(
				'horaInicio' => $cancha->turnoSiguiente->horaInicio,
				'horaFin' => $cancha->turnoSiguiente->horaFin,
				'socios' => $cancha->turnoSiguiente->socios
			)
		);
		if (count($cancha->turnosLibres) > 0)
			$arrCancha['proximoTurnoLibre'] = array(
				'horaInicio' => $cancha->turnosLibres[0]->horaInicio,
				'horaFin' => $cancha->turnosLibres[0]->horaFin
			);
		$array[] = $arrCancha;
	}
	$json = new jsonResponse();
	$json->code = 1;
	$json->status = 'success';
	$json->message = '';
	$json->data = array('canchas' => $array);
	echo HTML::jsonEncode($json);
} catch (Exception $ex) {
	echo HTML::jsonError($ex->getMessage());
}

?>