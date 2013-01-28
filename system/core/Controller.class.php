<?php


class Controller {
	public $name = null;

	/**
	 * An array containing the class names of models this controller uses.
	 *
	 * Example: `public $uses = array('Product', 'Post', 'Comment');`
	 *
	 * Can be set to several values to express different options:
	 *
	 * - `true` Use the default inflected model name.
	 * - `array()` Use only models defined in the parent class.
	 * - `false` Use no models at all, do not merge with parent class either.
	 * - `array('Post', 'Comment')` Use only the Post and Comment models. Models
	 *   Will also be merged with the parent class.
	 *
	 * The default value is `true`.
	 *
	 * @var mixed A single name as a string or a list of names as an array.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = true;

	/**
	 * An array containing the names of helpers this controller uses. The array elements should
	 * not contain the "Helper" part of the classname.
	 *
	 * Example: `public $helpers = array('Html', 'Javascript', 'Time', 'Ajax');`
	 *
	 * @var mixed A single name as a string or a list of names as an array.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $helpers = array();

	/**
	 * An instance of a CakeRequest object that contains information about the current request.
	 * This object contains all the information about a request and several methods for reading
	 * additional information about the request.
	 *
	 * @var CakeRequest
	 * @link http://book.cakephp.org/2.0/en/controllers/request-response.html#cakerequest
	 */
	public $request;

	/**
	 * An instance of a CakeResponse object that contains information about the impending response
	 *
	 * @var CakeResponse
	 * @link http://book.cakephp.org/2.0/en/controllers/request-response.html#cakeresponse
	 */
	public $response;

	/**
	 * The classname to use for creating the response object.
	 *
	 * @var string
	 */
	protected $_responseClass = 'CakeResponse';

	/**
	 * The name of the views subfolder containing views for this controller.
	 *
	 * @var string
	 */
	public $viewPath = null;

	/**
	 * The name of the layouts subfolder containing layouts for this controller.
	 *
	 * @var string
	 */
	public $layoutPath = null;

	/**
	 * Contains variables to be handed to the view.
	 *
	 * @var array
	 */
	public $viewVars = array();

	/**
	 * The name of the view file to render. The name specified
	 * is the filename in /app/View/<SubFolder> without the .ctp extension.
	 *
	 * @var string
	 */
	public $view = null;

	/**
	 * The name of the layout file to render the view inside of. The name specified
	 * is the filename of the layout in /app/View/Layouts without the .ctp
	 * extension.
	 *
	 * @var string
	 */
	public $layout = 'default';

	/**
	 * Set to true to automatically render the view
	 * after action logic.
	 *
	 * @var boolean
	 */
	public $autoRender = true;

	/**
	 * Set to true to automatically render the layout around views.
	 *
	 * @var boolean
	 */
	public $autoLayout = true;

	/**
	 * Instance of ComponentCollection used to handle callbacks.
	 *
	 * @var ComponentCollection
	 */
	public $Components = null;

	/**
	 * Array containing the names of components this controller uses. Component names
	 * should not contain the "Component" portion of the classname.
	 *
	 * Example: `public $components = array('Session', 'RequestHandler', 'Acl');`
	 *
	 * @var array
	 * @link http://book.cakephp.org/2.0/en/controllers/components.html
	 */
	public $components = array('Session');

	/**
	 * The name of the View class this controller sends output to.
	 *
	 * @var string
	 */
	public $viewClass = 'View';

	/**
	 * Instance of the View created during rendering. Won't be set until after
	 * Controller::render() is called.
	 *
	 * @var View
	 */
	public $View;

	/**
	 * File extension for view templates. Defaults to Cake's conventional ".ctp".
	 *
	 * @var string
	 */
	public $ext = '.ctp';

	/**
	 * Automatically set to the name of a plugin.
	 *
	 * @var string
	 */
	public $plugin = null;

	/**
	 * Used to define methods a controller that will be cached. To cache a
	 * single action, the value is set to an array containing keys that match
	 * action names and values that denote cache expiration times (in seconds).
	 *
	 * Example:
	 *
	 * {{{
	 * public $cacheAction = array(
	 *		'view/23/' => 21600,
	 *		'recalled/' => 86400
	 *	);
	 * }}}
	 *
	 * $cacheAction can also be set to a strtotime() compatible string. This
	 * marks all the actions in the controller for view caching.
	 *
	 * @var mixed
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/cache.html#additional-configuration-options
	 */
	public $cacheAction = false;

	/**
	 * Holds all params passed and named.
	 *
	 * @var mixed
	 */
	public $passedArgs = array();

	/**
	 * Triggers Scaffolding
	 *
	 * @var mixed
	 * @link http://book.cakephp.org/2.0/en/controllers/scaffolding.html
	 */
	public $scaffold = false;

	/**
	 * Holds current methods of the controller. This is a list of all the methods reachable
	 * via url. Modifying this array, will allow you to change which methods can be reached.
	 *
	 * @var array
	 */
	public $methods = array();

