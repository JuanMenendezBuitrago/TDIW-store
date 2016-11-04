<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/User.php');

class UserController extends Controller {

	public function __construct() {
		parent::__construct('user');
	}

	/**
	 * Render the registration form.
	 * @return void
	 */
	public function actionRegister() {
		// add id to body tag
		$this->bodyId = "register";
		// add script for the registration script 
		$this->scripts[] = "register-form.js";

		$this->render('register');
	}
	
	/**
	 * Receive data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		$user = new User($_POST);
		try{
			$result = $user->save();
        	
        	if(!$result->hasErrors()) {
				$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
			} 
			$errors = $result->getErrors();
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$errors)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}
	}
}