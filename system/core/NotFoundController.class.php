<?php


class NotFoundController extends Controller {
	public function index() {
		FlushResponse::statusCode(404);
		$this->render();
	}
}

?>