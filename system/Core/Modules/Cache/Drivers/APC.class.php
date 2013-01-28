<?php

namespace Flush\Core\Modules\Cache\Drivers;


class APC extends BaseDriver {
	
	public function __construct($config = null){
		if (!extension_loaded('apc') || !ini_get('apc.enabled')) {
			throw new \Exception('Error al iniciar cache APC, modulo no instalado o desactivado');
		}
	}
	
	public function get($key){
		return apc_fetch(call_user_func($this->hashFunction, $key));	
	}
	
	public function set($key, $value, $overwrite = false, $expiration = 0){
		return $overwrite ? apc_store(call_user_func($this->hashFunction, $key), $value, $expiration) : apc_add(call_user_func($this->hashFunction, $key), $value, $expiration);
	}
	
	public function delete($key){
		return apc_delete(call_user_func($this->hashFunction, $key));
	}
	
	public function increment($key, $step = 1){
		return apc_inc(call_user_func($this->hashFunction, $key), $step);
	}
	
	public function decrement($key, $step = 1){
		return apc_dec(call_user_func($this->hashFunction, $key), $step);
	}
	
	public function clean(){
		return apc_clear_cache();
	}	
	
}
?>