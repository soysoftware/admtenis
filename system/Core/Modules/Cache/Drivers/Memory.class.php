<?php

namespace Flush\Core\Modules\Cache\Drivers;

class Memory extends BaseDriver {
	
	private $cache = array();
	
	public function get($key){
		if (isset($this->cache[$hash = call_user_func($this->hashFunction, $key)])){
			$deathTime = $this->cache[$hash]['deathTime'];
			if ($deathTime == 0 || $deathTime > time()) {
				return $this->cach[$hash]['val'];
			} else {
				unset($this->cache[$hash]);
			}
		}
		return false;
	}
	
	public function set($key, $value, $overwrite = false, $expiration = 0){
		$hash = call_user_func($this->hashFunction, $key);
		if (!$overwrite && isset($this->cache($hash))){
			return false;
		}
		$this->cache[$hash] = array('val' => $value , 'deathTime' => $expiration > 0 ? time() + $expiration : 0);
		return true;
	}
	
	public function delete($key){
		if (isset($this->cache[$hash = call_user_func($this->hashFunction, $key)])){
			unset($this->cache[$hash]);
			return true;
		}
		return false;
	}
	
	public function increment($key, $step = 1){
		if (isset($this->cache[$hash = call_user_func($this->hashFunction, $key)])){
			if (is_numeric($this->cache[$hash]['val'])){
				$this->cache[$hash]['val'] = $this->cache[$hash]['val'] + $step;
			}
		}
		return false;
	}
	
	public function decrement($key, $step = 1){
		if (isset($this->cache[$hash = call_user_func($this->hashFunction, $key)])){
			if (is_numeric($this->cache[$hash]['val'])){
				$this->cache[$hash]['val'] = $this->cache[$hash]['val'] - $step;
			}
		}
		return false;
	}
	
	public function clean(){
		$this->cache = array();
		return true;
	}
	
}

?>