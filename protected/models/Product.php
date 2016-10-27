<?php
require_once(dirname(__FILE__).'/Model.php');

class User extends Model {
	private $_table_name = 'users';

	public $id;
	public $category_id;
	public $supplier_id;
	public $name;
	public $intro;
	public $desription;
	public $price;
	public $stock;
	public $picture;
	public $status;

	public $errors = [];
	
	public function __construct($pdo, $attributes=null){
		$this->_pdo = $pdo;
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'          => 'id',
			'category_id' => 'categoria',
			'supplier_id' => 'proveidor',
			'name'        => 'nom',
			'intro'       => 'introducció',
			'description' => 'descripció',
			'price'       => 'preu',
			'stock'       => 'stock',
			'picture'     => 'imatge',
			'status'      => 'estat',
		];
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

		// validate status
		if (!preg_match("/[0-9]/", $this->status))  {
			$this->errors['status'] = "Sencer entre 0 y 9.";
		}	
		
		$this->_validated = true;
	}

	public function save() {
		if ($this->isValid()) {
			try {
			    // set the PDO error mode to exception
			    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "INSERT INTO products (fk_category_id, fk_supplier_id, name, intro, description, price, stock, picture, created, updated, status) 
			    		VALUES (:fk_category_id, :fk_supplier_id, :name, :intro, :description, :price, :stock, :picture, now(), now(), :status)";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':fk_category_id', $this->fk_category_id, PDO::PARAM_STR); 
			    $stmt->bindParam(':fk_supplier_id', $this->fk_supplier_id, PDO::PARAM_STR); 
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':intro', $this ->intro, PDO::PARAM_STR); 
			    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
			    $stmt->bindParam(':price', $this->price, PDO::PARAM_STR); 
			    $stmt->bindParam(':stock', $this->stock, PDO::PARAM_STR); 
			    $stmt->bindParam(':picture', $this->picture, PDO::PARAM_STR); 
			    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
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
			    		SET fk_category_id = :fk_category_id, fk_supplier_id = :fk_supplier_id, name = :name, intro = :intro, description = :description, price = :price, stock = :stock, picture =:picture, updated = now(), status = :status
			    		WHERE id = :id";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':fk_category_id', $this->fk_category_id, PDO::PARAM_STR); 
			    $stmt->bindParam(':fk_supplier_id', $this->fk_supplier_id, PDO::PARAM_STR); 
			    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR); 
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':intro', $this->intro, PDO::PARAM_STR); 
			    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
			    $stmt->bindParam(':price', $this->price, PDO::PARAM_STR); 
			    $stmt->bindParam(':stock', $this->stock, PDO::PARAM_STR); 
			    $stmt->bindParam(':picture', $this->picture, PDO::PARAM_STR); 
			    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
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