<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class Core_RelationalObject extends Core_Base {
	private $_action = 0;

	public function __construct($idObj = null){
		parent::__construct($idObj);
	}

	/**
	 * @ignore
	 *
	 * @return int
	 */
	protected function getAction(){
		return $this->_action;
	}

	/**
	 * @ignore
	 * 
	 * @param int $action
	 * @return Core_RelationalObject
	 */
	protected function setAction($action){
		$this->_action = $action;
		return $this;
	}
}

?>
