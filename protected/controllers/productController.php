<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Product.php');
require_once(dirname(__FILE__).'/../models/Category.php');
require_once(dirname(__FILE__).'/../models/Supplier.php');

class ProductController extends Controller {

	public function __construct() {
		parent::__construct('product');
	}

	/** 
	 * Show teh list of products
	 * @return void 
	 */
	public function actionIndex($params=null) {
		// add id to body tag
		$this->bodyId = "list";
		$this->bodyClass = "list";

		$this->scripts[] = "list.js";

		$product = new Product();
		$list = $product->findAll($params);
		$this->render('index', array('products'=>$list));
	}

	/**
	 * Handles product's picture upload.
	 * @return void 
	 */
	public function actionUpload() {
		// get file info, extract the extension and compose the target filename
		$file = $_FILES['picture'];
		$fileExtension = substr($file['name'], 1+strrpos($file['name'],'.'));
		$targetFile = $this->getConfig('pictures_path')."/temp-product-".$_POST['tempId'].".$fileExtension";
		$errors = [];

		// validate file
		if(!in_array($file['type'], ['image/jpeg','image/png','image/gif'])) {
			$errors['picture'][] = "La imatge no és JPG, PNG o GIF.";
		}
		if($file['size'] > $this->getConfig('max_file_size')) {
			$errors['picture'][] = "La imatge no pot tenir mès de ".$this->getConfig('max_file_size')." bytes";
		}
		if (!is_uploaded_file($file['tmp_name'])) {
			$errors['picture'][] = "Hack attack!";
		}

		// if there are errors, send response (and exit)
		if(count($errors > 0)) {	
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$errors)));
		}

		// save file with name 'temp-product-<id>.(jpg|png|gif)'
		if(move_uploaded_file($file['tmp_name'], $targetFile)) {
			// send json with success message
			$result = ['filename'=>basename($targetFile), 'type'=>$file['type'], 'size'=>$file['size']];
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}
		// send response warning that the file couldn't be copied
		$this->_sendJSONResponse(200,  json_encode(array('errors'=>['copy'=>"No se ha pogut copiar la imatge al seu destí: ".$targetFile])));
	}

	/**
	 * Get the products as a JSON list
	 * @return void 
	 */
	public function actionList() {
	}

	/**
	 * Receive data via POST, create a new user with it and return a JSON response with the result.
	 * @param  array $params Data received via POST
	 * @return void         
	 */
	public function actionCreate($params) {
		switch($GLOBALS['request_method']){
			// if it's a POST request, process it
			case 'POST':
				$product = new Product($_POST);
				try{
					$result = $product->save();
					$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
				}catch(PDOException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
				}catch (ValidationException $e) {
					$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
				}
			// if it's a GET request, show the form
			case 'GET':
			default:
				// add id to body tag
				$this->bodyId = "register";
				$this->bodyClass = "form-page";
				// add script for the registration script 
				$this->scripts[] = "register-form.js";

				$product = new Product();
				$categories = (new Category())->findAll(['status'=>1]);
				$suppliers = (new Supplier())->findAll(['status'=>1]);
				$this->render('update', array('product'=>$product, 'tempId'=>uniqid(), 'method'=>'post', 'categories'=>$categories, 'suppliers'=>$suppliers));
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
			$product = new Product($_POST);
			$product->id = $params['id'];
			
			try{
				$result = $product->update();
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

		$product = new Product();
		$categories = (new Category())->findAll(['status'=>1]);
		$suppliers = (new Supplier())->findAll(['status'=>1]);
		if(isset($params['id'])) {
			try {
				$product = $product->findById($params['id']);	
			}
			catch (PDOException $e) {

			}
		}
		$this->render('update', array('product'=>$product, 'tempId'=>uniqid(), 'method'=>'put', 'categories'=>$categories,'suppliers'=>[]));
	}
}