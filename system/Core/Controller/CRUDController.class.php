<?php
namespace Flush\Core\Controller;

abstract class CRUDController extends Controller
{
	public function create();
	
	public function read();
	
	public function update();
	
	public function delete();
}
?>