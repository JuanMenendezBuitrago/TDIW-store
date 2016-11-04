<?php
require_once(dirname(__FILE__).'/Model.php');

class Category extends Model {
	protected $_tableName = 'categories';

	public $id;
	public $name;
	public $description;
	public $status;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'          => 'id',
			'name'    => 'nom de categoria',
			'description' => 'descripció',
			'status'      => 'estat',
		];
	}

	protected function _validate($scenario) {
		// validate name
		if (!preg_match("/((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D){1,32}/", $this->username)) {
			$this->_addError('name', "Camp alfanumèric. Màxim 32 caracters. Es un camp obligatori.");
		}

		// validate description
		if (!preg_match("/[\s\S]{0,128}/", $this->description))  {
			$this->_addError('description', "Camp de text de 128 caracters màxim.");
		}

		// validate status
		if (!preg_match("/[0-9]/", $this->status))  {
			$this->_addError('status', "Sencer entre 0 y 9.");
		}	
		
		$this->_validated = true;
	}

	public function save() {
		if ($this->isValid()) {
			try {
			    // set the PDO error mode to exception
			    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "INSERT INTO Categories (name, description, created, updated, status) 
			    		VALUES (:name, description, now(), now(), :status)";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':description', $this ->description, PDO::PARAM_STR); 
			    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
			    $stmt->execute();
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
			    		SET name = :name, description = :description, status = :status, updated = now()
			    		WHERE id = :id";

			    $stmt = $this->_pdo->prepare($sql);
			    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
			    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
			    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
			    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
			    $stmt->execute();
			    echo "Record updated successfully";
			} catch(PDOException $e) {
			    echo $sql . "<br>" . $e->getMessage();
			    }

		}
		return true;
	}
}