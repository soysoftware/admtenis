<?php
/**
 * @author Lucas Ceballos
 * @since 11/02/2012
 * @version 0.0.1
 */

class FlushRequest {
	private static $_controller;
	private static $_action;
	private static $_parameter;
	private static $_vars = array(
		'get' => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
		'post' => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
		'put' => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
		'delete' => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
		'ajax' => array('env' => 'HTTP_X_REQUESTED_WITH', 'value' => 'XMLHttpRequest'),
		'mobile' => array('env' => 'HTTP_USER_AGENT', 'options' => array(
			'Android', 'AvantGo', 'BlackBerry', 'DoCoMo', 'Fennec', 'iPod', 'iPhone', 'iPad',
			'J2ME', 'MIDP', 'NetFront', 'Nokia', 'Opera Mini', 'Opera Mobi', 'PalmOS', 'PalmSource',
			'portalmmm', 'Plucker', 'ReqwirelessWeb', 'SonyEricsson', 'Symbian', 'UP\\.Browser',
			'webOS', 'Windows CE', 'Windows Phone OS', 'Xiino'
		))
	);

	public static function server($var) {
		return $_SERVER[$var];
	}

	public static function request($var) {
		if (self::is('GET')) {
			return self::get($var);
		}
		if (self::is('POST')) {
			return self::post($var);
		}
		if (self::is('PUT')) {
			return self::put($var);
		}
		if (self::is('DELETE')) {
			return self::delete($var);
		}
		return $_REQUEST[$var];
	}

	public static function get($var) {
		return $_GET[$var];
	}

	public static function post($var) {
		return $_POST[$var];
	}

	public static function getPostObject() {
		return json_decode(json_encode($_POST));
	}

	public static function put($var) {
		$put = json_decode(file_get_contents('php://input'));
		return $put[$var];
	}

	public static function getPutObject() {
		return json_decode(file_get_contents('php://input'));
	}

	public static function delete($var) {
		$delete = json_decode(file_get_contents('php://input'));
		return $delete[$var];
	}

	public static function getDeleteObject() {
		return json_decode(file_get_contents('php://input'));
	}

	public static function is($type) {
		$type = strtolower($type);
		if (!isset(self::$_vars[$type])) {
			return false;
		}
		$var = self::$_vars[$type];
		if (isset($var['env'])) {
			if (isset($var['value'])) {
				return self::server($var['env']) == $var['value'];
			}
			if (isset($var['options'])) {
				$pattern = '/' . implode('|', $var['options']) . '/i';
				return (bool)preg_match($pattern, self::server($var['env']));
			}
		}
		return false;
	}

	/**
	 * @param Controller|string $controller
	 * @return Controller
	 */
	public static function controller($controller = null) {
		if (!is_null($controller)) {
			if (is_a($controller, 'Controller')) {
				self::$_controller = $controller;
			} elseif (is_string($controller) && class_exists($controller)) {
				self::$_controller = new $controller;
			} elseif (is_string($controller) && class_exists($controller . 'Controller')) {
				$controllerName = $controller . 'Controller';
				self::$_controller = new $controllerName;
			}
		}
		if (!isset(self::$_controller)) {
			$aux = explode('/', self::get('request'));
			$controllerName = ((!empty($aux[0])) ? ucfirst($aux[0]) : 'NotFound') . 'Controller';
			if (class_exists($controllerName)) {
				self::$_controller = new $controllerName;
			} else {
				self::$_controller = new NotFoundController();
			}
		}
		return self::$_controller;
	}

	public static function action() {
		if (!isset(self::$_action)) {
			$aux = explode('/', self::get('request'));
			$actionName = (!empty($aux[1])) ? strtolower($aux[1]) : 'index';
			if (method_exists(self::controller(), $actionName)) {
				self::$_action = $actionName;
			}
		}
		return self::$_action;
	}

	public static function parameter() {
		if (!isset(self::$_parameter)) {
			$aux = explode('/', self::get('request'));
			self::$_parameter = (!empty($aux[2])) ? $aux[2] : null;
		}
		return self::$_parameter;
	}
}

?>
