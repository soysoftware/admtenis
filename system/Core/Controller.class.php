<?php

namespace Flush\Core;

class Controller {
	public $name = null;
	public $request;
	public $response;

	public function __construct($request) {
		if ($this->name === null) {
			$this->name = substr(get_class($this), 0, -10);
		}
		$this->request = $request;
	}

	public final function run($action = 'index', $parameter = null) {
		try {
			$method = new ReflectionMethod($this, $action);
			if (!$method->isPublic()) {
				//throw new PrivateActionException(array(
				throw new \Exception(array('controller' => $this->name . 'Controller', 'action' => $action));
			}
			$method->invoke($this, $parameter);
			return $this->response;
		} catch (\ReflectionException $ex) {
			//throw new MissingActionException(array(
			throw new \Exception(array('controller' => $this->name . 'Controller', 'action' => $action));
		}
	}
}
