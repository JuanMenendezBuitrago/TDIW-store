<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Category.php');

class CategoryController extends Controller {

	public $menu = array(
			array('href'=>'/admin/categoria/list','text'=>'llista de categories','active'),
			array('href'=>'/admin/categoria/new', 'text'=>'nova categoria'),
	);

	public function __construct() {
		parent::__construct('category');
	}

	//-----------------------------------------------------------------
	
	/**
	 * Render the categories list (admin)
	 * @param  array $params parameters
	 * @return void 
	 */
	public function actionList($params=null) {

		$category = new Category();
		$list = $category->findAll($params);

		$this->renderPartial('_list', array('categories'=>$list));
	}

	/**
	 * Render category details as HTML view.
	 * @param  array $params parameters
	 * @return void         
	 */
	public function actionShow($params=null) {
		$category = new Category();
		try{
			$result = $category->findById($params['id']);
			$this->renderPartial('_show', array('category'=>$result));
		} catch(PDOException $e) {

		}
	}

	//-----------------------------------------------------------------
	
	/**
	 * Delete a category
	 * @return void
	 */
	public function actionDelete($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per eliminar una categoria');
			$this->redirect('');
		}

		$category = new Category();
		try{
			$result = $category->delete($params['id']);
        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}
	//-----------------------------------------------------------------
	
	/**
	 * Receive GET request or data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		if($this->getUser()->getRole() !== 'admin') {
			$this->setFlash('Has de ser un administrador per crear una nova categoria');
			$this->redirect('');
		}

		$category = new Category($_POST);
		try{
			$result = $category->save();
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}

	public function actionNew($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear una nova categoria');
			$this->redirect('');
		}

		$category = new Category();
		$this->renderPartial('_form', array('category'=>$category, 'action'=>'/categoria', 'method'=>'post'));
	}

	//-----------------------------------------------------------------

	/**
	 * Render the update form.
	 * @return void
	 */
	public function actionUpdate($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per editar una categoria');
			$this->redirect('');
		}
		
		$_POST=$this->parsePut();
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

	public function actionEdit($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per editar una categoria');
			$this->redirect('');
		}

		$category = new Category();
		if(isset($params['id'])) {
			try {
				$category = $category->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->renderPartial('_form', array('category'=>$category, 'action'=>'/categoria/'.$category->id, 'method'=>'put'));
	}

	//-----------------------------------------------------------------
	
	public function actionAdmin($params = null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear una nova categoria');
			$this->redirect('');
		}
		
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		$this->scripts[] = "update.js";
		
		$category = new Category();
		try {
			$categories = $category->findAll(['status'=>1]);	
		}
		catch (PDOException $e) {

		}

		$this->render('admin',array('categories'=>$categories));
	}
}