	/**
	 * This controller's primary model class name, the Inflector::singularize()'ed version of
	 * the controller's $name property.
	 *
	 * Example: For a controller named 'Comments', the modelClass would be 'Comment'
	 *
	 * @var string
	 */
	public $modelClass = null;

	/**
	 * This controller's model key name, an underscored version of the controller's $modelClass property.
	 *
	 * Example: For a controller named 'ArticleComments', the modelKey would be 'article_comment'
	 *
	 * @var string
	 */
	public $modelKey = null;

	/**
	 * Holds any validation errors produced by the last call of the validateErrors() method/
	 *
	 * @var array Validation errors, or false if none
	 */
	public $validationErrors = null;

	/**
	 * The class name of the parent class you wish to merge with.
	 * Typically this is AppController, but you may wish to merge vars with a different
	 * parent class.
	 *
	 * @var string
	 */
	protected $_mergeParent = 'AppController';

	/**
	 * Instance of the CakeEventManager this controller is using
	 * to dispatch inner events.
	 *
	 * @var CakeEventManager
	 */
	protected $_eventManager = null;

	/**
	 * Constructor.
	 *
	 * @param CakeRequest $request Request object for this controller. Can be null for testing,
	 *  but expect that features that use the request parameters will not work.
	 * @param CakeResponse $response Response object for this controller.
	 */
	public function __construct($request = null, $response = null) {
		if ($this->name === null) {
			$this->name = substr(get_class($this), 0, -10);
		}

		if ($this->viewPath == null) {
			$this->viewPath = $this->name;
		}

		$this->modelClass = Inflector::singularize($this->name);
		$this->modelKey = Inflector::underscore($this->modelClass);
		$this->Components = new ComponentCollection();

		$childMethods = get_class_methods($this);
		$parentMethods = get_class_methods('Controller');

		$this->methods = array_diff($childMethods, $parentMethods);

		if ($request instanceof CakeRequest) {
			$this->setRequest($request);
		}
		if ($response instanceof CakeResponse) {
			$this->response = $response;
		}
		parent::__construct();
	}

	/**
	 * Provides backwards compatibility to avoid problems with empty and isset to alias properties.
	 * Lazy loads models using the loadModel() method if declared in $uses
	 *
	 * @param string $name
	 * @return void
	 */
	public function __isset($name) {
		switch ($name) {
			case 'base':
			case 'here':
			case 'webroot':
			case 'data':
			case 'action':
			case 'params':
				return true;
		}

		if (is_array($this->uses)) {
			foreach ($this->uses as $modelClass) {
				list($plugin, $class) = pluginSplit($modelClass, true);
				if ($name === $class) {
					return $this->loadModel($modelClass);
				}
			}
		}

		if ($name === $this->modelClass) {
			list($plugin, $class) = pluginSplit($name, true);
			if (!$plugin) {
				$plugin = $this->plugin ? $this->plugin . '.' : null;
			}
			return $this->loadModel($plugin . $this->modelClass);
		}

		return false;
	}

	/**
	 * Provides backwards compatibility access to the request object properties.
	 * Also provides the params alias.
	 *
	 * @param string $name
	 * @return void
	 */
	public function __get($name) {
		switch ($name) {
			case 'base':
			case 'here':
			case 'webroot':
			case 'data':
				return $this->request->{$name};
			case 'action':
				return isset($this->request->params['action']) ? $this->request->params['action'] : '';
			case 'params':
				return $this->request;
			case 'paginate':
				return $this->Components->load('Paginator')->settings;
		}

		if (isset($this->{$name})) {
			return $this->{$name};
		}

		return null;
	}

	/**
	 * Provides backwards compatibility access for setting values to the request object.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'base':
			case 'here':
			case 'webroot':
			case 'data':
				return $this->request->{$name} = $value;
			case 'action':
				return $this->request->params['action'] = $value;
			case 'params':
				return $this->request->params = $value;
			case 'paginate':
				return $this->Components->load('Paginator')->settings = $value;
		}
		return $this->{$name} = $value;
	}

	/**
	 * Sets the request objects and configures a number of controller properties
	 * based on the contents of the request.  The properties that get set are
	 *
	 * - $this->request - To the $request parameter
	 * - $this->plugin - To the $request->params['plugin']
	 * - $this->view - To the $request->params['action']
	 * - $this->autoLayout - To the false if $request->params['bare']; is set.
	 * - $this->autoRender - To false if $request->params['return'] == 1
	 * - $this->passedArgs - The the combined results of params['named'] and params['pass]
	 *
	 * @param CakeRequest $request
	 * @return void
	 */
	public function setRequest(CakeRequest $request) {
		$this->request = $request;
		$this->plugin = isset($request->params['plugin']) ? Inflector::camelize($request->params['plugin']) : null;
		$this->view = isset($request->params['action']) ? $request->params['action'] : null;
		if (isset($request->params['pass']) && isset($request->params['named'])) {
			$this->passedArgs = array_merge($request->params['pass'], $request->params['named']);
		}

		if (array_key_exists('return', $request->params) && $request->params['return'] == 1) {
			$this->autoRender = false;
		}
		if (!empty($request->params['bare'])) {
			$this->autoLayout = false;
		}
	}

