<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Admin.php');

class AdminController extends Controller {
	
	public $menu = array(
			array('href'=>'/admin/admin/list','text'=>'llista de adminsitradors','active'),
			array('href'=>'/admin/admin/new', 'text'=>'nou adminsitradors'),
	);

	public function __construct() {
		parent::__construct('admin');
	}

	/**
	 * Render the amins list (admin)
	 * @param  array $params parameters
	 * @return void 
	 */
	public function actionList($params=null) {

		$admin = new Admin();
		$list = $admin->findAll($params);

		$this->renderPartial('_list', array('admins'=>$list));
	}

	/**
	 * Render admin details as HTML view.
	 * @param  array $params parameters
	 * @return void         
	 */
	public function actionShow($params=null) {
		$admin = new Admin();
		try{
			$result = $admin->findById($params['id']);
			$this->renderPartial('_show', array('admin'=>$result));
		} catch(PDOException $e) {

		}
	}
	//-----------------------------------------------------------------

	/**
	 * Receive data via POST, create a new admin with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou administrador');
			$this->redirect('');
		}

		$admin = new Admin($_POST);
		try{
			$result = $admin->save();		        	
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}
	
	public function actionNew($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou administrador');
			$this->redirect('');
		}

		$admin = new Admin(); // empty admin
		$this->renderPartial('_form', array('admin'=>$admin, 'action'=>'/admin', 'method'=>'post'));
	}

	//-----------------------------------------------------------------
	
	/**
	 * Delete an admin
	 * @return void
	 */
	public function actionDelete($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per eliminar un administrador');
			$this->redirect('');
		}

		$admin = new Admin();
		try{
			$result = $admin->delete($params['id']);
        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}
	//-----------------------------------------------------------------
	
	/**
	 * Render the update form.
	 * @return void
	 */
	public function actionUpdate($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per modificar un administrador');
			$this->redirect('');
		}

		//parse_str(file_get_contents("php://input"),$_POST);
		$_POST = $this->parsePut();
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

	public function actionEdit($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per editar un adminsitrador');
			$this->redirect('');
		}

		$admin = new Admin();
		if(isset($params['id'])) {
			try {
				$admin = $admin->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->renderPartial('_form', array('admin'=>$admin, 'action'=>'/admin/'.$admin->id, 'method'=>'put'));
	}

	//-----------------------------------------------------------------

	/**
	 * Render the admin page.
	 * @param  arry $params parameters.
	 * @return void         
	 */
	public function actionAdmin($params = null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per accedir a aquesta secció');
			$this->redirect('');
		}
		
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		$this->scripts[] = "update.js";
		
		$admin = new Admin();
		try {
			$admins = $admin->findAll(['status'=>1]);
		}
		catch (PDOException $e) {

		}

		$this->render('admin',array('admins'=>$admins));
	}	
}