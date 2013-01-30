<?php

namespace Flush\Core\HTTP\Request;

abstract class BaseRequest 
{
	protected $data;
	
	public function __get($name){
		return $this->data[$name];
	}
}

?>
