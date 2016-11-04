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

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
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

	protected function _validate($scenario) {
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