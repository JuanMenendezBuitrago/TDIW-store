<?php
require_once(dirname(__FILE__).'/Model.php');

class Login extends Model {
	protected $_tableName;

	public $username;
	public $password;
	public $encrypted_password;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'username'  => 'nom de usuari',
			'password'  => 'contrasenya',
		];
	}

	protected function _validate($scenario) {

		// validate username: only letters and digits
		if (in_array($scenario, ['login']) && !preg_match('/^[\w\d_]{1,20}$/', $this->username)) {
			$this->_addError('username', "Camp alfanumèric. Màxim 128 lletres sense accents, nombres y guió baix. Es un camp obligatori.");
		}

		// validate password
		if (in_array($scenario, ['login']) && !preg_match('/^[\s\S]{8,32}$/', $this->password)) {
			$this->_addError('password', "Camp alfanumèric entre 8 y 32 caracters. Es un camp obligatori.");
		}
		
		$this->_validated = true;
	}



}