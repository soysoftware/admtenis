<?php
	# Directorios
	$config['appdir'] = 'application';
	$config['ctrdir'] = 'controllers';
	$config['mdldir'] = 'class';
	# Environment
	$config['env'] = 'dev'; // dev = development | tst = testing | prod = produccion
	# Bases de datos
	$config['dbAutoConnect'] = true;
	$config['dbs'][0]['driver'] = 'mysqli';
	$config['dbs'][0]['autoConnect'] = true;
	$config['dbs'][0]['host'] = 'localhost';
	$config['dbs'][0]['user'] = 'root';
	$config['dbs'][0]['pass'] = 'Graciela';
	$config['dbs'][0]['database'] = 'admtenis';
	$config['dbs'][0]['charset'] = 'utf8';
	# Cache
	$config['cache']['descriptor']['enabled'] = true;
	$config['cache']['descriptor']['engine'] = 'apc';
	$config['cache']['descriptor']['expiration'] = 0;
	$config['cache']['descriptor']['servers'][0]['host'] = 'localhost';
	$config['cache']['model']['enabled'] = false;
	$config['cache']['model']['engine'] = 'memcached';
	$config['cache']['model']['expiration'] = 3600;
	$config['cache']['model']['servers'][0]['host'] = 'localhost';
	
?>