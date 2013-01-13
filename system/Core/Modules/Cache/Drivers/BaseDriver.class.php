<?php

namespace Flush\Core\Modules\Cache\Drivers;

abstract class BaseDriver {
	
	protected $hashFunction = 'md5';
	
	// Metodos abstractos
	public abstract function get($key);
	public abstract function set($key, $value, $overwrite = false, $expiration = 0);
	public abstract function delete($key);
	public abstract function increment($key, $step = 1);
	public abstract function decrement($key, $step = 1);
	public abstract function clean();
	
	// Metodos generales
	public function setHashFunction($hashFunction){
		if (!is_callable($hashFunction)){
			throw new \Exception('La function de hash especificada no es valida');
		}
		$this->hashFunction = $hashFunction;
	}
}


?>