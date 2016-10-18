<?php
require_once(dirname(__FILE__).'/Controller.php');

class IndexController extends Controller {

	public function __construct() {
		parent::__construct('index');
	}

	public function actionIndex() {
		$this->render('index');
	}
}