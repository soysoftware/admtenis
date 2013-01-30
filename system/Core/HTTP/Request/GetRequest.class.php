<?php

namespace Flush\Core\HTTP\Request;

class GetRequest extends BaseRequest 
{
	public function __construct() {
		$this->data = $_GET;
	}
}

?>
