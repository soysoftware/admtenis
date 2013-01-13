<?php
use Flush\Core\TypedModel;
use Flush\Core\BaseModel;
use Flush\Type\BaseType;

/**
 * 
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 * 
 */

/**
 * @property Int					$idTurno
 * @property Int					$idCancha
 * @property Cancha					$cancha
 * @property Type_Date				$fecha
 * @property Type_Time				$horaInicio
 * @property Type_Date				$horaFin
 * @property Int					$idSocioAnfitrion
 * @property Socio					$socioAnfitrion
 * @property Core_RelationalList	$socios
 * @property Turno					$proximoTurno
 * @property Bool					$luz
 * @property Int					$tipoPartido
 * @property Int					$estado
 */

class Turno extends TypedModel {
	const _table = 'turno';
	const _primaryKeyName = '_idTurno';

	const ESTADO_ESPERANDO = 1;
	const ESTADO_JUGANDO = 2;
	const ESTADO_JUGADO = 3;
	const ESTADO_NO_JUGADO = 4;
	
	/**
	 * @type Int
	 * @constraint Positive
	 */
	protected	$_idTurno;
	/**
	 * @type Int
	 * @constraint Positive
	 */
	protected	$_idCancha;
	protected	$__cancha;
	protected	$_fecha;
	protected	$_horaInicio;
	protected	$_horaFin;
	/**
	 * @type Int
	 * @constraint Positive
	 */
	protected	$_idSocioAnfitrion;
	/**
	 * @type Object
	 * @constraint Class Socio
	 * @constraint ReferenceValue _idSocioAnfitrion
	 */
	protected	$__socioAnfitrion;
	protected	$__socios;
	protected	$__proximoTurno;
	protected	$_luz = 0;
	protected	$_tipoPartido = 1;
	protected	$_estado = 1;
	private		$_deleted = 0;

    // Getters
	/**
	 * @ignore
	 *
	 * @return Cancha
	 */
	protected function getCancha(){
		if(!isset($this->_cancha)){
			$this->_cancha = new Cancha($this->idCancha, true);
		}
		return $this->_cancha;
	}
	/**
	 * @ignore
	 *
	 * @return array
	 */
	protected function getSocios(){
		if(!isset($this->__socios)){
			$this->__socios = new Core_RelationalList('SocioPorTurno', $this->getArrayPrimaryKey());
		}
		return $this->__socios;
	}

	/**
	 * @ignore
	 *
	 * @return Turno
	 */
	public function getProximoTurno(){
		if (!isset($this->__proximoTurno)) {
			$turno = new Turno;
			$where = 'idCancha = ' . Core_DBI::getInstance()->objectToDB($this->idCancha);
			$where .= ' AND fecha = ' . Core_DBI::getInstance()->objectToDB($this->fecha);
			$where .= ' AND horaInicio > ' . Core_DBI::getInstance()->objectToDB($this->horaInicio);
			$where .= ' ORDER BY horaInicio ASC';
			$turnos = Core_Base::getObjectList('Turno', $where, null, true);
			if (count($turnos) > 0)
				$turno = $turnos[0];
			$this->__proximoTurno = $turno;
		}
		return $this->__proximoTurno;
	}

	// Setters
	/**
	 * @ignore
	 * 
	 * @param Cancha
	 * @return Turno
	 */
	protected function setCancha(Cancha $cancha){
		if ($cancha->bloqueadaPorTorneo()) {
			if (!Parametro::get(4)) { //Es el parámetro de la lista de espera (1 o 0)
				throw new Exception('La cancha está bloqueada por torneo');
			}
		} else {
			if (!$cancha->quedanTurnos()) {
				throw new Exception('No quedan turnos en esa cancha');
			}
		}

		$this->__cancha = $cancha;
		$this->idCancha = $cancha->id;
		return $this;
	}
	/**
	 * @ignore
	 * 
	 * @param Type_Date
	 * @return Turno
	 */
	protected function setHoraInicio($horaInicio){
		/*
		$horaValidada = false;
		$elegirHoraInicio = Parametro::get(9); //Es el parámetro para saber si se puede elegir la hora de inicio (0 o 1)
		if (!$elegirHoraInicio) {
			$horaInicio = $this->cancha->getProximaHoraInicio();
			if (!$horaInicio) {
				throw new Exception('No hay más turnos disponibles');
			}
			$horaValidada = true;
		} elseif ($elegirHoraInicio == 2) { // Si es 2, se puede elegir hora de inicio, pero tiene que ser posterior a la hora de luz
			$horaLuz = Parametro::get(12); //Hora a partir de la cual se prende la luz
			if (Type_Time::isEarlierThan($horaInicio, $horaLuz)) {
				$horaInicio = $this->cancha->getProximaHoraInicio();
				$horaValidada = true;
			}
		}
		if (!$horaValidada && !$turno->cancha->esHoraInicioPosible($horaInicio)) {
			throw new Exception('La hora de inicio resulta incorrecta');
		}

		$this->_horaInicio->val = $horaInicio;
		return $this;
		*/
	}
	/**
	 * @ignore
	 * 
	 * @param Type_Date
	 * @return Turno
	 */
	protected function setHoraFin($horaFin){
		/*
		$durMin = (count($this->socios) == 2 ? Parametro::get(10) : Parametro::get(16));
		$durMax = (count($this->socios) == 2 ? Parametro::get(11) : Parametro::get(17));
		$horaFin = ($durMin == $durMax ? Type_Time::addMinutes($this->horaInicio, $durMax) : $reserva->horaFin);
		$duracion = Type_Time::toMinutes(Type_Time::subtractTime($horaFin, $this->horaInicio));
		if ($duracion < $durMin || $duracion > $durMax || !$this->cancha->esHoraFinPosible($horaFin)) {
			throw new Exception('La hora de fin resulta incorrecta');
		}
		$this->_horaFin->val = $horaFin;
		return $this;
		*/
	}

	/**
	 * @ignore
	 * 
	 * @param array
	 * @return Turno
	 */
	protected function setSocios($socios){
		$this->__socios = $socios;
		return $this;
	}
	/**
	 * @ignore
	 * 
	 * @param Socio
	 * @return Turno
	 */
	protected function setSocioAnfitrion(Socio $socioAnfitrion){
		//Debe setearse después de los socios, ya que uno de ellos debe ser el anfitrión
		foreach ($this->socios->getArray() as $socioPorTurno) {
			if ($socioPorTurno->socio->idSocio == $socioAnfitrion->idSocio) {
				$this->__socioAnfitrion = $socioAnfitrion;
				$this->idSocioAnfitrion = $socioAnfitrion->id;
				return $this;
			}
		}
		throw new Exception('No se pudo establecer el socio anfitrión');
	}

	// Metodos
	/**
	 * Añade un socio a la lista de socios asociados al turno
	 * 
	 * @param Socio
	 * @return void
	 */
	public function addSocio(Socio $socio){
		//TODO: Acá TENGO QUE HACER! las comprobaciones para saber si este socio puede jugar
		$socioPorTurno = new SocioPorTurno();
		$socioPorTurno->socio = $socio;
		$socioPorTurno->turno = clone $this;
		$socioPorTurno->_idTurno = &$this->_idTurno;
		$this->socios->add($socioPorTurno);
	}
}

?>