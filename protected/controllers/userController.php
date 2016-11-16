<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/User.php');
require_once(dirname(__FILE__).'/../models/Admin.php');

class UserController extends Controller {

	public function __construct() {
		parent::__construct('user');
	}

	public function actionLogin() {
		try{
			// is it a regular user?
			$user = new User($_POST);
			$authenticated = $user->authenticate();
			if($authenticated != false)  {
				$_SESSION['user'] = $authenticated;
				$this->_sendJSONResponse(200,  json_encode(array('result'=>$authenticated)));
			}

			// is it an admin
			$admin = new Admin($_POST);
			$authenticated = $admin->authenticate();
			if($authenticated != false)  {
				$_SESSION['user'] = $authenticated;
				$this->_sendJSONResponse(200,  json_encode(array('result'=>$authenticated)));
			}

			$this->_sendJSONResponse(200,  json_encode(array('result'=>$newUser)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}

	/**
	 * Receive data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		switch($GLOBALS['request_method']){
			case 'POST':
				$user = new User($_POST);
				try{
					$result = $user->save();
					$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
				}catch(PDOException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
				}catch (ValidationException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
				}
			case 'GET':
			default:
				// add id to body tag
				$this->bodyId = "register";
				$this->bodyClass = "form-page";
				// add script for the registration script 
				$this->scripts[] = "register-form.js";

				$user = new User();
				$this->render('update', array('user'=>$user, 'method'=>'post'));
				break;
			}
		}
	/**
	 * Render the update form.
	 * @return void
	 */
	public function actionUpdate($params) {
		// deal with REST request
		if($GLOBALS['request_method']=='PUT'){
			parse_str(file_get_contents("php://input"),$_POST);
			$user = new User($_POST);
			$user->id = $params['id'];
			
			try{
				$result = $user->update();
	        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
			}catch(PDOException $e) {
				$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
			}catch (ValidationException $e) {
				$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
			}
		}


		// add id to body tag
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		// add script for the registration script 
		$this->scripts[] = "register-form.js";

		$user = new User();
		if(isset($params['id'])) {
			try {
				$user = $user->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->render('update', array('user'=>$user, 'method'=>'put'));
	}
}