<?php
require_once(dirname(__FILE__).'/Controller.php');
// require_once(dirname(__FILE__).'/../models/Order.php');

class OrderController extends Controller {

	public function __construct() {
		parent::__construct('order');
	}

	public function actionIndex() {
		$cat = new Category($this->getPDO());
		$result = $cat->findAll(array('status'=>1));
		$this->render('index',array('cat'=>$result));
	}

	public function actionUpdate($params = null) {
		// increase or decrease
		if($params !== null && array_key_exists('action', $params)) {
			if(!isset($_SESSION['cart']))
				$_SESSION['cart'] = array();

			$cart = &$_SESSION['cart'];
			if(!isset($cart[$params['product']]))
				$cart[$params['product']] = 0;

			$cart[$params['product']] = ($params['action']=="inc")?$cart[$params['product']]+1:$cart[$params['product']]-1;
			if($cart[$params['product']] < 0)
				$cart[$params['product']] = 0;

		}
		// set an amount
		elseif ($params !== null && array_key_exists('amount', $params)) {
			if(!isset($_SESSION['cart']))
				$_SESSION['cart'] = array();

			$cart[$params['product']] = $params['amount'];
		}

		$this->_sendJSONResponse(200, json_encode($cart));
	}
}