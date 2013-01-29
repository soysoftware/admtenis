<?php
	
use Flush\Core\Descriptor;
use Flush\Core\Config;
use Flush\Core\Modules\Cache\Drivers;

	# Definimos directorios basicos
	define('SYSDIR', dirname(__FILE__));
	define('ROOTDIR', ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . current(explode(DIRECTORY_SEPARATOR, ltrim(str_ireplace($_SERVER['DOCUMENT_ROOT'], '', __FILE__),DIRECTORY_SEPARATOR), -1))));
	
	# Archivo de configuracion
	$configFile = SYSDIR . '/Conf/config.conf.php';
	if (!file_exists($configFile)){
		die('No se ha encontrado el archivo de configuración');
	}
	require_once $configFile;
	if (!isset($config) || !is_array($config)){
		die('El archivo de configuracion no es correcto');
	}
	
	# Environment
	if ($config['env'] == 'dev'){
		error_reporting(E_ALL);
	} else {
		error_reporting(0);
	}
	
	# Constantes
	define('APPDIR', ROOTDIR . DIRECTORY_SEPARATOR . $config['appdir']);
	define('CTRDIR', APPDIR . DIRECTORY_SEPARATOR . $config['ctrdir']);
	define('MDLDIR', APPDIR . DIRECTORY_SEPARATOR . $config['mdldir']);
	
	# Auto Loaders
	spl_autoload_register(function($class){
		$file = SYSDIR . DIRECTORY_SEPARATOR . str_replace('\\', '/', str_replace('Flush\\' , '', $class)) . '.class.php';
		if (file_exists($file)){
			@include_once $file;
		}
	});
	spl_autoload_register(function($class){
		$file = MDLDIR . DIRECTORY_SEPARATOR . str_replace('_', '/', $class) . '.class.php';
		if (file_exists($file)){
			@include_once $file;
		}
	});
	
	# Clase Config
	Config::init($config);
	
	# Bases de datos
	if (Config::getVal('dbAutoConnect')){
		foreach( Config::getVal('dbs') as $dbName => $dbConfig ){
			if (isset($dbConfig['autoConnect']) && $dbConfig['autoConnect']){
				//Core_Modules_DB_Loader::load($dbName);
			}
		}
	}
		
	# Cache
	if (Config::getVal('cache', 'descriptor', 'enabled')){
	}
	
	# Ejecucion de controller
	$request = new Request();
	$aux = explode('/', $request->request('request'));
	$controllerName = ((!empty($aux[0])) ? ucfirst($aux[0]) : 'NotFound') . 'Controller';
	$actionName = (!empty($aux[1])) ? strtolower($aux[1]) : 'index';
	$parameter = (!empty($aux[2])) ? $aux[2] : null;
	
	$controller = class_exists($controllerName) ? new $controllerName($request) : new NotFoundController($request);
	
	$controller->run($actionName, $parameter);
	
?>
