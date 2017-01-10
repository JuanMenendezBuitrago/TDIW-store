<?php
require_once(dirname(__FILE__).'/Model.php');

class Product extends Model {
	protected $_tableName = 'products';

	public $id;
	public $category_id;
	public $supplier_id;
	public $category;
	public $alias;
	public $name;
	public $intro;
	public $description;
	public $price;
	public $stock;
	public $picture;
	public $status;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'          => 'id',
			'category_id' => 'categoria',
			'name'        => 'nom',
			'intro'       => 'introducció',
			'description' => 'descripció',
			'price'       => 'preu',
			'stock'       => 'stock',
			'picture'     => 'imatge',
			'status'      => 'estat',
		];
	}

	protected function _validate($scenario) {
		// validate id: the value must exist in the database
		if (in_array($scenario, ['update']) && preg_match('pattern', $this->id)) {
			$inUse = $this->_isInUse('id', $this->id);
            if(!$inUse)
                $this->_addError('id', "No existeix un usuari registrat amb aquest id.");
            elseif($inUse === false)
                $this->_addError('id', "Error comprobando id.");
		}

		// validate category_id: the value must exist in the database
		if (in_array($scenario, ['update']) && preg_match('pattern', $this->category_id)) {
			$inUse = $this->_isInUse('category_id', $this->category_id);
            if(!$inUse)
                $this->_addError('category_id', "No existeix una categoria registrada amb aquest category_id.");
            elseif($inUse === false)
                $this->_addError('category_id', "Error comprobando category_id.");
		}

		// validate name
		if (in_array($scenario, ['create','update']) && preg_match('/^[\s\S]{1,50}$/', $this->name)) {
			$this->_addError('name', "Ha de contenir entre 1 i 50 caracters. Es un camp obligatori.");
		}
		// validate intro     
		if (in_array($scenario, ['create','update']) && preg_match('/^[\s\S]{140}$/', $this->intro)) {
			$this->_addError('intro', "Ha de contenir entre 1 i 140 caracters. Es un camp obligatori.");
		}
		// validate description
		if (in_array($scenario, ['create','update']) && preg_match('/^[\s\S]{500}$/', $this->description)) {
			$this->_addError('description', "Ha de contenir entre 1 i 500 caracters. Es un camp obligatori.");
		}
		// validate price
		if (in_array($scenario, ['create','update']) && preg_match('/^\d+(\.\d{2})?/', $this->price)) {
			$this->_addError('price', "Ha de contenir un nombre real amb dues xifres decimals fent servir un punt. Es un camp obligatori.");
		}
		// validate stock 
		if (in_array($scenario, ['create','update']) && preg_match('/^\d+$/', $this->stock)) {
			$this->_addError('stock', "Ha de contenir un nombre natural o un zero. Es un camp obligatori.");
		}
		// validate picture
		if (in_array($scenario, ['create','update']) && preg_match('pattern', $this->picture)) {
			$this->_addError('picture', "Ha de contenir 16 dígits.");
		}
	
		// validate picture 
		if(!isset($this->picture)) $this->picture = 'NULL';

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
		    $sql = "INSERT INTO {$this->_tableName} (category_id, name, intro, description, price, stock, picture, created, updated, status)
		    		VALUES (:category_id, :name, :intro, :description, :price, :stock, :picture, now(), now(), :status)";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR); 
		    $stmt->bindParam(':intro', $this ->intro, PDO::PARAM_STR); 
		    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
		    $stmt->bindParam(':price', $this->price, PDO::PARAM_STR); 
		    $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT); 
		    $stmt->bindParam(':picture', $this->picture, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->execute();
		
			if($stmt->rowCount()==0) {
		    	return null;
		    }

		    $this->id = $this->_pdo->lastInsertId();		    
			return $this;
		}   
		throw new ValidationException($this->getErrors(), "Product is not valid.");

	}

	public function update() {
		if ($this->isValid()) {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE {$this->_tableName} 
		    		SET category_id = :category_id, name = :name, intro = :intro, description = :description, price = :price, stock = :stock, picture =:picture, updated = now(), status = :status
		    		WHERE id = :id";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_STR);
		    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
		    $stmt->bindParam(':intro', $this->intro, PDO::PARAM_STR); 
		    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR); 
		    $stmt->bindParam(':price', $this->price, PDO::PARAM_STR); 
		    $stmt->bindParam(':stock', $this->stock, PDO::PARAM_STR); 
		    $stmt->bindParam(':picture', $this->picture, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR); 
		    $stmt->execute();
			
			if($stmt->rowCount()==0) {
		    	return null;
		    }		
			return $this;
		}
		throw new ValidationException($this->getErrors(), "Product is not valid.");
	}

	public function findAll($conditions = null) {
		$condString = "";
		if($conditions !== null && count($conditions)){
			$condArray = array();
			foreach($conditions as $column => $value) {
				$column_a = explode('.',$column);
				if(count($column_a)>1){
					$table = $column_a[0];
					$column = $column_a[1];
					$condArray[] = "{$table}.`$column` = :$column";
				}else{
					$condArray[] = "`$column` = :$column";
				}
			}
			$condString = " AND ".implode(" AND ", $condArray);
		}

		try {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "Select p.*, c.name as category, c.alias as alias from ".$this->_tableName." p,categories c WHERE `category_id` = c.`id`".$condString;

		    $stmt = $this->_pdo->prepare($sql);
		    if($conditions !== null) {
			    foreach($conditions as $column => $value) {
					$column_a = explode('.',$column);
					if(count($column_a)>1){
						$table = $column_a[0];
						$column = $column_a[1];
						$stmt->bindParam(':'.$column, $value, PDO::PARAM_STR);
					}else{
						$stmt->bindParam(':'.$column, $value, PDO::PARAM_STR);
					}

			    }		    	
		    }

			$stmt->execute();

			$result = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$obj = new static($row);
				$obj->setIsNewRecord(false);
				$result[] = $obj;
			}
			$result = count($result)==0?null:$result;
			return $result;
			// return $sql;
		} catch (PDOException $e) {
			return false;
		}
	}
}