<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Category.php');

class CategoryController extends Controller {

	public function __construct() {
		parent::__construct('category');
	}

	/**
	 * Receive GET request or data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		switch($GLOBALS['request_method']){
			case 'POST':
				$category = new Category($_POST);
				try{
					$result = $category->save();
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
				
				$category = new Category();
				if(isset($params['id'])) {
					try {
						$category = $category->findById($params['id']);	
					}
					catch (PDOException $e) {

					}
				}
				$this->render('update', array('category'=>$category, 'method'=>'post'));
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
			$category = new Category($_POST);
			$category->id = $params['id'];
			
			try{
				$result = $category->update();
	        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
			}catch(PDOException $e) {
				$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
			}catch (ValidationException $e) {
				$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
			}
		}

		// add id to body tag
		$this->bodyId = "create";
		$this->bodyClass = "form-page";
		// add script for the registration script 
		$this->scripts[] = "register-form.js";

		$category = new Category();
		if(isset($params['id'])) {
			try {
				$category = $category->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->render('update', array('category'=>$category, 'method'=>'put'));
	}
}