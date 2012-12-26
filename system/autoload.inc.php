<?php

spl_autoload_register('autoload');

function autoload($class){
	include APP_PATH . './class/' . str_replace('_', '/', $class) . '.class.php';
}

?>