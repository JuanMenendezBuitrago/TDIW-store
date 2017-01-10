<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Order.php');

class OrderController extends Controller {
	public $menu = array(
			array('href'=>'/admin/comanda/list','text'=>'llista de comandes','active'),
	);

	public function __construct() {
		parent::__construct('order');
	}

	public function actionIndex() {
		$cat = new Category($this->getPDO());
		$result = $cat->findAll(array('status'=>1));
		$this->render('index',array('cat'=>$result));
	}

	public function actionCreate($params=null) {
		$user_id = $this->getUser()->id;
		// $order = new Order(array('user_id'=>$user_id,'code'=>'aaa','status'=>'1'));
		$order = new Order(array('user_id'=>$user_id,'code'=>($user_id.time()),'status'=>'1'));
		try{
			$result = $order->save();
			$_SESSION['cart'] = array();
			$this->renderPartial('_result',  array('header'=>'Tot bè', 'body'=>'La comanda se ha processat correctament'));
		}catch(PDOException $e) {
			$this->renderPartial('_result',  array('header'=>'Vaya!', 'body'=>'Ha passat algo'));
		}
	}

	public function actionUpdate($params = null) {
		// increase or decrease
		if($params !== null && array_key_exists('action', $params)) {
			if(!isset($_SESSION['cart']))
				$_SESSION['cart'] = array();

			$cart = &$_SESSION['cart'];


			switch($params['action']){
				case "inc":
					if(!isset($cart[$params['product']]))
						$cart[$params['product']] = 0;
					$cart[$params['product']]+=1;
					break;
				case "dec":
					$cart[$params['product']]-=1;
					if($cart[$params['product']] < 0)
						unset($cart[$params['product']]);
					break;
				case "buida":
					$cart = array();
				default:
			}

		}
		// set an amount
		elseif ($params !== null && array_key_exists('amount', $params)) {
			if(!isset($_SESSION['cart']))
				$_SESSION['cart'] = array();

			$cart[$params['product']] = $params['amount'];
		}

		$this->_sendJSONResponse(200, json_encode($cart));
	}

	public function actionCart() {
		if(!isset($_SESSION['cart']))
			$_SESSION['cart'] = array();

		$json = json_encode($_SESSION['cart']);
		$this->_sendJSONResponse(200, $json);
	}

	//-----------------------------------------------------------------
	
	public function actionShow($params=null) {
		$list = (new Order())->findProductsInOrder($params['id']);
		$this->renderPartial('_show', array('list'=>$list));
	}

	public function actionList($params=null) {
		if(!$this->getUser()->isAdmin() && (isset($params['user_id']) && $this->getUser()->id == $params['user_id'])) {
			$this->setFlash('No pots administrar les comandes');
			$this->redirect('');
		}
		$order = new Order();
		$list = $order->findAll($params);

		$this->renderPartial('_list', array('orders'=>$list));
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

		$order = new Order();
		if($this->getUser()->isAdmin()) {
			try {
				$orders = $order->findAll();
				}
			catch (PDOException $e) {

			}

			$this->render('admin',array('orders'=>$orders));
		}else{
			$this->menu = array();

			$user_id = $this->getUser()->id;
			try {
				$orders = $order->findAll(['user_id'=>$user_id]);	
			}
			catch (PDOException $e) {

			}

			$this->render('adminSelf',array('orders'=>$orders));
		}		
	}	

	//--------------------------------------------------------------------------------
	
	/**
	 * Delete a product
	 * @return void
	 */
	public function actionDelete($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per eliminar una comanda');
			$this->redirect('');
		}

		$order = new Order();
		try{
			$result = $order->delete($params['id']);
			// TODO: delete picture
        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}
}