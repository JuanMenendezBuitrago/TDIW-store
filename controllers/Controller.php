<?php

abstract class Controller {

	public $layout;
	public $bodyId='';
	public $scripts=array();
	public $styles=array();
	public $menu=array();
	public $breadcrumbs=array();

	private $_id;
	private $_pdo = null;
	private $_pageTitle;
	private $_config;


	public function __construct($id) {
		$this->_id = $id;
		$this->bodyId = $id;
		$this->_config = require(dirname(__FILE__)."/../config/main.php");
		$this->_setPDO();
	}

	private function _setPDO() {
		$this->_pdo = new PDO(sprintf('mysql:host=%s;dbname=%s',$this->_config['db']['host'], $this->_config['db']['dbname']), $this->_config['db']['dbuser'], $this->_config['db']['password']);
	}

	public function getPDO() {
		return $this->_pdo;
	}

	public function getScripts(){
		$result = '';
		foreach ($this->scripts as $src) {
			$result .="\t<script type=\"text/javascript\" src=\"".$this->_config['base_url']."/js/".$src."\"></script>\n\r";
		}
		return $result;
	}

	public function getStyles(){
		$result = '';
		foreach ($this->styles as $src) {
        	$result .="\t<link rel=\"stylesheet\" href=\"".$this->_config['base_url']."/css/".$src."\">\n\r";
		}
		return $result;
	}

	public function getId() {
		return $this->_id;
	}

	public function getPageTitle() {
		if($this->_pageTitle!==null) {
			return $this->_pageTitle = $this->_pageTitle;
		} 
		else {
			return ucfirst($this->getId());
		}
	}

	public function setPageTitle($value) {
		$this->_pageTitle=$value;
	}

	/******************************************************
	 Render
	 ******************************************************/

	public function render($view,$data=null,$return=false){
		// render the view and store it in $output so it can be inserted into the layout
		$output=$this->renderPartial($view,$data,true);

		// render the layout with the content
		if(($layoutFile=$this->getLayoutFile($this->layout)) !== false){
			$output=$this->renderFile($layoutFile,array('content'=>$output),true);
		}

		// echo result or return it
		if($return){
			return $output;
		}else{
			echo $output;
		}
	}

	/**
	 * Renders a view.
	 *
	 * The named view refers to a PHP script (resolved via getViewFile())
	 * that is included by this method. If $data is an associative array,
	 * it will be extracted as PHP variables and made available to the script.
	 *
	 * This method does not apply a layout to the rendered result. It is thus mostly used
	 * in rendering a partial view, or an AJAX response.
	 *
	 * @param string $view name of the view to be rendered.
	 * @param array $data data to be extracted into PHP variables and made available to the view script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users
	 * @return string the rendering result. Null if the rendering result is not required.
	 */
	public function renderPartial($view,$data=null,$return=false){
		// if view file exists, render it with the given data 
		if(($viewFile = $this->getViewFile($view)) !== false){
			$output=$this->renderFile($viewFile,$data,true);

			// echo it or return it
			if($return){
				return $output;
			}else{
				echo $output;
			}
		}
		// if view file is not found, throw exception.
		else{
			throw new Exception(get_class($this)." cannot find the requested view $view.");
		}
	}

	/**
	 * Renders a static text string.
	 * The string will be inserted in the current controller layout and returned back.
	 * @param string $text the static text string
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see getLayoutFile
	 */
	public function renderText($text,$return=false) {
		if(($layoutFile=$this->getLayoutFile($this->layout))!==false)
			$text=$this->renderFile($layoutFile,array('content'=>$text),true);
		if($return)
			return $text;
		else
			echo $text;
	}

	/**
	 * Looks for the view file according to the given view name.
	 *
	 * Views will be searched for under the currently active
	 * controller's view path.
	 *
	 * @param string $viewName view name
	 * @return string the view file path, false if the view file does not exist
	 */
	public function getViewFile($viewName) {
		return $this->resolveViewFile($viewName,$this->getViewPath());
	}

	/**
	 * Returns the directory containing view files for this controller.
	 * The default implementation returns 'protected/views/ControllerID'.
	 * @return string the directory containing the view files for this controller. Defaults to 'protected/views/ControllerID'.
	 */
	public function getViewPath(){
		return $this->_config['view_path'].DIRECTORY_SEPARATOR.$this->getId();
	}

	/**
	 * Finds a view file based on its name.
	 * Views will be searched for under the currently active
	 * controller's view path.
	 * The corresponding view file is a PHP file whose name is the same as the view name. 
	 * The file is located under a specified directory.
	 * @param string $viewName the view name
	 * @param string $viewPath the directory that is used to search for a relative view name
	 * @return mixed the view file path. False if the view file does not exist.
	 */
	public function resolveViewFile($viewName,$viewPath){
		// view name cannot be empty
		if(empty($viewName)){
			return false;
		}

		// compose full path to view file
		$viewFile = $viewPath.DIRECTORY_SEPARATOR.$viewName.'.php';
		// if it exists, return full path
		if(is_file($viewFile)){
			return $viewFile;
		}else{
			return false;
		}
	}

	/**
	 * Renders a view file.
	 *
	 * @param string $viewFile view file path
	 * @param array $data data to be extracted and made available to the view
	 * @param boolean $return whether the rendering result should be returned instead of being echoed
	 * @return string the rendering result. Null if the rendering result is not required.
	 */
	public function renderFile($viewFile,$data=null,$return=false){ 
		$content = $this->renderInternal($viewFile,$data,$return);

		return $content;
	}

	/**
	 * Renders a view file.
	 * This method includes the view file as a PHP script
	 * and captures the display result if required.
	 * @param string $_viewFile_ view file
	 * @param array $_data_ data to be extracted and made available to the view file
	 * @param boolean $_return_ whether the rendering result should be returned as a string
	 * @return string the rendering result. Null if the rendering result is not required.
	 */
	public function renderInternal($_viewFile_,$_data_=null,$_return_=false){
		// turn array key-value pairs into variable name-value pairs
		// undersores are added to prevent name conflicts
		if(is_array($_data_)){
			extract($_data_,EXTR_PREFIX_SAME,'data');
		}
		// if it's not an array, just use the variable as it is
		else{
			$data=$_data_;
		}
		// if output has to be captured, use ob_start/ob_get_clean and return the result
		if($_return_){
			ob_start();
			ob_implicit_flush(false);
			require($_viewFile_);
			return ob_get_clean();
		}
		// otherwise just require the view file
		else{
			require($_viewFile_);
		}
	}

	/**
	 * Looks for the layout view script based on the layout name.
	 *
	 * The layout name can be specified in one of the following ways:
	 * - layout is false: returns false, meaning no layout.
	 * - layout is null: the application's layout will be used.
	 * - a regular view name.
	 *
	 * The resolution of the view file based on the layout view is similar to that in getViewFile.
	 *
	 * @param mixed $layoutName layout name
	 * @return string the view file for the layout. False if the view file cannot be found
	 */
	public function getLayoutFile($layoutName) {

		// application layout
		if(empty($layoutName)){
			$layoutName = $this->_config['layout'];
		}

		return $this->resolveViewFile($layoutName, $this->_config['layout_path']);
	}
}