<?php
	# Definimos directorios basicos
	define('SYSDIR', dirname(__FILE__));
	define('ROOTDIR', ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . current(explode(DIRECTORY_SEPARATOR, ltrim(str_ireplace($_SERVER['DOCUMENT_ROOT'], '', __FILE__),DIRECTORY_SEPARATOR), -1))));
	
	# Archivo de configuracion
	$configFile = SYSDIR . '/conf/config.conf.php';
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
		$file = SYSDIR . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . str_replace('_', '/', $class) . '.class.php';
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
	
	# Bases de datos
	
	
?>
