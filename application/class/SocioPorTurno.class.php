<?php
/**
 * 
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 * 
 */

class SocioPorTurno extends Core_RelationalObject {
	const _table = 'socioPorTurno';
	const _primaryKeyName = '_idSocioPorTurno';

	protected	$_idSocioPorTurno;
	public		$_idSocio;
	protected	$__socio;
	public		$_idTurno;
	protected	$__turno;
	private		$_deleted = 0;

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return Socio
	 */
	protected function getSocio(){
		if(!isset($this->_socio)){
			$this->_socio = new Socio($this->idSocio);
		}
		return $this->_socio;
	}

	/**
	 * @ignore
	 * 
	 * @param Socio
	 * @return SocioPorTurno
	 */
	protected function setSocio(Socio $socio){
		$this->__socio = $socio;
		$this->_idSocio = $socio->idSocio;
		return $this;
	}

	/**
	 * @ignore
	 * 
	 * @param void
	 * @return Turno
	 */
	protected function getTurno(){
		if(!isset($this->_turno)){
			$this->_turno = new Turno($this->idTurno);
		}
		return $this->_turno;
	}

	/**
	 * @ignore
	 * 
	 * @param Turno
	 * @return SocioPorTurno
	 */
	protected function setTurno(Turno $turno){
		$this->__turno = $turno;
		$this->_idTurno = $turno->idTurno;
		return $this;
	}
}

?>