	/**
	 * Dispatches the controller action.  Checks that the action
	 * exists and isn't private.
	 *
	 * @param CakeRequest $request
	 * @return mixed The resulting response.
	 * @throws PrivateActionException When actions are not public or prefixed by _
	 * @throws MissingActionException When actions are not defined and scaffolding is
	 *    not enabled.
	 */
	public function invokeAction(CakeRequest $request) {
		try {
			$method = new ReflectionMethod($this, $request->params['action']);

			if ($this->_isPrivateAction($method, $request)) {
				throw new PrivateActionException(array(
													  'controller' => $this->name . "Controller",
													  'action' => $request->params['action']
												 ));
			}
			return $method->invokeArgs($this, $request->params['pass']);

		} catch (ReflectionException $e) {
			if ($this->scaffold !== false) {
				return $this->_getScaffold($request);
			}
			throw new MissingActionException(array(
												  'controller' => $this->name . "Controller",
												  'action' => $request->params['action']
											 ));
		}
	}

	/**
	 * Check if the request's action is marked as private, with an underscore,
	 * or if the request is attempting to directly accessing a prefixed action.
	 *
	 * @param ReflectionMethod $method The method to be invoked.
	 * @param CakeRequest $request The request to check.
	 * @return boolean
	 */
	protected function _isPrivateAction(ReflectionMethod $method, CakeRequest $request) {
		$privateAction = (
			$method->name[0] === '_' ||
			!$method->isPublic() ||
			!in_array($method->name,  $this->methods)
		);
		$prefixes = Router::prefixes();

		if (!$privateAction && !empty($prefixes)) {
			if (empty($request->params['prefix']) && strpos($request->params['action'], '_') > 0) {
				list($prefix) = explode('_', $request->params['action']);
				$privateAction = in_array($prefix, $prefixes);
			}
		}
		return $privateAction;
	}

	/**
	 * Convenience and object wrapper method for CakeResponse::header().
	 *
	 * @param string $status The header message that is being set.
	 * @return void
	 * @deprecated Use CakeResponse::header()
	 */
	public function header($status) {
		$this->response->header($status);
	}

	/**
	 * Returns number of errors in a submitted FORM.
	 *
	 * @return integer Number of errors
	 */
	public function validate() {
		$args = func_get_args();
		$errors = call_user_func_array(array(&$this, 'validateErrors'), $args);

		if ($errors === false) {
			return 0;
		}
		return count($errors);
	}

	/**
	 * Validates models passed by parameters. Example:
	 *
	 * `$errors = $this->validateErrors($this->Article, $this->User);`
	 *
	 * @param mixed A list of models as a variable argument
	 * @return array Validation errors, or false if none
	 */
	public function validateErrors() {
		$objects = func_get_args();

		if (empty($objects)) {
			return false;
		}

		$errors = array();
		foreach ($objects as $object) {
			if (isset($this->{$object->alias})) {
				$object = $this->{$object->alias};
			}
			$object->set($object->data);
			$errors = array_merge($errors, $object->invalidFields());
		}

		return $this->validationErrors = (!empty($errors) ? $errors : false);
	}

	/**
	 * Instantiates the correct view class, hands it its data, and uses it to render the view output.
	 *
	 * @param string $view View to use for rendering
	 * @param string $layout Layout to use
	 * @return CakeResponse A response object containing the rendered view.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#Controller::render
	 */
	public function render($view = null, $layout = null) {
		$event = new CakeEvent('Controller.beforeRender', $this);
		$this->getEventManager()->dispatch($event);
		if ($event->isStopped()) {
			$this->autoRender = false;
			return $this->response;
		}

		if (!empty($this->uses) && is_array($this->uses)) {
			foreach ($this->uses as $model) {
				list($plugin, $className) = pluginSplit($model);
				$this->request->params['models'][$className] = compact('plugin', 'className');
			}
		}

		$viewClass = $this->viewClass;
		if ($this->viewClass != 'View') {
			list($plugin, $viewClass) = pluginSplit($viewClass, true);
			$viewClass = $viewClass . 'View';
			App::uses($viewClass, $plugin . 'View');
		}

		$View = new $viewClass($this);

		$models = ClassRegistry::keys();
		foreach ($models as $currentModel) {
			$currentObject = ClassRegistry::getObject($currentModel);
			if (is_a($currentObject, 'Model')) {
				$className = get_class($currentObject);
				list($plugin) = pluginSplit(App::location($className));
				$this->request->params['models'][$currentObject->alias] = compact('plugin', 'className');
				$View->validationErrors[$currentObject->alias] =& $currentObject->validationErrors;
			}
		}

		$this->autoRender = false;
		$this->View = $View;
		$this->response->body($View->render($view, $layout));
		return $this->response;
	}



}
