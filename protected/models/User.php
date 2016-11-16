<?php
require_once(dirname(__FILE__).'/Model.php');
require_once(dirname(__FILE__).'/../userID.php');

class User extends Model {
	protected $_tableName = 'users';

	public $id;
	public $username;
	public $name;
	public $email;
	public $password;
	public $password2;
	public $encrypted_password;
	public $phone;
	public $address;
	public $city;
	public $zip;
	public $card;
	public $status;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'        => 'id',
			'username'  => 'nom de usuari',
			'name'      => 'nom i cognoms',
			'email'     => 'email',
			'password'  => 'contrasenya',
			'password2' => 'confirmació de contrasenya',
			'phone'     => 'telèfon',
			'address'   => 'adreça',
			'city'      => 'població',
			'zip'       => 'codi postal',
			'card'      => 'targeta de crèdit',
			'status'    => 'status',

		];
	}

	protected function _validate($scenario) {
		// validate id: the value must exist in the database
		if (in_array($scenario, ['update']) && preg_match('/^\d+$/', $this->id)) {
			$inUse = $this->_isInUse('id', $this->id);
            if(!$inUse)
                $this->_addError('id', "No existeix un proveidor registrat amb aquest id.");
            elseif($inUse === false)
                $this->_addError('id', "Error comprobando id.");
		}

		// validate name: only spaces and letters
		if (in_array($scenario, ['update', 'create']) && !preg_match('/^[\s\p{L}]{1,50}$/u', $this->name))  {
			$this->_addError('name', "Només lletres i espais. Es un camp obligatori.");
		}
		
		// validate username: only letters and digits
		if (in_array($scenario, ['update', 'create', 'login']) && !preg_match('/^[\w\d_]{1,20}$/', $this->username)) {
			$this->_addError('username', "Camp alfanumèric. Màxim 128 lletres sense accents, nombres y guió baix. Es un camp obligatori.");
		}

        if (in_array($scenario, ['create'])) {
            $inUse = $this->_isInUse('username', $this->username);
            if($inUse)
                $this->_addError('username', "Ja existeix un usuari registrat amb aquest username.");
            elseif($inUse === false)
                $this->_addError('username', "Error comprobando username.");
        }


		// validate password
		if (in_array($scenario, ['create']) && !preg_match('/^[\s\S]{8,32}$/', $this->password)) {
			$this->_addError('password', "Camp alfanumèric entre 8 y 32 caracters. Es un camp obligatori.");
		}

		if (in_array($scenario, ['create','update']) && $this->password != $this->password2) {
			$this->_addError('password2', "Las contrasenyas no coincideixen. Es un camp obligatori.");
		}
		
		if (in_array($scenario, ['update']) && $this->password != '' && $this->password == $this->password2 && !preg_match("/^[\s\S]{8,32}$/", $this->password)) {
			$this->_addError('password', "Camp alfanumèric entre 8 y 32 caracters. Es un camp obligatori.");
		}

		// validate email
		if (in_array($scenario, ['update', 'create']) && !preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $this->email)) {
			$this->_addError('email', "Ha de contenir una adreça de correu valid. Es un camp obligatori.");
		}
		
		if (in_array($scenario, ['create'])) {
            $inUse = $this->_isInUse('email', $this->email);
            if($inUse === true)
			    $this->_addError('email', "Ja existeix un usuari registrat amb aquest email.");
            elseif($inUse === false)
                $this->_addError('email', "Error comprobando email.");
		}

		// validate phone: only 9 digits
		if (in_array($scenario, ['create', 'update']) && !preg_match('/^\d{9}$/', $this->phone)) {
			$this->_addError('phone', "Ha de contenir 9 dígits. Es un camp obligatori.");
		}

		// validate address: up to 30 alphanumeric characters
		if (in_array($scenario, ['create', 'update']) && !preg_match('/^[\s\S]{1,30}$/u', $this->address)) {
			$this->_addError('address', "Pot contenir fins a 30 nombres, lletres, espais i els símbols [,.-#()] Es un camp obligatori.");
		}

		// validate city: up to 30 alphanumeric characters
		if (in_array($scenario, ['create', 'update']) && !preg_match('/^[\s\d\p{L}]{1,50}$/u', $this->city)) {
			$this->_addError('city', "Pot conteni fins a 30 lletres, nombres, espais i else símbols [,.]. Es un camp obligatori.");
		}

		// validate zip
		if (in_array($scenario, ['create', 'update']) && !preg_match('/^\d{5}$/', $this->zip)) {
			$this->_addError('zip', "Ha de contenir 5 dígits. Es un camp obligatori.");
		}

		// validate card
		if (in_array($scenario, ['create', 'update']) && !preg_match('/^\d{16}$/', $this->card)) {
			$this->_addError('card', "Ha de contenir 16 dígits. Es un camp obligatori.");
		}

		// validate status 
		if(!isset($this->status)) $this->status = '1';

		// (admin only)
		// if($_SESSION['user']->isAdmin){
		if(false){
			if (in_array($scenario, ['update','create']) && !preg_match("/[0-9]/", $this->status))  {
				$this->_addError('status', "Sencer entre 0 y 9.");
			}	
		}
		
		$this->_validated = true;
	}

	public function authenticate() {
		if ($this->isValid('login')) {
			$user = $this->findAll(['username' => $this->username]);
			if($user == null)
				return false;
			else {
				$password = $this->password;
				$hash = $user[0]->encrypted_password;
				$verify = password_verify($password, $hash);
				if ($verify){
					$id = $user[0]->id;
					$username = $user[0]->username;
					$isAdmin = false;
					$userId = new UserID($id, $username, $isAdmin);
					return $userId;
				}
			}
			return false;
		} else {
			throw new ValidationException($this->getErrors(), "User is not valid.");
		}
	}

	public function save() {
		if ($this->isValid('create')) {
			$this->encrypted_password = password_hash($this->password, PASSWORD_BCRYPT);

		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "INSERT INTO {$this->_tableName} (username, name, email, encrypted_password, phone, address, city, zip, card, created, updated, status)
		    		VALUES (:username, :name, :email, :password, :phone, :address, :city, :zip, :card, now(), now(), :status)";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR); 
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':email', $this ->email, PDO::PARAM_STR); 
		    $stmt->bindParam(':password', $this->encrypted_password, PDO::PARAM_STR); 
		    $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR); 
		    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
		    $stmt->bindParam(':city', $this->city, PDO::PARAM_STR); 
		    $stmt->bindParam(':zip', $this->zip, PDO::PARAM_STR); 
		    $stmt->bindParam(':card', $this->card, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->execute();

		    if($stmt->rowCount()==0) {
		    	return null;
		    }

		    $this->id = $this->_pdo->lastInsertId();		  
		    return $this;
		}
		throw new ValidationException($this->getErrors(), "User is not valid.");
	}

	public function update() {
		if ($this->isValid('update')) {
			if($this->password != '') {
				$this->encrypted_password = password_hash($this->password, PASSWORD_BCRYPT);

			}

		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE {$this->_tableName}
		    		SET username = :username, name = :name, email = :email, encrypted_password = :password, phone = :phone, address = :address, city =:city, zip = :zip, card = :card, updated = now() , status = :status
		    		WHERE id = :id";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR); 
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR); 
		    $stmt->bindParam(':password', $this->encrypted_password, PDO::PARAM_STR); 
		    $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR); 
		    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
		    $stmt->bindParam(':city', $this->city, PDO::PARAM_STR); 
		    $stmt->bindParam(':zip', $this->zip, PDO::PARAM_STR); 
		    $stmt->bindParam(':card', $this->card, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
		    $stmt->execute();

		    if($stmt->rowCount()==0) {
		    	return null;
		    }
	    	return $this;
		}
		throw new ValidationException($this->getErrors(), "User is not valid.");
	}
}