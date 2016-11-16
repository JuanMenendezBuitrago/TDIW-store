<?php
require_once(dirname(__FILE__).'/Model.php');

class Supplier extends Model {
	protected $_tableName = 'suppliers';

	public $id;
	public $name;
	public $phone;
	public $email;
	public $address;
	public $status;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'      => 'id',
			'name'    => 'nom',
			'phone'   => 'telèfon',
			'email'   => 'email',
			'address' => 'adreça',
			'status'  => 'estat',
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

		// validate name
		if (in_array($scenario, ['create','update']) && preg_match('/^[\s\S]{1,50}$/', $this->name)) {
			$this->_addError('name', "Ha de contenir entre 1 i 50 caracters. Es un camp obligatori.");
		}
	
		// validate phone     
		if (in_array($scenario, ['create','update']) && preg_match('/^[\d]{9}$/', $this->phone)) {
			$this->_addError('intro', "Ha de contenir entre 1 i 140 caracters. Es un camp obligatori.");
		}

		// validate email
		if (in_array($scenario, ['update', 'create']) && !preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $this->email)) {
			$this->_addError('email', "Ha de contenir una adreça de correu valid. Es un camp obligatori.");
		}
	
		// validate address
		if (in_array($scenario, ['create','update']) && preg_match('/^[\s\S]{50}$/', $this->address)) {
			$this->_addError('address', "Ha de contenir entre 1 i 50 caracters. Es un camp obligatori.");
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

	public function save() {
		if ($this->isValid()) {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "INSERT INTO {$this->_tableName} (name, phone, email, address, created, updated, status) 
		    		VALUES (:name, :phone, :email, :address, now(), now(), :status)";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':phone', $this ->phone, PDO::PARAM_STR); 
		    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR); 
		    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->execute();

			if($stmt->rowCount()==0) {
		    	return null;
		    }

		    $this->id = $this->_pdo->lastInsertId();		    
			return $this;
		}
		throw new ValidationException($this->getErrors(), "Supplier is not valid.");
	}

	public function update() {
		if ($this->isValid()) {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE {$this->_tableName} 
		    		SET name = :name, phone = :phone, email = :email, address = :address, updated = now(), status = :status
		    		WHERE id = :id";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR); 
		    $stmt->bindParam(':email', $this->email, PDO::PARAM_STR); 
		    $stmt->bindParam(':address', $this->address, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
		    $stmt->execute();
			
			if($stmt->rowCount()==0) {
		    	return null;
		    }
			return $this;	
		}
		throw new ValidationException($this->getErrors(), "Supplier is not valid.");
	}
}