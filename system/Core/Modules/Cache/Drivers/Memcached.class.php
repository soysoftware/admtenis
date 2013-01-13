<?php
namespace Flush\Core\Modules\Cache\Drivers;

class Memcached extends BaseDriver {
	
	const DEFAULT_PORT = 11211;
	
	private $memcachedObj;
	
	public function __construct($servers = array(0 => array('host' => 'localhost' , 'port' => self::DEFAULT_PORT , 'weight' => 0))){
		if (!extension_loaded('memcached')) {
			throw new \Exception('Error al iniciar cache Memcached, modulo no instalado o desactivado');
		}
		$this->memcachedObj = new \Memcached();
		foreach ($servers as $server){
			$this->memcachedObj->addServer($server['host'], $server['port'], $server['weight']);
		}
	}
	
	
	public function get($key){
		return $this->memcachedObj->get(call_user_func($this->hashFunction, $key));
	}
	
	public function set($key, $value, $overwrite = false, $expiration = null){
		return $overwrite ? $this->memcachedObj->set( call_user_func($this->hashFunction, $key), $value, $expiration) : $this->memcachedObj->add(call_user_func($this->hashFunction, $key), $value, $expiration);
	}
	
	public function delete($key){
		$this->memcachedObj->delete(call_user_func($this->hashFunction, $key));
	}
	
	public function increment($key, $step = 1){
		return $this->memcachedObj->increment(call_user_func($this->hashFunction, $key), $step);
	}
	
	public function decrement($key, $step = 1){
		return $this->memcachedObj->decrement(call_user_func($this->hashFunction, $key), $step);
	}
	
	public function clean(){
		return $this->memcachedObj->flush();
	}
}
?>