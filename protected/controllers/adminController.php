<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Admin.php');

class AdminController extends Controller {

	public function __construct() {
		parent::__construct('admin');
	}

	/**
	 * Receive data via POST, create a new admin with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		switch($GLOBALS['request_method']){
			case 'POST':
				$admin = new Admin($_POST);
				try{
					$result = $admin->save();		        	
					$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
				}catch(PDOException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
				}catch (ValidationException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
				}
			case 'GET':
			default:
				// add id to body tag
				$this->bodyId = "create";
				$this->bodyClass = "form-page";
				// add script for the registration script 
				$this->scripts[] = "register-form.js";

				$admin = new Admin(); // empty admin
				$this->render('update', array('admin'=>$admin, 'method'=>'post'));
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
			$admin = new Admin($_POST);
			$admin->id = $params['id'];
			
			try{
				$result = $admin->update();
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

		$admin = new Admin();
		if(isset($params['id'])) {
			try {
				$admin = $admin->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->render('update', array('admin'=>$admin, 'method'=>'put'));
	}
}