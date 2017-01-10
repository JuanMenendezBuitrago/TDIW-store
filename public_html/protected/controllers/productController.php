<?php
require_once(dirname(__FILE__).'/Controller.php');
require_once(dirname(__FILE__).'/../models/Product.php');
require_once(dirname(__FILE__).'/../models/Category.php');
require_once(dirname(__FILE__).'/../models/Supplier.php');

class ProductController extends Controller {
	 
	public $menu = array(
			array('href'=>'/admin/producte/list','text'=>'llista de productes','active'),
			array('href'=>'/admin/producte/new', 'text'=>'nou producte'),
	);

	public function __construct() {
		parent::__construct('product');
	}

	private function _removeDir($dir) {
		if(is_dir($dir)){
			array_map('unlink', glob("$dir/*.*"));
			rmdir($dir);
		}
	}

	private function _getFilenameInDir($dir) {
		if(is_dir($dir)){
			$files = glob($dir.'/*'); // get all file names
			return count($files)>0?basename($files[0]):null;
		}
		return null;
	}

	/** 
	 * Show the list of products (user)
	 * @return void 
	 */
	public function actionIndex($params=null) {
		// add id to body tag
		$this->bodyId = "list";
		$this->bodyClass = "list";
		$this->scripts[] = "list.js";
		
		$ajax = false;
		if(isset($params['ajax'])){
			$ajax = true;
			unset($params['ajax']);
		}
		
		$product = new Product();
		$list = $product->findAll($params);
		if(!$ajax)
			$this->render('index', array('products'=>$list));
		else
			$this->renderPartial('_productList', array('products'=>$list));
	}

	/**
	 * Handles product's picture upload.
	 * @return void 
	 */
	public function actionUpload() {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou producte');
			$this->redirect('');
		}

		// get file info, extract the extension and compose the target filename
		$file = $_FILES['picture'];
		$targetFile = $this->getConfig('pictures_path')."/{$_POST['tempId']}/{$file['name']}";
		$errors = array();

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
		if(count($errors) > 0) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$errors)));
		}

		$targetDir = dirname($targetFile);
		if(!is_dir($targetDir)){
			mkdir($targetDir);
		}else{
			$files = glob($targetDir.'/*'); // get all file names
			foreach($files as $f){ // iterate files
				if(is_file($f))
					unlink($f); // delete file
			}
		}
		if(move_uploaded_file($file['tmp_name'], $targetFile)) {
			// send json with success message
			$result = ['filename'=>basename($targetFile), 'type'=>$file['type'], 'size'=>$file['size']];
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}
		// send response warning that the file couldn't be copied
		$this->_sendJSONResponse(200,  json_encode(array('errors'=>['copy'=>"No se ha pogut copiar la imatge al seu destí: ".$targetFile])));
	}

	/**
	 * Render the products list (admin)
	 * @param  array $params parameters
	 * @return void 
	 */
	public function actionList($params=null) {

		$product = new Product();
		$list = $product->findAll($params);

		$this->renderPartial('_list', array('products'=>$list));
	}

	/**
	 * Render product details as HTML view.
	 * @param  array $params parameters
	 * @return void         
	 */
	public function actionShow($params=null) {
		$product = new Product();
		try{
			$result = $product->findById($params['id']);
			if(isset($params['admin']))
				$this->renderPartial('_showAdmin', array('product'=>$result));
			else
				$this->renderPartial('_show', array('product'=>$result));
		} catch(PDOException $e) {

		}
	}

	//-----------------------------------------------------------------
	
	/**
	 * Delete a product
	 * @return void
	 */
	public function actionDelete($params) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per eliminar un producte');
			$this->redirect('');
		}

		$product = new Product();
		try{
			$result = $product->delete($params['id']);
			// TODO: delete picture
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
	 * @param  array $params 
	 * @return void         
	 */
	public function actionCreate($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou producte');
			$this->redirect('');
		}

		$product = new Product($_POST);
		$targetDir = $this->getConfig('pictures_path')."/{$_POST['tempId']}";
		if(is_dir($targetDir)){
			$files = glob($targetDir.'/*'); // get all file names
			foreach($files as $f){ // iterate files
				$file = $f;
			}
		}
		try{
			$product->picture = basename($file);
			$result = $product->save();
			@rename($targetDir, $this->getConfig('pictures_path')."/".$product->id);
			$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}

	/**
	 * Render the 'new product' form as HTML view.
	 * @param  array $params 
	 * @return void
	 */
	public function actionNew($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per crear un nou producte');
			$this->redirect('');
		}

		$product = new Product();
		$categories = (new Category())->findAll(['status'=>1]);
		$this->renderPartial('_form', array('product'=>$product, 'method'=>'post', 'categories'=>$categories));
	}

	//-----------------------------------------------------------------
	
	/**
	 * Receive data via POST, edit user with it and return a JSON response with the result.
	 * @param  array $params
	 * @return void
	 */
	public function actionUpdate($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per editar un producte');
			$this->redirect('');
		}

		$_POST = $this->parsePut();
		$removePicture = $_POST['remove']=='1'?true:false;
		$product = new Product($_POST);
		$product->id = $params['id'];
		$sourceDir = $this->getConfig('pictures_path')."/{$_POST['tempId']}";
		$targetDir = $this->getConfig('pictures_path')."/".$product->id;
		$product->picture = $this->_getFilenameInDir($targetDir);

		if($removePicture) {
			$product->picture = null;
			$this->_removeDir($targetDir);
			$this->_removeDir($sourceDir);
		}else{
			if(is_dir($sourceDir)){
				$product->picture = $this->_getFilenameInDir($sourceDir);
				if(is_dir($targetDir)){
					$this->_removeDir($targetDir);
				}
			}
		}


		try{
			$result = $product->update();
			@rename($sourceDir, $targetDir);
        	$this->_sendJSONResponse(200,  json_encode(array('result'=>$result)));
		}catch(PDOException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('dbError'=>$e->getMessage())));
		}catch (ValidationException $e) {
			$this->_sendJSONResponse(200,  json_encode(array('errors'=>$e->getErrors())));
		}
	}

	/**
	 * Render the 'update product' 
	 * @param  array $params 
	 * @return void
	 */
	public function actionEdit($params=null) {
		if(!$this->getUser()->isAdmin()) {
			$this->setFlash('Has de ser un administrador per editar un producte');
			$this->redirect('');
		}

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
		$this->renderPartial('_form', array('product'=>$product, 'method'=>'put', 'categories'=>$categories));
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
		
		$product = new Product();
		try {
			$products = $product->findAll(['p.status'=>1]);
		}
		catch (PDOException $e) {

		}

		$this->render('admin',array('products'=>$products));
	}
}