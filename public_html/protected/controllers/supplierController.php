<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Supplier.php');

class SupplierController extends Controller {
	public $menu = array(
			array('href'=>'','text'=>'llista de proveidors','active'),
			array('href'=>'', 'text'=>'nou proveidor'),
	);

	public function __construct() {
		parent::__construct('supplier');
	}

	/**
	 * Receive GET request or data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		if($this->getUser()->getRole() !== 'admin') {
			$this->setFlash('Has de ser un administrador per crear un nou producte');
			$this->redirect('');
		}

		switch($GLOBALS['request_method']){
			case 'POST':
				$supplier = new Supplier($_POST);
				try{
					$result = $supplier->save();
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
				
				$supplier = new Supplier();
				if(isset($params['id'])) {
					try {
						$supplier = $supplier->findById($params['id']);	
					}
					catch (PDOException $e) {

					}
				}
				$this->render('update', array('supplier'=>$supplier, 'method'=>'post'));
				break;

		}
		
	}

	/**
	 * Render the update form.
	 * @return void
	 */
	public function actionUpdate($params) {
		if($this->getUser()->getRole() !== 'admin') {
			$this->setFlash('Has de ser un administrador per crear un nou producte');
			$this->redirect('');
		}
		
		// deal with REST request
		if($GLOBALS['request_method']=='PUT'){
			parse_str(file_get_contents("php://input"),$_POST);
			$supplier = new Supplier($_POST);
			$supplier->id = $params['id'];
			
			try{
				$result = $supplier->update();
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

		$supplier = new Supplier();
		if(isset($params['id'])) {
			try {
				$supplier = $supplier->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->render('update', array('supplier'=>$supplier, 'method'=>'put'));
	}

	public function actionAdmin($params = null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou proveidor');
			$this->redirect('');
		}
		
		// add id to body tag
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		// add script for the registration script 
		$this->scripts[] = "update.js";

		$this->menu = array(
				array('href'=>'#','text'=>'llista de proveidors','active'),
				array('href'=>'#','text'=>'nou proveidor'),
		);
		
		$supplier = new Supplier();
		try {
			$suppliers = $supplier->findAll(['status'=>1]);	
		}
		catch (PDOException $e) {

		}

		$this->render('admin',array('suppliers'=>$suppliers));
	}
}