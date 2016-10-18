<?php
require_once(dirname(__FILE__).'/Controller.php');

class RegisterController extends Controller {

	public function __construct() {
		parent::__construct('register');
	}

	public function actionIndex() {
		$this->render('index');
	}
}