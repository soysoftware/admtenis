<?php

namespace Flush\Core\HTTP\Request;

class PostRequest extends BaseRequest 
{
	public function __construct() {
		$this->data = $_POST;
	}
}

?>