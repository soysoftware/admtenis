<?php
/**
 * 
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 * 
 */

/**
 * @property Int		$idCancha
 * @property Int		$numero
 * @property Superficie	$superficie
 * @property Bool		$luz
 * @property Bool		$habilitadaParaTorneo
 * @property array		$turnoActual
 * @property array		$turnoSiguiente
 * @property array		$turnosLibres
 */

class Cancha extends Core_Base {
	const _table = 'cancha';
	const _primaryKeyName = '_idCancha';
	const _descriptor = '
						{
							"idCancha": {
								"type"		:	"Int",
								"NotNull"	:	true
							},
							"numero": {
							   "type"		:	"Int"
							}
						}';

	const ESTADO_DISPONIBLE = 1;
	const ESTADO_OCUPADA = 2;
	const ESTADO_OCUPADA_TORNEO = 3;
	const ESTADO_NO_DISPONIBLE = 4;
	
	const SUPERFICIE_POLVO = 1;
	const SUPERFICIE_CEMENTO = 2;
	const SUPERFICIE_CESPED = 3;

	protected	$_idCancha;
	protected	$_numero;
	protected	$_superficie;	//Hacer CLASE superficie
	protected	$_luz;
	protected	$_habilitadaParaTorneo;
	protected	$__turnoActual;
	protected	$__turnoSiguiente;
	protected	$__turnosLibres;
	protected	$_estado = 1; //0: disponible, 1: ocupada, 2: ocupada por torneo, 3: no disponible
	private		$__deleted = 0;

	public function bloqueadaPorTorneo() {
		return $this->estado == self::ESTADO_OCUPADA_TORNEO;
	}

	public function quedanTurnos() {
		return count($this->turnosLibres) > 0;
	}

	public function getProximaHoraInicio() {
		if (count($this->turnosLibres) > 0) {
			return $this->turnosLibres[0]->horaInicio;
		}
		return false;
	}

	public function esHoraInicioPosible($horaInicio) {
		foreach ($this->turnosLibres as $turno) {
			if ($turno->horaInicio == $horaInicio) {
				return true;
			}
		}
		return false;
	}

	public function esHoraFinPosible($horaFin) {
		foreach ($this->turnosLibres as $turno) {
			if ($turno->horaFin == $horaFin) {
				return true;
			}
		}
		return false;
	}

	//Getters y setters

	/**
	 * @ignore
	 *
	 * @param void
	 * @return Turno
	 */
	public function getTurnoActual(){
		if (!isset($this->_turnoActual)) {
			$turno = new Turno();
			$where = 'idCancha = ' . Core_DBI::getInstance()->objectToDB($this->idCancha);
			$where .= ' AND estado = 2 AND fecha = ' . Core_DBI::getInstance()->objectToDB(Core_Functions::today());
			$where .= ' AND horaInicio < ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time());
			$where .= ' AND horaFin > ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time());
			$where .= ' ORDER BY fecha DESC, horaInicio DESC';
			$turnos = Core_Base::getObjectList('Turno', $where);
			if (count($turnos) > 0)
				$turno = $turnos[0];
			$this->_turnoActual = $turno;
		}
		return $this->_turnoActual;
	}

	/**
	 * @ignore
	 *
	 * @param void
	 * @return Turno
	 */
	public function getTurnoSiguiente(){
		if (!isset($this->_turnoSiguiente)) {
			$turno = new Turno;
			$where = 'idCancha = ' . Core_DBI::getInstance()->objectToDB($this->idCancha);
			$where .= ' AND estado = 1 AND fecha = ' . Core_DBI::getInstance()->objectToDB(Core_Functions::today());
			$where .= ' AND horaInicio > ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time());
			$where .= ' ORDER BY fecha ASC, horaInicio ASC';
			$turnos = Core_Base::getObjectList('Turno', $where);
			if (count($turnos) > 0)
				$turno = $turnos[0];
			$this->_turnoSiguiente = $turno;
		}
		return $this->_turnoSiguiente;
	}

	/**
	 * @ignore
	 *
	 * @param void
	 * @return array
	 */
	public function getTurnosLibres(){
		if (!isset($this->__turnosLibres)) {
			$horaFinDia = Parametro::get(1);
			$duracionFraccion = Parametro::get(2);
			$where = 'idCancha = ' . Core_DBI::getInstance()->objectToDB($this->idCancha);
			$where .= ' AND fecha = ' . Core_DBI::getInstance()->objectToDB(Core_Functions::today());
			$where .= ' AND horaInicio > ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time());
			$where .= ' ORDER BY horaInicio ASC';
			$turnos = Core_Base::getObjectList('Turno', $where);
			if ($this->turnoActual->id != null)
				$proxHora = $this->turnoActual->horaFin;
			else
				$proxHora = Core_Functions::getProximaHora();
			$arrTurnos = array();
			foreach($turnos as $turno) { //Tengo que hacer un loop que genere todos los turnos posibles, a partir de la lista de los vigentes (un while?)
				$break = false;
				$begin = $proxHora;
				while (!$break) {
					if (!Type_Time::isLaterThan(Type_Time::addMinutes($proxHora, $duracionFraccion), $turno->horaInicio)) {
						$proxHora = Core_Functions::sumaMinutos($proxHora, $duracionFraccion);
					} else {
						$break = true;
						$newTurno = new Turno();
						$newTurno->horaInicio = $begin;
						$newTurno->horaFin = $turno->horaInicio;
						$arrTurnos[] = $newTurno;
						$proxHora = $turno->horaFin;
					}
				}
			}
			if (!Type_Time::isLaterThan(Type_Time::addMinutes($proxHora, $duracionFraccion), $horaFinDia)) {
				//Acá tengo q crear muchos turnos de X duración (30 min)
				$newTurno = new Turno();
				$newTurno->horaInicio = $proxHora;
				$newTurno->horaFin = $horaFinDia;
				$arrTurnos[] = $newTurno;
			}
			$newArrTurnos = array();
			foreach($arrTurnos as $turno) {
				if ($turno->horaInicio != $turno->horaFin)
					$newArrTurnos[] = $turno;
			}
			$this->__turnosLibres = $newArrTurnos;
		}
		return $this->__turnosLibres;
	}
}

?>
