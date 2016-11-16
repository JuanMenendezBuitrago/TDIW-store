<?php
require_once(dirname(__FILE__).'/Model.php');

class Category extends Model {
	protected $_tableName = 'categories';

	public $id;
	public $name;
	public $alias;
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
			'alias'    => 'alias',
			'description' => 'descripció',
			'status'      => 'estat',
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
		if (in_array($scenario, ['create','update']) && !preg_match('/^[\s\d\p{L}]{1,32}$/u', $this->name)) {
			$this->_addError('name', "Camp alfanumèric. Màxim 32 caracters. Es un camp obligatori.");
		}

		// validate alias
		if (in_array($scenario, ['create','update']) && !preg_match('/^[a-z0-9-]{1,32}$/', $this->alias)) {
			$this->_addError('alias', "Camp alfanumèric. Màxim 32 caracters. Es un camp obligatori.");
		}

        if (in_array($scenario, ['create'])) {
            $inUse = $this->_isInUse('alias', $this->alias);
            if($inUse)
                $this->_addError('alias', "Ja existeix un alias com aquest.");
            elseif($inUse === false)
                $this->_addError('alias', "Error comprobando alias.");
        }

		// validate description
		if (in_array($scenario, ['create','update']) && !preg_match('/^[\s\S]{1,128}$/', $this->description))  {
			$this->_addError('description', "Camp de text de 128 caracters màxim.");
		}

		// validate status 
		if(in_array($scenario, ['create','update']) && !isset($this->status)) $this->status = '1';

		// validate status
		if (in_array($scenario, ['create','update']) && !preg_match("/[0-9]/", $this->status))  {
			$this->_addError('status', "Sencer entre 0 y 9.");
		}	
		
		$this->_validated = true;
	}

	public function save() {
		if ($this->isValid('create')) {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "INSERT INTO {$this->_tableName} (name, alias, description, created, updated, status) 
		    		VALUES (:name, :alias, :description, now(), now(), :status)";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':alias', $this->alias, PDO::PARAM_STR); 
		    $stmt->bindParam(':description', $this ->description, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->execute();

			if($stmt->rowCount()==0) {
		    	return null;
		    }

		    $this->id = $this->_pdo->lastInsertId();
			return $this;
		}
		throw new ValidationException($this->getErrors(), "Category is not valid.");
	}

	public function update() {
		if ($this->isValid('update')) {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE {$this->_tableName} 
		    		SET name = :name, alias = :alias, description = :description, status = :status, updated = now()
		    		WHERE id = :id";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':alias', $this->alias, PDO::PARAM_STR); 
		    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
		    $stmt->execute();

		    if($stmt->rowCount()==0) {
		    	return null;
		    }
		    return $this;
		}
		throw new ValidationException($this->getErrors(), "Cstegory is not valid.");
	}
}