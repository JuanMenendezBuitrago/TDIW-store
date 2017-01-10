<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/User.php');
require_once(dirname(__FILE__).'/../models/Admin.php');
require_once(dirname(__FILE__).'/../models/Product.php');


class UserController extends Controller {

	public $menu = array(
			array('href'=>'/admin/usuari/list','text'=>'llista de usuaris','active'),
			array('href'=>'/admin/usuari/new', 'text'=>'nou usuari'),
	);

	public function __construct() {
		parent::__construct('user');
	}

	public function actionLogout() {
		$user = new UserID();
		$_SESSION['user'] = $user;
		$this->_sendJSONResponse(200,  json_encode(array('result'=>$user)));
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
	 * Render the users list (admin)
	 * @param  array $params parameters
	 * @return void 
	 */
	public function actionList($params=null) {

		$user = new User();
		$list = $user->findAll($params);

		$this->renderPartial('_list', array('users'=>$list));
	}

	/**
	 * Render user details as HTML view.
	 * @param  array $params parameters
	 * @return void         
	 */
	public function actionShow($params=null) {
		$user = new User();
		try{
			$result = $user->findById($params['id']);
			$this->renderPartial('_show', array('user'=>$result));
		} catch(PDOException $e) {

		}
	}

	//-----------------------------------------------------------------
	
	/**
	 * Delete a user
	 * @return void
	 */
	public function actionDelete($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per eliminar un usuari');
			$this->redirect('');
		}

		$user = new User();
		try{
			$result = $user->delete($params['id']);
        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}
	//-----------------------------------------------------------------
	
	/**
	 * Receive data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params = null) {
		$user = new User($_POST);
		try{
			$result = $user->save();
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}

	/**
	 * Render the 'new user' form as HTML view.
	 * @param  array $params 
	 * @return void
	 */
	public function actionNew($params=null) {

		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		$this->scripts[] = "update.js";
		$this->scripts[] = "upload.js";

		$user = new User();
		if($this->getUser()->isAdmin()){
			$this->renderPartial('_form', array('user'=>$user, 'method'=>'post'));
		}
		elseif($this->getUser()->isGuest()) {
			$this->render('new', array('user'=>$user, 'method'=>'post'));
		}
		else {
			$this->setFlash('No pots crear un nou usuari si ja estàs logueat.');
			$this->redirect('');
		}
	}

	//-----------------------------------------------------------------
	
	/**
	 * Receive data via POST, edit user with it and return a JSON response with the result.
	 * @param  array $params
	 * @return void
	 */
	public function actionUpdate($params = null) {
		if(!$this->getUser()->isAdmin()) {
			if($this->getUser()->id != $params['id']) {
				$this->setFlash('Has de ser un administrador per editar un producte');
			}
		}


		if($this->hasFlash()){
			$this->redirect('');
		}

		$_POST = $this->parsePut();
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

	/**
	 * Render the 'update user' form
	 * @param  array $params 
	 * @return void
	 */
	public function actionEdit($params=null) {

		$user = new User();
		if(isset($params['id'])) {
			try {
				$user = $user->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->renderPartial('_form', array('user'=>$user,'action'=> '/usuari/'.$user->id, 'method'=>'put'));
	}

	//-----------------------------------------------------------------
	
	public function actionAdmin($params = null) {

		if($this->getUser()->isGuest()) {
			$this->setFlash('Has de estar registrat per accedir a aquesta secció');
			$this->redirect('');
		}
		
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		$this->scripts[] = "update.js";

		$user = new User();
		if($this->getUser()->isAdmin()) {
			try {
				$users = $user->findAll();	
			}
			catch (PDOException $e) {

			}

			$this->render('admin',array('users'=>$users));
		}else{
			$this->menu = array();

			$user_id = $this->getUser()->id;
			try {
				$user = $user->findAll(['id'=>$user_id]);	
			}
			catch (PDOException $e) {

			}

			$this->render('adminSelf',array('user'=>$user[0]));
		}		
	}

	public function actionCart() {
		$this->bodyId = "register";
		$this->bodyClass = "form-page";
		$this->scripts[] = "update.js";

		$cart = (isset($_SESSION['cart']))?$_SESSION['cart']:array();
		$product= new Product();
		$list = array();
		foreach($cart as $item=>$amount) {
			$list[] = array('product'=>$product->findById($item),'amount'=>$amount);
		}
		$this->render('cart',array('list'=>$list));
	}
}