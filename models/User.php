<?php
require_once(dirname(__FILE__).'/Model.php');

class User extends Model {
	private $_table_name = 'users';

	public $id;
	public $username;
	public $name;
	public $email;
	public $password;
	public $phone;
	public $address;
	public $city;
	public $zip;
	public $cc;

	public $errors = [];

	public function attributesLabels() {
		return [
			'id' => 'id',
			'username' => 'nom de usuari',
			'name' => 'nom i cognoms',
			'email' => 'email',
			'password' => 'contrasenya',
			'phone' => 'telèfon',
			'address' => 'adreça',
			'city' => 'població',
			'zip' => 'codi postal',
			'cc' => 'targeta de crèdit',
		];
	}

	public function isValid($field = null) {
		if(!$this->_validated){
			$this->validate();
		}

		if ($field == null) {
			return $this->_validated && (sizeof($this->errors) == 0);
		}

		return $this->_validated && (sizeof($this->errors[$field]) == 0);
	}

	private function _validate() {
		// validate username
		if (!preg_match("/((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D){1,128}/", $this->username)) {
			$this->errors['username'] = "Camp alfanumèric. Màxim 128 caracters. Es un camp obligatori.";
		}

		// validate name
		if (!preg_match("/([^\s]){1,32}/", $this->name))  {
			$this->errors['name'] = "Només caracters i espais. Es un camp obligatori.";
		}

		// validate email
		if (!preg_match("/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD", $this->email)) {
			$this->errors['email'] = "Ha de contenir una adreça de correu valid. Es un camp obligatori.";
		}

		// validate password
		if (!preg_match("/.{8,32}/", $this->password)) {
			$this->errors['password'] = "Camp alfanumèric entre 8 y 32 caracters. Es un camp obligatori.";
		}
		
		// validate phone
		if (!preg_match("/\d{9}/", $this->phone)) {
			$this->errors['phone'] = "Ha de contenir 9 dígits. Es un camp obligatori.";
		}

		// validate address
		if (!preg_match("/.{1,30}/", $this->address)) {
			$this->errors['address'] = "Pot contenir fins a 30 caràcters alfanumèrics. Es un camp obligatori.";
		}

		// validate city
		if (!preg_match("/((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D){1,30}/", $this->city)) {
			$this->errors['city'] = "Pot conteni fins a 30 caràcters i espais. Es un camp obligatori.";
		}

		// validate zip
		if (!preg_match("/\d{5}/", $this->zip)) {
			$this->errors['zip'] = "Ha de contenir 5 dígits. Es un camp obligatori.";
		}

		// validate cc
		if (!preg_match("/\d{16}/", $this->cc)) {
			$this->errors['cc'] = "Ha de contenir 16 dígits. Es un camp obligatori.";
		}
		
		$this->_validated = true;
	}

	public function save() {
		if ($this->isValid()) {
			try {
			    // set the PDO error mode to exception
			    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "INSERT INTO Users (username, name, email, password, phone, address, city, zip,cc) 
			    		VALUES (:username, :name, :email, :password, :phone, :address, :city, :zip, :cc)";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR); 
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':email', $this ->email, PDO::PARAM_STR); 
			    $stmt->bindParam(':password', $this->password, PDO::PARAM_STR); 
			    $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR); 
			    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
			    $stmt->bindParam(':city', $this->city, PDO::PARAM_STR); 
			    $stmt->bindParam(':zip', $this->zip, PDO::PARAM_STR); 
			    $stmt->bindParam(':cc', $this->cc, PDO::PARAM_STR); 
			    $stmt->exec();
			    echo "New record created successfully";
			} catch(PDOException $e) {
			    echo $sql . "<br>" . $e->getMessage();
			    }

		}
		return true;
	}

	public function update() {
		if ($this->isValid()) {
			try {
			    // set the PDO error mode to exception
			    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "UPDATE Users 
			    		SET username = :username, name = :name, email = :email, password = :password, phone = :phone, address = :address, city =:city, zip = :zip, cc = :cc
			    		WHERE id = :id";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR); 
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR); 
			    $stmt->bindParam(':password', $this->password, PDO::PARAM_STR); 
			    $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR); 
			    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
			    $stmt->bindParam(':city', $this->city, PDO::PARAM_STR); 
			    $stmt->bindParam(':zip', $this->zip, PDO::PARAM_STR); 
			    $stmt->bindParam(':cc', $this->cc, PDO::PARAM_STR); 
			    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
			    $stmt->exec();
			    echo "New record created successfully";
			} catch(PDOException $e) {
			    echo $sql . "<br>" . $e->getMessage();
			    }

		}
		return true;
	}
}