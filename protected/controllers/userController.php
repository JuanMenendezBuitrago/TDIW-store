<?php
require_once(dirname(__FILE__).'/Controller.php');

class UserController extends Controller {

	public function __construct() {
		parent::__construct('user');
	}

	public function actionRegister() {
		$this->bodyId = "register";
		$this->scripts[] = "register-form.js";
		if($this->_checkToken()){
			// $this->_sendJSONResponse(200,  json_encode(array('message'=>"foo")));
			$this->_sendJSONResponse(400, json_encode(array('errors'=>array(
				'card'=>'qué coño has pesto aquí?',
			))));
		}

		$_SESSION['token'] = uniqid();
		$this->render('register', ['token'=>$_SESSION['token']]);
	}
}