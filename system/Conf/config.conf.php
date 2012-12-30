<?php
	# Directorios
	$config['appdir'] = 'application';
	$config['ctrdir'] = 'controllers';
	$config['mdldir'] = 'class';
	# Environment
	$config['env'] = 'dev'; // dev = development | tst = testing | prod = produccion
	# Bases de datos
	$config['dbs']['autoConnect'] = true;
	$config['dbs'][0]['driver'] = 'mysqli';
	$config['dbs'][0]['autoConnect'] = true;
	$config['dbs'][0]['host'] = 'localhost';
	$config['dbs'][0]['user'] = 'root';
	$config['dbs'][0]['pass'] = 'Graciela';
	$config['dbs'][0]['port'] = 3306;
	$config['dbs'][0]['encoding'] = 'utf8';
		
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_NAME', 'adm_tennis');
	
	define('LOG_DIR' , 'C:\xampp\htdocs\admtenis\log\\');
	define('APP_PATH' , 'C:\xampp\htdocs\admtenis\\');
	//define('LOG_DIR' , 'D:\htdocs\lanus\log\\');
	//define('APP_PATH' , 'D:\htdocs\lanus\\');
	//define('APP_PATH' , 'D:\htdocs\adm_lanus\\');
	
?>