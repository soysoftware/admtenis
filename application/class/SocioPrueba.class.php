<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

/**
 * @property Int	$idSocio
 * @property String	$nombre
 * @property String	$apellido
 * @property String	$nombreApellido
 * @property Date	$penalizado
 * @property Turno	$turnoActual
 * @property Turno	$turnoParaCheckIn
 * @property array	$turnosPorJugar
 * @property Int	$estado
 */

class SocioPrueba extends Core_Base {
	
	const _table = 'socio';
	const _primaryKeyName = '_idSocio';

	const ESTADO_NORMAL = 1;
	const ESTADO_INHABILITADO = 2;
	const ESTADO_SANCIONADO = 3;

	/**
	 * 
	 * 
	 * @type Int
	 * @constraint Positive
	 * 
	 * 
	 */
	protected	$_idSocio;
	/**
	 * @type String
	 * @constraint MaxLength 50
	 * @constraint NotNull
	 * @constraint NotEmpty
	 */
	protected	$_nombre;
	/**
	 * @type String
	 * @constraint MaxLength 50
	 * @constraint NotNull
	 * @constraint NotEmpty
	 * 
	 */
	protected	$_apellido;
	/**
	 * @type Date
	 */
	protected	$_penalizado;
	protected	$__turnoActual;
	protected	$__turnoParaCheckIn;
	protected	$__turnosPorJugar;
	protected	$_estado = 1;
	private		$__deleted = 0;

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return string
	 */
	protected function getNombreApellido(){
		return $this->nombre . ' ' . $this->apellido;
	}

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return string
	 */
	protected function getTurnoActual(){
		if(!isset($this->_turnoActual)){
			$this->__turnoActual = new Turno();
			$query = 'SELECT spt.idTurno FROM socioPorTurno spt INNER JOIN turno t ON spt.idTurno = t.idTurno WHERE';
			$query .= ' spt.idSocio = ' . Core_DBI::getInstance()->objectToDB($this->idSocio);
			$query .= ' AND t.estado = 2 ORDER BY t.horaInicio ASC';
			$result = Core_DBI::getInstance()->query($query);
			$arrTurnos = array();
			if (Core_DBI::getInstance()->numRows($result) == 1) {
				$row = Core_DBI::getInstance()->fetchAssoc($result);
				$this->__turnoActual = new Turno($row['idTurno']);
			}
		}
		return $this->__turnoActual;
	}

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return string
	 */
	protected function getTurnoParaCheckIn(){
		if(!isset($this->__turnoParaCheckIn)){
			$this->__turnoParaCheckIn = new Turno();
			$minutosCheckIn = new Parametro(7);
			$restaCheckIn = Core_Functions::sumaMinutos('00:00:00', $minutosCheckIn->valor);
			$minutosTolerancia = new Parametro(8);
			$sumaTolerancia = Core_Functions::sumaMinutos('00:00:00', $minutosTolerancia->valor);
			$where = 't2.idSocio = ' . Core_DBI::getInstance()->objectToDB($this->idSocio);
			$where .= ' AND t1.estado = 1 ';
			$where .= ' AND ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time()) . ' > TIMEDIFF(t1.horaInicio, ' . Core_DBI::getInstance()->objectToDB($restaCheckIn) .  ')';
			$where .= ' AND ' . Core_DBI::getInstance()->objectToDB(Core_Functions::time()) . ' < TIMESUM(t1.horaInicio, ' . Core_DBI::getInstance()->objectToDB($sumaTolerancia) .  ')';
			$where .= ' ORDER BY t1.horaInicio ASC';
			$arrTurnos = Core_Base::getListObjectJoin('Turno', 'socioPorTurno', $where);
			if (count($arrTurnos) == 1)
				$this->__turnoParaCheckIn = $arrTurnos[0];
		}
		return $this->__turnoParaCheckIn;
	}

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return string
	 */
	protected function getTurnosPorJugar(){
		if(!isset($this->__turnosPorJugar)){
			$where = 't2.idSocio = ' . Core_DBI::getInstance()->objectToDB($this->idSocio);
			$where .= ' AND t1.estado = 1 ORDER BY t1.horaInicio ASC';
			$arrTurnos = Core_Base::getListObjectJoin('Turno', 'socioPorTurno', $where);
			$this->__turnosPorJugar = $arrTurnos;
		}
		return $this->__turnosPorJugar;
	}
}

?>