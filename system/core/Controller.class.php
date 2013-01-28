<?php


class Controller {
	/**
	 * The name of the Controller
	 *
	 * @var null|string
	 */
	public $name = null;

	/**
	 * Set to true to automatically render the view
	 * after action logic.
	 *
	 * @var boolean
	 */
	public $autoRender = true;

	/**
	 * Holds any validation errors produced by the last call of the validateErrors() method/
	 *
	 * @var array Validation errors, or false if none
	 */
	public $validationErrors = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ($this->name === null) {
			$this->name = substr(get_class($this), 0, -10);
		}
	}

	/**
	 * Dispatches the controller action.  Checks that the action
	 * exists and isn't private.
	 *
	 * @return mixed The resulting response.
	 * @throws PrivateActionException When actions are not public or prefixed by _
	 * @throws MissingActionException When actions are not defined and scaffolding is
	 *    not enabled.
	 */
	public final function run() {
		try {
			$method = new ReflectionMethod($this, FlushRequest::action());
			if (!$method->isPublic()) {
				//throw new PrivateActionException(array(
				throw new Exception(array('controller' => $this->name . 'Controller', 'action' => FlushRequest::action()));
			}
			$body = $method->invokeArgs($this, array(FlushRequest::parameter()));
			$body = (json_decode($body) == null ? json_encode($body) : $body);
			$this->response()->body($body);
			FlushResponse::send();
		} catch (ReflectionException $ex) {
			//throw new MissingActionException(array(
			throw new Exception(array('controller' => $this->name . 'Controller', 'action' => FlushRequest::action()));
		}
	}

	public function response() {
		return FlushResponse::getInstance();
	}
}
